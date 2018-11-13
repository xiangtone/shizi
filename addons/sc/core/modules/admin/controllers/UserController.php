<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/12
 * Time: 9:46
 */

namespace app\modules\admin\controllers;


use app\models\User;
use app\modules\admin\models\UserForm;

class UserController extends Controller
{
    public function actionIndex()
    {
        $form = new UserForm();
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
     * 禁言、解除禁言
     */
    public function actionComment($id=null,$status=0)
    {
        $user = User::findOne(['id'=>$id,'store_id'=>$this->store->id]);
        if(!$user){
            $this->renderJson([
                'code'=>1,
                'msg'=>'网络异常'
            ]);
        }
        $user->is_comment = $status;
        if($user->save()){
            $this->renderJson([
                'code'=>0,
                'msg'=>'成功'
            ]);
        }else{
            $this->renderJson([
                'code'=>1,
                'msg'=>'网络异常'
            ]);
        }
    }

    /**
     * 批量禁言、解除
     */
    public function actionBatch()
    {
        $get = \Yii::$app->request->get();
        User::updateAll(['is_comment'=>$get['type']],['and',['store_id'=>$this->store->id],['in','id',$get['check']]]);
        $this->renderJson([
            'code'=>0,
            'msg'=>'禁言成功'
        ]);
    }
    /**
     * 核销员列表
     */
    public function actionClerk()
    {
        $form = new UserForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->clerk();
        $data_list = $form->getUser();
        return $this->render('clerk',[
            'list'=>$arr['list'],
            'pagination'=>$arr['pagination'],
            'row_count'=>$arr['row_count'],
            'user_list'=>json_encode($data_list,JSON_UNESCAPED_UNICODE)
        ]);
    }
    /**
     * 获取用户
     */
    public function actionGetUser()
    {
        $form = new UserForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->getUser();
        return json_encode($arr,JSON_UNESCAPED_UNICODE);
    }
    /**
     * @param null $id
     * @param int $status //0--解除核销员  1--设置核销员
     * @return null
     * 设置/取消核销员
     */
    public function actionClerkEdit($id=null,$status=0)
    {
        $user = User::findOne(['id'=>$id,'is_delete'=>0,'store_id'=>$this->store->id]);
        if(!$user){
            return $this->renderJson([
                'code'>1,
                'msg'=>'用户不存在'
            ]);
        }
        $user->is_clerk = $status;

        if ($user->save()) {
            $this->renderJson([
                'code' => 0,
                'msg' => '成功'
            ]);
        } else {
            $this->renderJson([
                'code' => 1,
                'msg' => '网络异常'
            ]);
        }
    }

    /**
     * 添加会员
     */
    public function actionAddMember()
    {
        $form = new UserForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        return $this->renderJson($form->addMember());
    }
}