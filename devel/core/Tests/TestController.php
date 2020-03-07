<?php

namespace Devel\Core\Tests;

use Devel\Core\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index()
    {
        return response()->json([]);
    }
}
