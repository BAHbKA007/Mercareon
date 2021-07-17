<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GromasLieferscheinController extends Controller
{
    public function push_to_database(Request $request)
    {
        return var_dump(json_decode($request));
    }
}
