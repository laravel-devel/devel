<?php

namespace Modules\DevelCore\Traits;

trait Searchable
{
    protected $searchable = [];

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            for ($i = 0; $i < count($this->searchable); $i++) {
                if ($i == 0) {
                    $q->where($this->searchable[$i], 'LIKE', "%{$search}%");
                } else {
                    $q->orWhere($this->searchable[$i], 'LIKE', "%{$search}%");
                }
            }
        });
    }
}
