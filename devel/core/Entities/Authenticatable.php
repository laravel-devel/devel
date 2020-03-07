<?php

namespace Devel\Core\Entities;

use Illuminate\Foundation\Auth\User;
use Devel\Core\Traits\Sortable;
use Devel\Core\Traits\Searchable;
use Devel\Core\Traits\HasRelationships;

class Authenticatable extends User
{
    use Sortable;
    use Searchable;
    use HasRelationships;
}
