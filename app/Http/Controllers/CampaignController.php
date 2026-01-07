<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::orderBy('start_date', 'desc')->get();
        return view('marketing.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('marketing.campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:100',
            'budget' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_audience' => 'required|string',
        ]);

        Campaign::create($validated);

        return redirect()->route('campaigns.index')->with('success', 'Kampanye berhasil dibuat!');
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        return view('marketing.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:100',
            'budget' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_audience' => 'required|string',
        ]);

        $campaign = Campaign::findOrFail($id);
        $campaign->update($validated);

        return redirect()->route('campaigns.index')->with('success', 'Kampanye berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'Kampanye dihapus.');
    }
}