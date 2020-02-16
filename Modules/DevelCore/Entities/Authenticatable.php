<?php

namespace Modules\DevelCore\Entities;

use Illuminate\Foundation\Auth\User;
use Modules\DevelCore\Traits\Sortable;
use Modules\DevelCore\Traits\Searchable;
use Modules\DevelCore\Traits\HasRelationships;

class Authenticatable extends User
{
    use Sortable;
    use Searchable;
    use HasRelationships;
}
