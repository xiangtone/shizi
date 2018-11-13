<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 14:07
 */

namespace app\modules\admin\models;


use luweiss\wechat\Wechat;

class Model extends \app\models\Model
{
    /**
     * @return Wechat
     */
    public function getWechat()
    {
        return empty(\Yii::$app->controller->wechat) ? null : \Yii::$app->controller->wechat;
    }
}