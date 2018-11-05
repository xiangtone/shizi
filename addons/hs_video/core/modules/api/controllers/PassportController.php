<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/14
 * Time: 15:26
 */

namespace app\modules\api\controllers;


use app\modules\api\models\LoginForm;

class PassportController extends Controller
{
    public function actionLogin()
    {
        $form = new LoginForm();
        $form->attributes = \Yii::$app->request->post();
        $form->wechat_app = $this->wechat_app;
        $form->store_id = $this->store->id;
        return $this->renderJson($form->login());
    }
}