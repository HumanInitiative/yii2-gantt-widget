<?php //src/model/ProgressUpdater.php
namespace pkpudev\gantt\model;

use Yii;
use app\models\ProjectWbsProgress;
use yii\db\ActiveRecordInterface;

class ProgressUpdater
{
    protected $wbsId;
    protected $progress;

    public function __construct(ActiveRecordInterface $model, ?float $progress = null)
    {
        $this->wbsId = (int)$model->id;
        $this->progress = $progress;
    }

    public function execute(): bool
    {
        if ($this->progress === null || $this->progress <= 0) return false;

        $model = ProjectWbsProgress::find()
            ->where(['wbs_id'=>$this->wbsId])
            ->one();
        if (is_null($model)) {
            $model = new ProjectWbsProgress;
            $model->wbs_id = $this->wbsId;
        }
        $model->progress = $this->progress;
        $model->insert_stamp = date('Y-m-d H:i:s');
        if ($user = Yii::$app->user) {
            $model->insert_by = $user->id;
        }
        return (bool)$model->save();
    }
}