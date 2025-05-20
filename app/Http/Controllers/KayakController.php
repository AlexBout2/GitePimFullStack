<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KayakController extends Controller
{
    public function index()
{
    return view('kayak.index');
}
}
