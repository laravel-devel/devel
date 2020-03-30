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
        $method = explode('.', $sort)[0];

        return $this->leftJoinRelationship($query, $method);
    }
}
