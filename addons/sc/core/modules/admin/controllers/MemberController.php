<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 13:51
 */

namespace app\modules\admin\controllers;


use app\models\Level;
use app\models\Option;
use app\modules\admin\models\LevelForm;
use app\modules\admin\models\LevelListForm;

class MemberController extends Controller
{
    public function actionIndex()
    {
        $form = new LevelListForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        return $this->render('index',$arr);
    }
    public function actionEdit($id = null)
    {
        $level = Level::findOne(['id'=>$id,'is_delete'=>0,'store_id'=>$this->store->id]);
        if(!$level){
            $level = new Level();
        }
        if(\Yii::$app->request->isPost){
            $form = new LevelForm();
            $form->store_id = $this->store->id;
            $form->level = $level;
            $post = \Yii::$app->request->post();
            $form->attributes = $post;
            if($post['scene'] == 'edit'){
                $form->scenario = $post['scene'];
                $this->renderJson($form->save());
            }
            if($post['scene'] == 'content'){
                $this->renderJson($form->saveContent());
            }
        }else{
            return $this->render('edit',[
                'level'=>$level,
                'content'=>Option::get('content',$this->store->id,'level','')
            ]);
        }
    }

    public function actionDel($id)
    {
        $level = Level::findOne(['id'=>$id,'store_id'=>$this->store->id]);
        if(!$level){
            $this->renderJson([
                'code'=>1,
                'msg'=>'不存在，请刷新重试'
            ]);
        }
        if($level->is_delete == 1){
            $this->renderJson([
                'code'=>1,
                'msg'=>'已删除，请刷新重试'
            ]);
        }
        $level->is_delete = 1;
        if($level->save()){
            $this->renderJson([
                'code'=>0,
                'msg'=>'成功'
            ]);
        }else{
            $this->renderJson([
                'code'=>1,
                'msg'=>'请刷新重试'
            ]);
        }
    }

    public function actionUpDown($id = 0,$type = 'down')
    {
        $level = Level::findOne(['store_id'=>$this->store->id,'is_delete'=>0,'id'=>$id]);
        if(!$level){
            $this->renderJson([
                'code'=>1,
                'msg'=>'不存在或者以删除'
            ]);
        }
        if($type == 'down'){
            $level->is_groom = 0;
        }elseif($type == 'up'){
            $level->is_groom = 1;
        }else{
            $this->renderJson([
                'code'=>1,
                'msg'=>'参数错误'
            ]);
        }
        if($level->save()){
            $this->renderJson([
                'code'=>0,
                'msg'=>'成功'
            ]);
        }else{
            $this->renderJson([
                'code'=>1,
                'msg'=>'请刷新重试'
            ]);
        }
    }
}