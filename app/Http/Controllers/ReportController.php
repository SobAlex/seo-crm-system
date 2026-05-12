<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Project;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $query = Report::with(['project', 'generatedBy']);

        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        return Inertia::render('Reports/Index', [
            'reports' => $query->latest()->paginate(20),
            'projects' => Project::with('client')->get(),
            'projectId' => $request->project_id,
        ]);
    }

    public function create()
    {
        return Inertia::render('Reports/Create', [
            'projects' => Project::with('client')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $report = $this->reportService->generateReport(
            $validated['project_id'],
            $validated['period_start'],
            $validated['period_end'],
            auth()->id()
        );

        return redirect()->route('reports.index', ['project_id' => $report->project_id])
            ->with('success', 'Отчёт сформирован');
    }

    public function show(Report $report)
    {
        return Inertia::render('Reports/Show', [
            'report' => $report->load('project.client', 'generatedBy'),
        ]);
    }

    public function edit(Report $report)
    {
        // Отчёты не редактируются
        abort(404);
    }

    public function update(Request $request, Report $report)
    {
        abort(404);
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return back()->with('success', 'Отчёт удалён');
    }

    // Скачивание PDF
    public function download(Report $report)
    {
        return response()->download(storage_path('app/public/' . $report->pdf_path));
    }

    // Отправка клиенту
    public function send(Report $report)
    {
        // Отправка email
        // Mail::to($report->project->client->email)->send(new ReportMail($report));

        $report->update(['sent_to_client_at' => now()]);

        return back()->with('success', 'Отчёт отправлен клиенту');
    }
}
