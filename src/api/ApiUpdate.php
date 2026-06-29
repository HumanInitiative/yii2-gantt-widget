<?php //src/api/ApiIndex.php
namespace pkpudev\gantt\api;

use pkpudev\gantt\model\PicUpdater;
use pkpudev\gantt\model\ProgressUpdater;
use pkpudev\gantt\transformer\RequestTransformer;
use Yii;

class ApiUpdate
{
    use RestTrait;

    protected $projectId;
    protected $taskId;

    public function __construct(int $projectId, int $taskId)
    {
        $this->projectId = $projectId;
        $this->taskId = $taskId;
    }

    public function run()
    {
        $request = Yii::$app->request;

        $post = $request->post();
        $post = is_array($post) ? $post : [];
        $transformer = new RequestTransformer($this->projectId, $post);
        $model = $transformer->getExistingModel($this->taskId);

        $trx = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
                $picId = isset($post['pic_id']) ? (int) $post['pic_id'] : 0;
                $progress = $post['progress'] ?? null;

                (new PicUpdater($model, $picId))->execute();
                (new ProgressUpdater($model, $progress))->execute();
                $trx->commit();

                $this->setHeader(200);
                echo json_encode(['action'=>'updated'], JSON_PRETTY_PRINT);
            } else {
                $trx->rollBack();

                $this->setHeader(400);
                echo json_encode(['msg'=>'error'], JSON_PRETTY_PRINT);
            }
        } catch (\Throwable $th) {
            $trx->rollBack();
            throw $th;
        }
    }
}
