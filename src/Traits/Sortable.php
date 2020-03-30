<?php

namespace Devel\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function scopeSort($query, $sort)
    {
        if (!$sort) {
            return $query;
        }

        $sort = explode('|', $sort);

        if (count($sort) < 2) {
            return $query;
        }

        $order = strtolower($sort[1]);
        $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';

        $sort = $sort[0];

        // Sorting by a relationship column
        if (strpos($sort, '.') !== false) {
            $query = $this->sortJoinRelationship($query, $sort);
        }

        // Sorting by an own column
        return $query->orderBy($sort, $order);
    }

    /**
     * Left Join required relationship to the query to perform sorting. 
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sort
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function sortJoinRelationship(Builder $query, string $sort): Builder
    {
        list($method, $col) = explode('.', $sort);

        if (!$model = $query->getModel()) {
            throw new \Exception('Could not define model for the sort query.');
        }

        $relationships = $model->getRelationships();
        $relation = $relationships[$method] ?? null;

        if (!$relation) {
            // Sometimes a relationship could not be found because the string is
            // in a wrong case (camel instead of snake or ise-versa)
            $reformatted = strpos($method, '_') !== false
                ? \Str::camel($method)
                : \Str::snake($method);

            $relation = $relationships[$reformatted] ?? null;

            if (!$relation) {
                $model = get_class($model);

                throw new \Exception("Relationship '{$method}' does not exist in the '{$model}' model");
            }
        }

        // The table names
        $relatedTable = (new $relation['model'])->getTable();

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
}
