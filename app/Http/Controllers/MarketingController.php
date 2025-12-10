<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketingController extends Controller
{
public function index()
{
    $view1 = DB::table('vw_marketing_trending')
        ->select('show_name', 'startYear', 'vote_count', 'rating', 'network_name', 'genre_name')
        ->distinct()
        ->orderBy('vote_count', 'desc')
        ->take(15)
        ->get()
        ->unique('show_name')
        ->take(6);

    $view2 = DB::table('vw_marketing_tier')
        ->select('show_name', 'content_tier', 'quality_category', 'rating')
        ->distinct()
        ->take(12)
        ->get()
        ->unique('show_name')
        ->take(5);

    $view3 = DB::table('vw_marketing_priority')
        ->select('show_name', 'campaign_priority', 'recommended_strategy', 'status_name')
        ->where('campaign_priority', '!=', 'Low Priority')
        ->distinct()
        ->take(5)
        ->get();

    $view4 = DB::table('vw_marketing_genre_insights')
        ->select('genre_name', 'market_status', 'targeting_recommendation', 'avg_popularity')
        ->orderBy('avg_popularity', 'desc')
        ->distinct()
        ->take(8)
        ->get();

    return view('marketing.dashboard', [
        'trending' => $view1,
        'tier'     => $view2,
        'priority' => $view3,
        'insights' => $view4
    ]);
}
}