<?php
namespace app\modules\school\controllers;

use yii\web\Controller;
use app\modules\school\models\LoginForm;
use app\modules\school\models\VideoListForm;
use app\models\Cat;

/**
 * Site controller
 */
class SiteController extends AdminController
{
    //首页
    public function actionIndex()
    {
        $list = Cat::find()->where(['is_delete' => 0, 'is_display' => 1])
            ->orderBy(['sort' => SORT_ASC])->asArray()->all();
        // return $this->render('cat', [
        //     'list' => $list
        // ]);
        return $this->render('index', ['userName' => $this->userName,'list' => $list]);
    }

    public function actionVideo()
    {
        $form = new VideoListForm();
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->getList();
        return $this->render('video', [
            'list' => $arr['list'],

        ]);

        // return $this->render('cat', [
        //     'list' => $list
        // ]);
        // return $this->render('index', ['userName' => $this->userName,'list' => $list]);
    }

    public function actionMain()
    {
        //echo 'main';
    }

    /**
     * 注销
     */
    public function actionLogout()
    {
        LoginForm::lagout();
        return $this->redirect(['login/index']);
    }

}
