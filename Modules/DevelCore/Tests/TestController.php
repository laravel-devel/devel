<?php

namespace Modules\DevelCore\Tests;

use Modules\DevelCore\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index()
    {
        return response()->json([]);
    }
}
