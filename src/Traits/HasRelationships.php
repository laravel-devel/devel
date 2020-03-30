<?php

namespace Devel\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

trait HasRelationships
{
    /**
     * Get the list of a model's relationships
     *
     * @return array
     */
    public function getRelationships(): array
    {
        $reflector = new \ReflectionClass($this);

        $relationships = [];

        foreach ($reflector->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $acceptedClasses = Str::startsWith($method->class, [
                'App\\',
                'Modules\\',
                'Devel\\',
            ]);

            if (count($method->getParameters()) || !$acceptedClasses || $method->getName() === 'getRelationships') {
                continue;
            }

            try {
                $return = $method->invoke($this);

                if (!is_object($return) && !method_exists($return, 'getRelated')) {
                    continue;
                }

                $className = get_class($return->getRelated());

                if ($return instanceof Relation && substr($className, 0, 10) !== 'Illuminate') {
                    $relationships[$method->getName()] = [
                        'type' => class_basename($return),
                        'model' => get_class($return->getRelated()),
                        'method' => $method,
                        'relation' => $return,
                    ];
                }
            } catch (\Exception $e) {
                // Do nothing
            }
        }

        return $relationships;
    }

    /**
     * Left join a relationship.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string $method
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function leftJoinRelationship(Builder $query, string $method): Builder
    {
        $relationships = $this->getRelationships();
        $relation = $relationships[$method] ?? null;

        if (!$relation) {
            // Sometimes a relationship could not be found because the string is
            // in a wrong case (camel instead of snake or ise-versa)
            $reformatted = strpos($method, '_') !== false
                ? \Str::camel($method)
                : \Str::snake($method);

            $relation = $relationships[$reformatted] ?? null;

            if (!$relation) {
                $model = get_class($this);

                throw new \Exception("Relationship '{$method}' does not exist in the '{$model}' model");
            }
        }

        // The table names
        $relatedTable = (new $relation['model'])->getTable();

        // Don't join the table more than ones - produces an SQL error
        if ($this->tableJoined($query, $relatedTable)) {
            return $query;
        }

        // Foreign and local keys
        $lk = $relation['relation']->getQualifiedParentKeyName();   // With the table name
        $fk = $relation['relation']->getForeignKeyName();   // Without the table name

        $lk = is_string($lk) ? [$lk] : $lk;
        $fk = is_string($fk) ? [$fk] : $fk;

        // Now we can add a join. Alliasing the join table as the method name.
        $query->leftJoin($relatedTable . ' as ' . $method, function ($join) use ($fk, $lk, $method) {
            for ($i = 0; $i < count($fk); $i++) {
                $join->on($lk[$i], '=', $method . '.' . $fk[$i]);
            }
        });

        return $query;
    }

    /**
     * Determine if a table has already be joined to the query
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string $table
     * @return boolean
     */
    protected function tableJoined(Builder $query, string $table): bool
    {
        return \Str::contains($query->toSql(), "join `{$table}`");
    }
}
