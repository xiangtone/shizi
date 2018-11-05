<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 16:56
 */

namespace app\modules\api\controllers;


use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\MemberList;

class MemberController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
                'ignore'=>[]
            ],
        ]);
    }
    public function actionIndex()
    {
        $form = new MemberList();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }
}