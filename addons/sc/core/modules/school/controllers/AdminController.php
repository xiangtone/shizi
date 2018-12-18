<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/14
 * Time: 23:35
 */
namespace app\modules\school\controllers;
use Yii;

class AdminController extends CommonController
{
    public $layout = 'empty';

    public function beforeAction($action)
    {

        if (parent::beforeAction($action)) { //先让CommonController的beforeAction执行
            if(!$this->userId){
                return Yii::$app->response->redirect(['login/index']);
            }
            return true;
        }
        return false;
    }
}