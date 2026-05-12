<?php

namespace App\Services;

use App\Models\Track;
use App\Models\TrackTemplate;
use App\Models\Task;
use App\Models\TaskTemplate;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TemplateService
{
    /**
     * Создать трек из шаблона
     */
    public function createTrackFromTemplate(int $trackTemplateId, int $projectId, ?int $websiteId = null, array $overrideData = []): Track
    {
        $template = TrackTemplate::with('taskTemplates')->findOrFail($trackTemplateId);

        return DB::transaction(function () use ($template, $projectId, $websiteId, $overrideData) {
            // Создаём трек
            $track = Track::create(array_merge([
                'title' => $template->title,
                'description' => $template->description,
                'project_id' => $projectId,
                'website_id' => $websiteId,
                'business_process_id' => $template->business_process_id,
                'track_template_id' => $template->id,
                'sort_order' => 0,
                'is_active' => true,
            ], $overrideData));

            // Создаём задачи из шаблонов
            foreach ($template->taskTemplates as $taskTemplate) {
                $this->createTaskFromTemplate($taskTemplate->id, $track->id);
            }

            return $track;
        });
    }

    /**
     * Создать задачу из шаблона
     */
    public function createTaskFromTemplate(int $taskTemplateId, int $trackId, array $overrideData = []): Task
    {
        $template = TaskTemplate::findOrFail($taskTemplateId);

        return DB::transaction(function () use ($template, $trackId, $overrideData) {
            // Копируем файлы
            $copiedFiles = [];
            if ($template->files) {
                foreach ($template->files as $file) {
                    $copiedFiles[] = $this->copyTemplateFile($file, $trackId);
                }
            }

            // Создаём задачу
            return Task::create(array_merge([
                'title' => $template->title,
                'description' => $template->description,
                'track_id' => $trackId,
                'task_template_id' => $template->id,
                'checklist' => $template->checklist,
                'structure' => $template->structure,
                'files' => $copiedFiles,
                'priority' => $template->default_priority,
                'deadline' => $template->default_deadline_days ? now()->addDays($template->default_deadline_days) : null,
                'status_id' => 1, // начальный статус
                'created_by_id' => auth()->id(),
            ], $overrideData));
        });
    }

    /**
     * Копировать файл из шаблона в задачу
     */
    protected function copyTemplateFile(array $file, int $taskId): array
    {
        $oldPath = $file['path'];
        $newPath = str_replace('templates/task', 'tasks/' . $taskId, $oldPath);

        Storage::disk('private')->copy($oldPath, $newPath);

        $attachment = Attachment::create([
            'filename' => $file['filename'],
            'path' => $newPath,
            'size' => $file['size'],
            'mime_type' => $file['mime_type'],
            'entity_type' => 'task',
            'entity_id' => $taskId,
            'is_template' => false,
            'uploaded_by_id' => auth()->id(),
        ]);

        return [
            'id' => $attachment->id,
            'filename' => $attachment->filename,
            'url' => Storage::disk('private')->url($newPath),
        ];
    }
}
