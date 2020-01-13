<?php

namespace Modules\DevelCore\Entities;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Modules\DevelCore\Traits\Searchable;
use Modules\DevelCore\Traits\Sortable;

class Model extends EloquentModel
{
    use Sortable;
    use Searchable;
}
