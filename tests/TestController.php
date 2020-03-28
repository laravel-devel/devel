<?php

namespace Devel\Dev\Tests;

use Devel\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index()
    {
        return response()->json([]);
    }
}
