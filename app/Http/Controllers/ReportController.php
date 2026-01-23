<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    /**
     * Generate a public link (Report) for an analysis.
     */
    public function store(Request $request, Analysis $analysis): RedirectResponse
    {
        // Manual authorization check
        if ($analysis->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if report already exists
        $report = $analysis->report;

        if (!$report) {
            $report = $analysis->report()->create([
                'public_link_token' => Str::uuid(),
            ]);
        }

        return redirect()->back()->with('status', 'Public report link generated!');
    }

    /**
     * Display the specified resource (Public View).
     */
    public function show(string $token): View
    {
        $report = Report::where('public_link_token', $token)->with('analysis')->firstOrFail();

        return view('reports.show', [
            'analysis' => $report->analysis,
            'report' => $report,
        ]);
    }

    /**
     * Revoke a public link.
     */
    public function destroy(Analysis $analysis): RedirectResponse
    {
        if ($analysis->user_id !== Auth::id()) {
            abort(403);
        }

        $analysis->report()->delete();

        return redirect()->back()->with('status', 'Public report link revoked.');
    }
}
