<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExecutiveController extends Controller
{
    public function index()
    {
        $kpi = DB::select('EXEC sp_exec_dashboard');
        $kpi_data = !empty($kpi) ? $kpi[0] : null;

        $trends = DB::select('EXEC sp_exec_trend');

        $genres = DB::select('EXEC sp_exec_genre');
        $genres = collect($genres)->sortByDesc('roi_estimate')->take(5);

        $top_assets = DB::select('EXEC sp_exec_roi');
        $assets = collect($top_assets)
            ->unique('show_name')
            ->values()
            ->take(10);

        return view('executive.dashboard', [
            'kpi'       => $kpi_data,
            'trends'    => $trends,
            'genres'    => $genres,
            'assets'    => $assets
        ]);
    }
}
