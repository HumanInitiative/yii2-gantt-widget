<?php //src/model/WbsProgress.php
namespace pkpudev\gantt\model;

use app\models\ProjectWbsProgress;

class WbsProgress
{
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function getValue(): float
    {
        $row = ProjectWbsProgress::find()
            ->select('progress')
            ->where(['wbs_id'=>$this->task->id])
            ->orderBy('insert_stamp DESC')
            ->asArray()
            ->one();
        if ($row) {
            $number = (float)($row['progress'] ?? 0);
            return (float) number_format($number, 2, '.', '');
        }
        return 0.0;
    }

    public static function get(Task $task): float
    {
        $static = new static($task);
        return $static->getValue();
    }
}