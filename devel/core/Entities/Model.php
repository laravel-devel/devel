<?php

namespace Devel\Core\Entities;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Devel\Core\Traits\HasRelationships;
use Devel\Core\Traits\Searchable;
use Devel\Core\Traits\Sortable;

class Model extends EloquentModel
{
    use Sortable;
    use Searchable;
    use HasRelationships;
}
