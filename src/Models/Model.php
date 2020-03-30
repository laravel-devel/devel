<?php

namespace Devel\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Devel\Traits\HasRelationships;
use Devel\Traits\Filterable;
use Devel\Traits\Searchable;
use Devel\Traits\Sortable;

class Model extends EloquentModel
{
    use Filterable;
    use Sortable;
    use Searchable;
    use HasRelationships;
}
