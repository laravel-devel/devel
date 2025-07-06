<?php

namespace Devel\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    protected $searchable = [];

    /**
     * @template T
     * @param Builder<T> $query
     * @return Builder<T>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        if (!is_array($this->searchable)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            for ($i = 0; $i < count($this->searchable); $i++) {
                $q->orWhere($this->searchable[$i], 'LIKE', "%{$search}%");
            }
        });
    }
}
