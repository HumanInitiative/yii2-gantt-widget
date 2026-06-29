<?php //src/api/BaseController.php
namespace pkpudev\gantt\api;

use Yii;
use yii\web\Response;

/**
 * Create Rest Api in Yii2 from scratch
 * 
 * See: https://www.yiiframework.com/wiki/748/building-a-rest-api-in-yii2-0
 */
trait RestTrait
{
    protected function validateWbsModel()
    {
        $WbsExists = class_exists('\app\models\ProjectWbs');
        $WbsProgressExists = class_exists('\app\models\ProjectWbsProgress');

        if (!$WbsExists || !$WbsProgressExists) {
            throw new \Exception("No WBS Model", 123);
        }
    }

    protected function setHeader($statusCode)
    {
        $statusMessage = $this->getStatusCodeMessage($statusCode);
        $contentType = 'application/json; charset=utf-8';

        if (Yii::$app->has('response') && Yii::$app->response instanceof Response) {
            $response = Yii::$app->response;
            $response->statusCode = $statusCode;
            $response->format = Response::FORMAT_RAW;
            $response->headers->set('Content-Type', $contentType);
            $response->headers->set('X-Powered-By', 'IT Revo <it.revo@pkpu.org>');
            return;
        }

        $statusHeader = "HTTP/1.1 {$statusCode} {$statusMessage}";
        header($statusHeader);
        header("Content-type: {$contentType}");
        header("X-Powered-By: IT Revo <it.revo@pkpu.org>");
    }

    protected function getStatusCodeMessage($statusCode)
    {
        $codes = [
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        ];
        return $codes[$statusCode] ?? '';
    }
}