<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\Models\Website;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KeywordController extends Controller
{
    public function index(Request $request)
    {
        $query = Keyword::with('website');

        if ($request->website_id) {
            $query->where('website_id', $request->website_id);
        }

        return Inertia::render('Keywords/Index', [
            'keywords' => $query->latest()->paginate(20),
            'websites' => Website::with('project')->get(),
            'websiteId' => $request->website_id,
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Keywords/Create', [
            'websites' => Website::with('project')->get(),
            'selectedWebsiteId' => $request->website_id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'website_id' => 'required|exists:websites,id',
            'keyword' => 'required|string|max:255',
            'frequency' => 'nullable|integer|min:0',
            'difficulty' => 'nullable|integer|min:0|max:100',
            'current_position' => 'nullable|integer|min:0',
            'target_position' => 'nullable|integer|min:0',
        ]);

        Keyword::create($validated);

        return redirect()->route('keywords.index', ['website_id' => $validated['website_id']])
            ->with('success', 'Ключевое слово добавлено');
    }

    public function show(Keyword $keyword)
    {
        return Inertia::render('Keywords/Show', [
            'keyword' => $keyword->load('website'),
        ]);
    }

    public function edit(Keyword $keyword)
    {
        return Inertia::render('Keywords/Edit', [
            'keyword' => $keyword,
            'websites' => Website::with('project')->get(),
        ]);
    }

    public function update(Request $request, Keyword $keyword)
    {
        $validated = $request->validate([
            'website_id' => 'required|exists:websites,id',
            'keyword' => 'required|string|max:255',
            'frequency' => 'nullable|integer|min:0',
            'difficulty' => 'nullable|integer|min:0|max:100',
            'current_position' => 'nullable|integer|min:0',
            'target_position' => 'nullable|integer|min:0',
        ]);

        $keyword->update($validated);

        return redirect()->route('keywords.index', ['website_id' => $keyword->website_id])
            ->with('success', 'Ключевое слово обновлено');
    }

    public function destroy(Keyword $keyword)
    {
        $websiteId = $keyword->website_id;
        $keyword->delete();

        return redirect()->route('keywords.index', ['website_id' => $websiteId])
            ->with('success', 'Ключевое слово удалено');
    }

    // Страница импорта
    public function importForm(Website $website)
    {
        return Inertia::render('Keywords/Import', [
            'website' => $website,
        ]);
    }

    // Обработка импорта CSV
    public function import(Request $request, Website $website)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');

        $headers = fgetcsv($handle, 1000, ';');
        $keywordIndex = array_search('keyword', array_map('strtolower', $headers));

        if ($keywordIndex === false) {
            fclose($handle);
            return back()->with('error', 'CSV должен содержать колонку "keyword"');
        }

        $imported = 0;

        while (($row = fgetcsv($handle, 1000, ';')) !== false) {
            $keyword = trim($row[$keywordIndex] ?? '');
            if (empty($keyword)) continue;

            Keyword::updateOrCreate(
                ['website_id' => $website->id, 'keyword' => $keyword],
                ['keyword' => $keyword]
            );
            $imported++;
        }

        fclose($handle);

        return redirect()->route('keywords.index', ['website_id' => $website->id])
            ->with('success', "Импортировано ключевых слов: {$imported}");
    }
}
