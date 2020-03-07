<?php

namespace Devel\Core\Traits;

trait Searchable
{
    protected $searchable = [];

    public function scopeSearch($query, $search)
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
