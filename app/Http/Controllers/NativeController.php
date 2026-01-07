<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NativeController extends Controller
{
    public function show($tconst)
    {
        $movie = DB::table('vw_native_katalog')
            ->where('tconst', $tconst)
            ->first();

        if (!$movie) {
            abort(404);
        }

        return view('native.show', compact('movie'));
    }
}