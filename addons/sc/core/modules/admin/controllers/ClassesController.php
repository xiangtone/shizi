<?php
/**
 * ClassController
 * 班级管理
 */

namespace app\modules\admin\controllers;

use app\models\Classes;
use app\modules\admin\models\ClassesForm;
class ClassesController extends Controller
{

    public $keyword;
    /**
     * @return string
     * 展示班级信息
     */
    public function actionIndex()
    {
        $form = new ClassesForm();

        //$form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $form->limit = 10;
        $arr = $form->getList();

        //var_dump( $arr);die();

        return $this->render('index', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count']
        ]);

        //return $this->render('index');
    }

    public function actionListUser($id=null){
        $form = new ClassesForm();
        // $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        return $this->render('list-user',[
            'list'=>$arr['list'],
            'pagination'=>$arr['pagination'],
            'row_count'=>$arr['row_count'],
        ]);
    }

    /**
     * 班级编辑
     * @param null $id
     * @return string
     */
    public function actionEdit($id = null)
    {
        //根据id查询是否有记录,有记录就是修改,没有就是添加
        $classes = Classes::findOne([
            'id' => $id,
        ]);

        //var_dump($classes);die();
        if (!$classes) {
            $classes = new Classes();
        }
        //\Yii::$app->user->identity->id; // 当前登陆用户id
        if(\Yii::$app->request->isPost){

            $form = new ClassesForm();
            $form->classes = $classes;
            $form->attributes = \Yii::$app->request->post();
            $this->renderJson($form->save());


        }else{
            return $this->render('edit',[
                'classes'=>$classes,
            ]);
        }
    }
    /*
     * 删除班级记录
     */
    public function actionDel($id)
    {

        $form = new ClassesForm();

        if ($form->del($id)) {
            $this->renderJson([
                'code' => 0,
                'msg' => '刪除成功'
            ]);
        } else {
            $this->renderJson([
                'code' => 1,
                'msg' => '刪除失敗'
            ]);
        }
    }
}