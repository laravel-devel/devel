<?php

namespace Devel\Models;

use Illuminate\Foundation\Auth\User;
use Devel\Traits\Sortable;
use Devel\Traits\Searchable;
use Devel\Traits\HasRelationships;

class Authenticatable extends User
{
    use Sortable;
    use Searchable;
    use HasRelationships;
}
