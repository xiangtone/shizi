<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/12
 * Time: 9:46
 */

namespace app\modules\admin\controllers;


use app\models\School;
use app\modules\admin\models\SchoolForm;
use app\modules\admin\models\SchoolAddBatchForm;

class SchoolController extends Controller
{
    public function actionIndex()
    {
        $form = new SchoolForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        return $this->render('index',[
            'list'=>$arr['list'],
            'pagination'=>$arr['pagination'],
            'row_count'=>$arr['row_count'],
        ]);
    }

    public function actionAddBatch(){
        if (\Yii::$app->request->isPost) {
            $form = new SchoolAddBatchForm();
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post('model');
            $this->renderJson($form->save());
        }
        return $this->render('add-batch', [
            
        ]);
    }

    public function actionEdit($id = null)
    {
        $item = School::findOne(['id' => $id, ]);
        if (!$item)
            $item = new School();
        if (\Yii::$app->request->isPost) {
            $form = new SchoolForm();
            $form->school = $item;
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();
            $this->renderJson($form->save());
        }
        return $this->render('edit', [
            'school' => $item
        ]);
    }

}