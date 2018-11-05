<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/14
 * Time: 9:32
 */

namespace app\controllers;


use app\behaviors\OrderBehavior;
use yii\web\Response;

class Controller extends \yii\web\Controller
{
    /**
     * 返回json格式数据，将执行 \Yii::$app->end() 操作
     * @param array|string $data 返回的数据，数组或json字符串
     * @return null
     */
    public function renderJson($data = [])
    {
        if (is_array($data)) {
            if (!isset($data['code']))
                $data['code'] = 0;
            if (!isset($data['msg']))
                $data['msg'] = '';
            if (!isset($data['data']))
                $data['data'] = (object)null;
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        if (is_object($data)) {
            if (!isset($data->code))
                $data->code = 0;
            if (!isset($data->msg))
                $data->msg = '';
            if (!isset($data->data))
                $data->data = (object)null;
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        echo $data;
        \Yii::$app->end();
        return null;
    }
    public function behaviors()
    {
        return [
            'order' => [
                'class' => OrderBehavior::className(),
            ],
        ];
    }

    public function getVersion()
    {
        $file = \Yii::$app->basePath . '/version.json';
        if (!file_exists($file))
            return null;
        $content = json_decode(file_get_contents($file), true);
        if (!$content)
            return null;
        if (isset($content['version']))
            return $content['version'];
        return null;
    }
}