<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LayoutTesterController extends Controller
{
    public function index(Request $request) {

        return view('layouttester');

    }
}
