<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function welcome(Request $request)
    {
        if ($request->filled('search')) {
            $keyword = $request->search;

            $searchResults = DB::table('vw_native_katalog')
                ->where('show_name', 'LIKE', "%{$keyword}%")
                ->orWhere('genre_name', 'LIKE', "%{$keyword}%")
                ->orderBy('popularity', 'desc')
                ->take(50)
                ->get()
                ->unique('show_name');

            return view('welcome', [
                'search_results' => $searchResults,
                'search_query'   => $keyword
            ]);
        }
        $katalog = DB::table('vw_native_katalog')
            ->orderBy('startYear', 'desc')
            ->take(100) 
            ->get()
            ->unique('show_name')
            ->take(20);

        $popular = DB::table('vw_native_most_popular')
            ->orderBy('popularity', 'desc') 
            ->take(50)
            ->get()
            ->unique('show_name')
            ->take(10);

        $newReleases = DB::table('vw_native_new_releases')
            ->orderBy('startYear', 'desc')
            ->take(50)
            ->get()
            ->unique('show_name')
            ->take(10);

        $topRated = DB::table('vw_native_top_rating')
            ->orderBy('rating', 'desc')
            ->take(50)
            ->get()
            ->unique('show_name')
            ->take(10);

        return view('welcome', [
            'katalog'      => $katalog,
            'popular'      => $popular,
            'new_releases' => $newReleases,
            'top_rated'    => $topRated
        ]);
    }

    public function searchPreview(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {return response()->json([]);}

        try{
            $results = DB::table('vw_native_katalog')
                ->where('show_name', 'LIKE', "%{$query}%")
                ->orWhere('genre_name', 'LIKE', "%{$query}%")
                ->select('show_name', 'startYear', 'rating', 'popularity')
                ->distinct()
                ->orderBy('popularity', 'desc')
                ->take(5)
                ->get();

            return response()->json($results);
            } 
        catch (\Exception $e) {return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}