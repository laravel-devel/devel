<?php

namespace Devel\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
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
                // Require relationship methods to have explicit return types.
                // We don't want to invoke non-relationship methods because
                // those might perform unwanted actions.
                $returnType = $method->getReturnType();

                if (!$returnType || get_class($returnType) !== 'ReflectionNamedType') {
                    continue;
                }

                if (!method_exists($returnType->getName(), 'getRelated')) {
                    continue;
                }

                // Now it should be safe to invoke a method
                $return = $method->invoke($this);

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
     * @param string $methods
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function leftJoinRelationship(Builder $query, string $methods): Builder
    {
        $methods = explode('.', $methods);

        $baseModel = $this;
        $chainedMethod = [];

        foreach ($methods as $method) {
            $chainedMethod[] = $method;

            $relationships = $baseModel->getRelationships();
            $relation = $relationships[$method] ?? null;

            if (!$relation) {
                // Sometimes a relationship could not be found because the string is
                // in a wrong case (camel instead of snake or ise-versa)
                $reformatted = strpos($method, '_') !== false
                    ? \Str::camel($method)
                    : \Str::snake($method);

                $relation = $relationships[$reformatted] ?? null;

                if (!$relation) {
                    $class = get_class($this);

                    throw new \Exception("Relationship '{$method}' does not exist in the '{$class}' model");
                }
            }

            // The table names
            $relatedModel = new $relation['model'];

            $relatedTable = $relatedModel->getConnection()->getDatabaseName()
                . '.' . $relatedModel->getTable();

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
            $chainedMethodStr = '`' . implode('.', $chainedMethod) . '`';

            // When adding joing for 2nd level relations and above, we need to
            // replace the table names with the aliases we gave to the upper
            // level joins

            $alias = '';
            if (count($chainedMethod) > 1) {
                $alias = $chainedMethod;

                array_pop($alias);

                $alias = implode('.', $alias);

                for ($i = 0; $i < count($lk); $i++) {
                    // Grab the field name
                    $field = explode('.', $lk[$i]);
                    $field = array_pop($field);

                    // Attach it to the alias instead
                    $lk[$i] = "`{$alias}`.{$field}";
                }
            }

            $query->leftJoin(DB::raw($relatedTable . ' as ' . $chainedMethodStr), function ($join) use ($fk, $lk, $chainedMethodStr, $alias) {
                for ($i = 0; $i < count($fk); $i++) {
                    $join->on(DB::raw($lk[$i]), '=', DB::raw($chainedMethodStr . '.' . $fk[$i]));
                }
            });

            $baseModel = $relatedModel;
        }

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
