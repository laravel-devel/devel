<?php

namespace Modules\DevelCore\Entities;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public function scopeSort($query, $sort)
    {
        $sort = explode('|', $sort);

        if (count($sort) < 2) {
            return $query;
        }

        $order = strtolower($sort[1]);
        $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';

        return $query->orderBy($sort[0], $order);
    }
}
