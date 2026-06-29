<?php //src/api/ApiIndex.php
namespace pkpudev\gantt\api;

use pkpudev\gantt\composer\TaskComposer;
use Yii;

class ApiIndex
{
    use RestTrait;

    public function run()
    {
        $request = Yii::$app->request;

        $this->setHeader(200);

        $pid = $request->getQueryParam('pid');
        if ($pid !== null && $pid !== '') {
            $composer = new TaskComposer($pid);
            $json['data'] = $composer->compose();
            $json['link'] = [];
        } else {
            $json = ['data'=>[], 'link'=>[]];
        }

        echo json_encode($json, JSON_PRETTY_PRINT);
    }
}