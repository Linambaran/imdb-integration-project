<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // <--- JANGAN LUPA IMPORT INI

class MarketingController extends Controller
{
    public function index(Request $request)
    {
        // 1. CHEAT CODE: REFRESH CACHE
        // Buka: /marketing?refresh=1 untuk paksa update data
        if ($request->has('refresh')) {
            Cache::forget('marketing_dashboard_data');
        }

        $marketingDB = DB::connection('sqlsrv_marketing');

        // ==========================================
        // A. KONDISI SEARCHING (Realtime - No Cache)
        // ==========================================
        if ($request->filled('search')) {
            $keyword = $request->search;

            // 1. Cari ID di tabel mentah (Pakai Admin Connection biar cepat)
            $matchingIds = DB::connection('sqlsrv_admin')
                ->table('title_basics')
                ->select('tconst')
                // Ganti CONTAINS dengan LIKE jika FTS belum aktif
                ->whereRaw("CONTAINS((primaryTitle, originalTitle), ?)", ['"' . $keyword . '*"'])
                ->limit(200) // Batas pencarian
                ->pluck('tconst');

            // 2. Filter View Marketing
            $view1 = $marketingDB->table('vw_marketing_trending')
                ->select('show_name', 'startYear', 'vote_count', 'rating', 'network_name', 'genre_name')
                ->whereIn('tconst', $matchingIds)
                ->get()
                ->unique('show_name');

            $view2 = $marketingDB->table('vw_marketing_tier')
                ->select('show_name', 'content_tier', 'quality_category', 'rating')
                ->whereIn('tconst', $matchingIds)
                ->get()
                ->unique('show_name');

            $view3 = $marketingDB->table('vw_marketing_priority')
                ->select('show_name', 'campaign_priority', 'recommended_strategy', 'status_name')
                ->whereIn('tconst', $matchingIds)
                ->get();

            // Insight Genre dikosongkan saat search (karena data agregat)
            $view4 = collect([]);

            return view('marketing.dashboard', [
                'trending' => $view1,
                'tier'     => $view2,
                'priority' => $view3,
                'insights' => $view4,
                'search'   => $keyword
            ]);
        } 
        
        // ==========================================
        // B. KONDISI DEFAULT (CACHE - 30 MENIT)
        // ==========================================
        // Data disimpan selama 1800 detik (30 Menit)
        $data = Cache::remember('marketing_dashboard_data', 1800, function () use ($marketingDB) {
            
            // VIEW 1: TRENDING
            // Hapus 'distinct()' jika bikin lambat, unique() di PHP sudah cukup
            $view1 = $marketingDB->table('vw_marketing_trending')
                ->select('show_name', 'startYear', 'vote_count', 'rating', 'network_name', 'genre_name')
                ->orderBy('vote_count', 'desc') // Pastikan kolom ini di-INDEX di database
                ->take(50) // Buffer: Ambil 50
                ->get()
                ->unique('show_name') // Saring duplikat
                ->values()
                ->take(6); // Final: Ambil 6

            // VIEW 2: TIER
            $view2 = $marketingDB->table('vw_marketing_tier')
                ->select('show_name', 'content_tier', 'quality_category', 'rating')
                ->take(50) // Buffer
                ->get()
                ->unique('show_name')
                ->values()
                ->take(8);

            // VIEW 3: PRIORITY
            $view3 = $marketingDB->table('vw_marketing_priority')
                ->select('show_name', 'campaign_priority', 'recommended_strategy', 'status_name')
                ->where('campaign_priority', '!=', 'Low Priority')
                ->orderBy('campaign_priority', 'asc') // Urutkan High -> Medium
                ->take(10)
                ->get();

            // VIEW 4: INSIGHTS
            $view4 = $marketingDB->table('vw_marketing_genre_insights')
                ->select('genre_name', 'market_status', 'targeting_recommendation', 'avg_popularity')
                ->orderBy('avg_popularity', 'desc')
                ->take(8)
                ->get();

            return [
                'trending' => $view1,
                'tier'     => $view2,
                'priority' => $view3,
                'insights' => $view4,
                'search'   => null
            ];
        });

        return view('marketing.dashboard', $data);
    }
}