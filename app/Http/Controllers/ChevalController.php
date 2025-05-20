<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChevalController extends Controller
{
    public function index()
{
    return view('cheval.index');
}
}
