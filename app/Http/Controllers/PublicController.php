<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function welcome(Request $request)
    {
        // A. Katalog Random
        $randomIds = DB::connection('sqlsrv_admin')
            ->table('imdb_tv')
            ->join('tv_shows', 'imdb_tv.show_id', '=', 'tv_shows.show_id')
            ->where('tv_shows.id_status', 2) // Approved only
            ->inRandomOrder() // Random di tabel kecil jauh lebih cepat
            ->limit(50)
            ->pluck('tconst');
        $katalog = DB::table('vw_native_katalog')
            ->whereIn('tconst', $randomIds)
            ->get()
            ->unique('imdb_title')
            ->take(15);

        // B. Most Popular
        $popularIds = DB::connection('sqlsrv_admin')
            ->table('tv_shows')
            ->join('imdb_tv', 'tv_shows.show_id', '=', 'imdb_tv.show_id')
            ->where('tv_shows.id_status', 2)
            ->orderBy('tv_shows.popularity', 'desc')
            ->limit(50)
            ->pluck('imdb_tv.tconst');
        $popular = DB::connection('sqlsrv_admin')
            ->table('vw_native_katalog')
            ->whereIn('tconst', $popularIds)
            ->orderBy('popularity', 'desc') // Sort ulang hasil akhir
            ->get()
            ->unique('show_name')
            ->take(10);

        $newReleaseIds = DB::connection('sqlsrv_admin')
            ->table('title_basics')
            ->join('imdb_tv', 'title_basics.tconst', '=', 'imdb_tv.tconst') // Pastikan ada di katalog kita
            ->join('tv_shows', 'imdb_tv.show_id', '=', 'tv_shows.show_id')
            ->where('tv_shows.id_status', 2)
            ->orderBy('title_basics.startYear', 'desc')
            ->limit(50)
            ->pluck('title_basics.tconst');
        $newReleases = DB::connection('sqlsrv_admin')
            ->table('vw_native_katalog')
            ->whereIn('tconst', $newReleaseIds)
            ->orderBy('startYear', 'desc')
            ->get()
            ->unique('show_name')
            ->take(10);

        $topRatedIds = DB::connection('sqlsrv_admin')
            ->table('title_ratings')
            ->join('imdb_tv', 'title_ratings.tconst', '=', 'imdb_tv.tconst')
            ->join('tv_shows', 'imdb_tv.show_id', '=', 'tv_shows.show_id')
            ->where('tv_shows.id_status', 2)
            ->orderBy('title_ratings.averageRating', 'desc')
            ->limit(50)
            ->pluck('title_ratings.tconst');
        $topRated = DB::connection('sqlsrv_admin')
            ->table('vw_native_katalog')
            ->whereIn('tconst', $topRatedIds)
            ->orderBy('rating', 'desc')
            ->get()
            ->unique('imdb_title')
            ->take(12);

        $data = [
            'katalog'      => $katalog,
            'popular'      => $popular,
            'new_releases' => $newReleases,
            'top_rated'    => $topRated,
            'search_query' => $request->search ?? null
        ];

        if ($request->filled('search')) {
            $keyword = $request->search;

            $matchingIds = DB::table('title_basics')
                ->select('tconst')
                ->whereRaw("CONTAINS((primaryTitle, originalTitle), ?)", ['"' . $keyword . '*"'])
                ->limit(100)
                ->pluck('tconst');

            $data['search_results'] = DB::table('vw_native_katalog')
                ->whereIn('tconst', $matchingIds)
                ->get()
                ->unique('show_name')
                ->values();
        }

        return view('welcome', $data);
    }
    
    public function searchPreview(Request $request)
    {
        $query = $request->get('q');
        if (empty($query)) return response()->json([]);

        try {
            $results = DB::table('vw_native_katalog')
                ->select('tconst', 'show_name', 'startYear', 'rating')
                ->where('show_name', 'LIKE', "%{$query}%")
                ->orWhere('imdb_title', 'LIKE', "%{$query}%")
                ->orderBy('popularity', 'desc')
                ->take(20)
                ->get()
                ->unique('show_name')
                ->values()
                ->take(5);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}