<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Services\TextAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AnalysisController extends Controller
{
    protected $analyzer;

    public function __construct(TextAnalysisService $analyzer)
    {
        $this->analyzer = $analyzer;
    }

    public function index(): View
    {
        $analyses = Auth::user()->analyses()->latest()->paginate(9);
        $totalAnalyses = Auth::user()->analyses()->count();
        $totalWords = Auth::user()->analyses()->sum('word_count');

        return view('analyses.index', compact('analyses', 'totalAnalyses', 'totalWords'));
    }

    public function create(): View
    {
        return view('analyses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10',
            'target_keyword' => 'nullable|string|max:255',
        ]);

        $metrics = $this->analyzer->analyze($validated['content']);

        $analysis = Auth::user()->analyses()->create([
            'title' => $validated['title'] ?? 'Untitled Analysis',
            'content_raw' => $validated['content'],
            'target_keyword' => $validated['target_keyword'] ?? null,
            ...$metrics,
            'status' => 'published', // Auto-publish for now
        ]);

        return redirect()->route('analyses.show', $analysis)->with('status', 'Analysis complete!');
    }

    public function show(Analysis $analysis): View
    {
        if ($analysis->user_id !== Auth::id()) {
            abort(403);
        }
        return view('analyses.show', compact('analysis'));
    }

    public function edit(Analysis $analysis): View
    {
        if ($analysis->user_id !== Auth::id()) {
            abort(403);
        }
        return view('analyses.edit', compact('analysis'));
    }

    public function update(Request $request, Analysis $analysis): RedirectResponse
    {
        if ($analysis->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $analysis->update([
            'title' => $validated['title'],
        ]);

        return redirect()->route('analyses.show', $analysis)->with('status', 'Analysis updated successfully.');
    }

    public function destroy(Analysis $analysis): RedirectResponse
    {
        if ($analysis->user_id !== Auth::id()) {
            abort(403);
        }
        $analysis->delete();
        return redirect()->route('analyses.index')->with('status', 'Analysis deleted.');
    }
}
