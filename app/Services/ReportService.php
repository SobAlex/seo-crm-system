<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Report;
use App\Models\Comment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function generateReport(int $projectId, string $startDate, string $endDate, int $userId): Report
    {
        $project = Project::with('client')->findOrFail($projectId);

        $comments = $this->getCommentsForReport($projectId, $startDate, $endDate);

        $logoUrl = $this->getLogo($project);

        $html = view('reports.report', [
            'project' => $project,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'comments' => $comments,
            'logo' => $logoUrl,
            'generated_at' => now()->format('d.m.Y H:i'),
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $pdfPath = 'reports/' . $projectId . '_' . time() . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        return Report::create([
            'project_id' => $projectId,
            'title' => 'Отчёт за период ' . $startDate . ' — ' . $endDate,
            'period_start' => $startDate,
            'period_end' => $endDate,
            'content' => $comments,
            'pdf_path' => $pdfPath,
            'generated_by_id' => $userId,
            'generated_at' => now(),
        ]);
    }

    protected function getCommentsForReport(int $projectId, string $startDate, string $endDate): array
    {
        return DB::table('comments')
            ->join('tasks', 'comments.task_id', '=', 'tasks.id')
            ->join('tracks', 'tasks.track_id', '=', 'tracks.id')
            ->join('comment_comment_tag', 'comments.id', '=', 'comment_comment_tag.comment_id')
            ->join('comment_tags', 'comment_comment_tag.comment_tag_id', '=', 'comment_tags.id')
            ->where('tracks.project_id', $projectId)
            ->where('comment_tags.title', 'Для отчета')
            ->whereBetween('comments.created_at', [$startDate, $endDate])
            ->select(
                'comments.text',
                'comments.created_at',
                'tracks.title as track_title',
                'tasks.title as task_title'
            )
            ->orderBy('tracks.sort_order')
            ->orderBy('tasks.id')
            ->get()
            ->groupBy('track_title')
            ->map(function ($tasks) {
                return $tasks->groupBy('task_title')->map(function ($comments) {
                    return $comments->map(function ($comment) {
                        return [
                            'text' => $comment->text,
                            'date' => $comment->created_at,
                        ];
                    });
                });
            })
            ->toArray();
    }

    protected function getLogo(Project $project): ?string
    {
        if ($project->logo_attachment_id && $project->logo) {
            return Storage::disk('public')->url($project->logo->path);
        }

        if ($project->client->logo_attachment_id && $project->client->logo) {
            return Storage::disk('public')->url($project->client->logo->path);
        }

        return null;
    }
}
