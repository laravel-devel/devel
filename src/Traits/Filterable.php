<?php

namespace Devel\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply filters to the query.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, $request): Builder
    {
        $table = $this->getTable();

        foreach ($request->all() as $field => $values) {
            if (!\Str::startsWith($field, 'f_')) {
                continue;
            }

            $filter = substr($field, 2);

            $values = \Str::contains($values, '|')
                ? explode('|', $values)
                : [$values];

            $method = \Str::camel("filterBy_{$filter}");

            // Custom filter logic
            if (method_exists($this, $method)) {
                return $this->{$method}($query, $values);
            }

            if (\Str::contains($filter, '__')) {
                $field = str_replace('__', '.', $filter);

                list($method, $filter) = explode('__', $filter);

                $query = $this->leftJoinRelationship($query, $method);
            } else {
                $field = "{$table}.{$filter}";
            }

            // Default filter logic
            $query->where(function ($q) use ($field, $values) {
                foreach ($values as $value) {
                    if (is_null($value)) {
                        continue;
                    }

                    $q->where($field, $value);
                }
            });
        }

        return $query;
    }
}
