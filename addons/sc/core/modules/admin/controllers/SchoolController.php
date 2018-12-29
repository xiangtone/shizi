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

    /**
     * 审核、取消审核
     */
    public function actionStatus($id=null,$status=0)
    {
        $teacher = Teacher::findOne(['id'=>$id]);
        if(!$teacher){
            $this->renderJson([
                'code'=>1,
                'msg'=>'记录不存在'
            ]);
        }
        $teacher->status = $status;
        if($teacher->save()){
            $this->renderJson([
                'code'=>0,
                'msg'=>'成功'
            ]);
        }else{
            $this->renderJson([
                'code'=>1,
                'msg'=>'db更新失败'
            ]);
        }
    }
}