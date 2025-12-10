<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        $data = DB::select("SELECT @@VERSION as sql_version");
        return view('latihan', ['info' => $data[0]->sql_version]);
    }
}
