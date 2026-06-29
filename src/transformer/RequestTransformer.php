<?php //src/transformer/RequestTransformer.php
namespace pkpudev\gantt\transformer;

use app\models\ProjectWbs;

class RequestTransformer
{
    protected $projectId;
    protected $taskId;
    protected $data;

    public function __construct(int $projectId, array $data)
    {
        $this->projectId = $projectId;
        $this->data = $data;
    }

    public function getNewModel(): ProjectWbs
    {
        $model = $this->mapping(new ProjectWbs);
        $model->duration_unit = 'day';
        return $model;
    }

    public function getExistingModel(int $id): ProjectWbs
    {
        return $this->mapping(ProjectWbs::findOne($id));
    }

    protected function mapping(ProjectWbs $model): ProjectWbs
    {
        $parent = (int)($this->data['parent'] ?? 0);
        $text = isset($this->data['text']) ? (string) $this->data['text'] : '';
        $duration = (int)($this->data['duration'] ?? 0);
        $startDate = isset($this->data['start_date']) ? (string) $this->data['start_date'] : null;
        $endDate = isset($this->data['end_date']) ? (string) $this->data['end_date'] : null;

        $model->parent_id   = $parent;
        $model->project_id  = $this->projectId;
        $model->task_name   = $text;
        $model->duration    = $duration;
        $model->start       = $startDate !== null ? date('Y-m-d', strtotime($startDate)) : null;
        $model->finish      = $endDate !== null ? date('Y-m-d', strtotime($endDate)) : null;

        return $model;
    }
}