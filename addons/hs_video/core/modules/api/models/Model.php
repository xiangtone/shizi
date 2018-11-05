<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9
 * Time: 10:45
 */

namespace app\modules\api\models;


use luweiss\wechat\Wechat;

class Model extends \app\models\Model
{
    /**
     * @return Wechat
     */
    public function getWechat()
    {
        return isset(\Yii::$app->controller->wechat) ? \Yii::$app->controller->wechat : null;
    }
}