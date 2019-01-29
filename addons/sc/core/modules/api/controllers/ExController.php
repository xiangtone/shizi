<?php

namespace app\modules\api\controllers;

use app\modules\api\models\ExCharForm;
use app\modules\api\models\ExRedicalForm;
/**
 * Default controller for the `api` module
 */
class ExController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     * 首页列表
     */
    public function actionCharIndex()
    {
        $form = new ExCharForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }

    public function actionRedicalIndex()
    {
        $form = new ExRedicalForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }
}
