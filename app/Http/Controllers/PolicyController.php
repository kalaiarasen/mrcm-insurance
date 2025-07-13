<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function newPolicy()
    {
        return view('pages.new-policy.index');
    }
}
