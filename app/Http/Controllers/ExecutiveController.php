<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExecutiveController extends Controller
{
    public function index(Request $request)
    {
        $execDB = DB::connection('sqlsrv_executive');
        $matchingIds = null;

        // ==========================================
        // 1. LOGIKA SEARCH (FTS - Cari ID Dulu)
        // ==========================================
        if ($request->filled('search')) {
            $keyword = $request->search;

            // Cari ID di tabel mentah (Pakai koneksi ADMIN)
            try {
                $matchingIds = DB::connection('sqlsrv_admin')
                    ->table('title_basics')
                    ->select('tconst')
                    // Pastikan syntax FTS (CONTAINS) valid di SQL Server Anda
                    ->whereRaw("CONTAINS((primaryTitle, originalTitle), ?)", ['"' . $keyword . '*"'])
                    ->limit(500)
                    ->pluck('tconst')
                    ->toArray();
            } catch (\Exception $e) {
                // Fallback jika FTS error/belum setup
                $matchingIds = [];
            }
        }

        // ==========================================
        // 2. QUERY UTAMA ($items) - UNTUK TABEL SEARCH/GLOBAL
        // ==========================================
        // Ini yang KETINGGALAN sebelumnya
        
        $itemsQuery = $execDB->table('imdb_tv')
            ->join('title_basics', 'imdb_tv.tconst', '=', 'title_basics.tconst')
            ->join('tv_shows', 'imdb_tv.show_id', '=', 'tv_shows.show_id')
            ->leftJoin('title_ratings', 'imdb_tv.tconst', '=', 'title_ratings.tconst')
            ->select(
                'imdb_tv.tconst',
                'title_basics.primaryTitle as show_name', // Alias biar sama dengan view
                'title_basics.startYear',
                'title_ratings.averageRating as rating',
                'tv_shows.popularity'
            );

        // Jika sedang search, filter berdasarkan ID
        if ($request->filled('search')) {
            $itemsQuery->whereIn('imdb_tv.tconst', $matchingIds ?? []);
            $itemsQuery->orderBy('tv_shows.popularity', 'desc'); // Urutkan popularitas
        } else {
            // Jika tidak search, tampilkan konten terpopuler (Default)
            $itemsQuery->orderBy('tv_shows.popularity', 'desc');
        }

        // Pagination untuk tabel utama
        $items = $itemsQuery->paginate(15)->withQueryString();


        // ==========================================
        // 3. AMBIL DATA STORED PROCEDURE (KPI, Trend, dll)
        // ==========================================
        try {
            $kpi = $execDB->select('EXEC sp_exec_dashboard');
            $kpi_data = !empty($kpi) ? $kpi[0] : null;

            $trends = $execDB->select('EXEC sp_exec_trend');

            $genres = $execDB->select('EXEC sp_exec_genre');
            $genres = collect($genres)->sortByDesc('roi_estimate')->take(7);

            // Top Assets (ROI) - Filter jika search
            $top_assets = $execDB->select('EXEC sp_exec_roi');
            $assets = collect($top_assets);
            
            if ($request->filled('search') && $matchingIds !== null) {
                $assets = $assets->whereIn('tconst', $matchingIds);
            }
            
            $assets = $assets->unique('show_name')->values()->take(20);

        } catch (\Exception $e) {
            $kpi_data = null; $trends = []; $genres = []; $assets = [];
        }

        // ==========================================
        // 4. APPROVAL LIST
        // ==========================================
        $approvalQuery = $execDB->table('imdb_tv')
            ->join('title_ratings', 'imdb_tv.tconst', '=', 'title_ratings.tconst')
            ->join('title_basics', 'imdb_tv.tconst', '=', 'title_basics.tconst')
            ->join('tv_shows', 'imdb_tv.show_id', '=', 'tv_shows.show_id')
            ->select(
                'imdb_tv.tconst',
                'title_basics.primaryTitle',
                'title_basics.startYear',
                'title_ratings.averageRating',
                'tv_shows.id_status'
            )
            ->where(function($q) {
                $q->where('tv_shows.id_status', '=', 1)
                  ->orWhereNull('tv_shows.id_status');
            });

        if ($request->filled('search') && $matchingIds !== null) {
            $approvalQuery->whereIn('imdb_tv.tconst', $matchingIds);
        }

        $approvalList = $approvalQuery
            ->orderBy('title_basics.startYear', 'desc')
            ->take(10)
            ->get();

        // ==========================================
        // 5. SYSTEM LOGS
        // ==========================================
        $systemLogs = $execDB->table('user_access_log')
            ->orderBy('access_time', 'desc')
            ->take(5)
            ->get();

        return view('executive.dashboard', [
            'items'         => $items,  // <-- Variabel ini wajib ada sekarang
            'kpi'           => $kpi_data,
            'trends'        => $trends,
            'genres'        => $genres,
            'assets'        => $assets,
            'approvalList'  => $approvalList,
            'systemLogs'    => $systemLogs,
            'search'        => $request->search
        ]);
    }

    // ... method updateStatus dan show biarkan tetap sama ...
    public function updateStatus(Request $request, $tconst)
    {
        $status = $request->input('status');
        DB::connection('sqlsrv_executive')
            ->table('tv_shows')
            ->join('imdb_tv', 'tv_shows.show_id', '=', 'imdb_tv.show_id')
            ->where('imdb_tv.tconst', $tconst)
            ->update(['tv_shows.id_status' => $status]);
        return redirect()->back()->with('success', 'Status updated.');
    }

    public function show($tconst)
    {
        $execDB = DB::connection('sqlsrv_executive');
        $movie = $execDB->table('imdb_tv')
            ->join('title_basics', 'imdb_tv.tconst', '=', 'title_basics.tconst')
            ->join('tv_shows', 'imdb_tv.show_id', '=', 'tv_shows.show_id')
            ->join('tbasic_genres', 'imdb_tv.tconst', '=', 'tbasic_genres.tconst')
            ->leftJoin('title_ratings', 'imdb_tv.tconst', '=', 'title_ratings.tconst')
            ->select(
                'imdb_tv.tconst',
                'title_basics.primaryTitle',
                'title_basics.originalTitle',
                'title_basics.startYear',
                'tbasic_genres.genre',
                'title_basics.runtimeMinutes',
                'title_ratings.averageRating',
                'title_ratings.numVotes',
                'tv_shows.id_status',
                'tv_shows.overview',
            )
            ->where('imdb_tv.tconst', $tconst)
            ->first();
            
        return view('executive.show', compact('movie'));
    }
}