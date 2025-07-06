<?php

namespace Devel\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait Sortable
{
    /**
     * @template T
     * @param Builder<T> $query
     * @return Builder<T>
     */
    public function scopeSort(Builder $query, string $sort): Builder
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

            // We need to wrap the multilevel relationship/join name into tildas

            $parts = explode('.', $sort);
            $field = array_pop($parts);

            $sort = '`' . implode('.', $parts) . '`.`' . $field . '`';
        } else {
            $sort = "`{$sort}`";
        }

        // Sorting by an own column
        return $query->orderBy(DB::raw($sort), $order);
    }

    /**
     * Left Join required relationship to the query to perform sorting.
     *
     * @template T
     * @param Builder<T> $query
     * @return Builder<T>
     */
    protected function sortJoinRelationship(Builder $query, string $sort): Builder
    {
        $method = explode('.', $sort);

        // Remove the field name from the chain, only keep the relationship names
        array_pop($method);

        return $this->leftJoinRelationship($query, implode('.', $method));
    }
}
