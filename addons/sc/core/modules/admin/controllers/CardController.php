<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/12
 * Time: 9:46
 */

namespace app\modules\admin\controllers;


use app\models\School;
use app\models\CardType;
use app\models\Cat;
use app\modules\admin\models\SchoolForm;
use app\modules\admin\models\CardTypeForm;

class CardController extends Controller
{

    public function actionTypeList()
    {
        $form = new CardTypeForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        // var_dump($arr);
        // die();
        return $this->render('type-list',[
            'list'=>$arr['list'],
            'pagination'=>$arr['pagination'],
            'row_count'=>$arr['row_count'],
        ]);
    }

    public function actionTypeEdit($id = null)
    {
        $item = CardType::findOne(['id' => $id, ]);
        if (!$item)
            $item = new CardType();
        if (\Yii::$app->request->isPost) {
            $form = new CardTypeForm();
            $model = \Yii::$app->request->post('model');
            $form->attributes = $model;
            // $form->model = $item;
            $form->store_id = $this->store->id;
            // $form->attributes = \Yii::$app->request->post();
            $this->renderJson($form->save());
        }
        $cat = Cat::find()->where(['is_delete' => 0, 'store_id' => $this->store->id])->orderBy(['sort' => SORT_ASC])->asArray()->all();
        return $this->render('type-edit', [
            'card_type' => $item,
            'cat_list' => $cat,
        ]);
    }

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