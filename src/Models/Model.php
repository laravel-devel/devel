<?php

namespace Devel\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Devel\Traits\HasRelationships;
use Devel\Traits\Searchable;
use Devel\Traits\Sortable;

class Model extends EloquentModel
{
    use Sortable;
    use Searchable;
    use HasRelationships;
}
