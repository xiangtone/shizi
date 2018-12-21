<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/28
 * Time: 23:13
 */
namespace backeapp\modules\school\controllers;

use Yii;
use common\models\User;
use common\models\UserEdit;
use yii\data\Pagination;

class UserController extends AdminController
{
    /*
     * 超级账号管理
     */
    public function actionIndex()
    {
        //var_dump("actionIndex");

        $model = User::find()->where([ 'level' =>['admin','kefu','manager']]);
        $pagination = new Pagination(['totalCount' => $model->count() , 'pageSize' => 10]);
        $result = $model->offset($pagination->offset)->limit($pagination->limit)->all();
        $m =  User::findOne($this->userId);
        $m->password = '';
        $m->level = '';
        $m->username = '';
        return $this->render('index' ,['result' => $result , 'pagination' => $pagination,'model' => $m]);
        //$this->userId
    }
    /*
     * 个人资料
     */
    public function actionInfo()
    {
        switch($this->level){
            case "admin":
                $level = '超级管理员';
                break;
            case "kefu":
                $level = '客服';
                break;
            default:
                $level = '其他';
                break;
        }
         //var_dump($level);
         return $this->render('info',['userName' => $this->userName , 'level' => $level]);
    }
    /*
     * 添加用户
     */
    public function actionAdd()
    {
        $model = new User();
        //var_dump($model->load(Yii::$app->request->post()));
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) /*&& $model->save()*/){
            if($model->validate()){
                //用户名和密码被占用判断
                if(User::find()->where(['username' => $model->username])->andWhere(['!=' , 'id' , $this->id])->count() > 0){
                   // $this->addError($attribute , '用户名已经被占用');
                    Yii::$app->session->setFlash('error' , '添加的账号已经被占用');
                    return $this->redirect(['index']);
                }
                //MD5
                $md5pwd = md5($model->password);
                $model->password = $md5pwd;
                if($model->save()){;
                    Yii::$app->session->setFlash('success' , '添加账号成功');
                }
            }
            else{
                Yii::$app->session->setFlash('error' ,"请填写账号和密码");
            }
            //Yii::$app->session->setFlash('success' , '添加用户成功');
            //return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('success' , '添加账号失败');
        }

       return $this->redirect(['index']);
        //return $this->render('index' , ['model' => $model]);

    }
    /*
     * 修改密码
     */
    public function actionEdit()
    {

        //$model = User::findOne($this->userId);
        $model = UserEdit::findOne($this->userId);
        //var_dump($model);
        if(!$model)
        {
            //var_dump("取得数据");
            return $this->render('edit' , ['model' => $model]);
        }
        else{
            $current_pwd =  $model->password;
        }
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) /*&& $model->save()*/){
           if( $model->validate()){
               if($current_pwd !=  md5(Yii::$app->request->post()['UserEdit']['password']))
               {
                   Yii::$app->session->setFlash('error' , '原始密码输入错误');
               }
               else{
                   if($model->save()){
                       $model->password = '';
                       $model->newPassword = '';
                       $model->confirmPassword = '';
                       Yii::$app->session->setFlash('success' , '修改密码成功');
                   }

               }
           }
            return $this->render('edit' , ['model' => $model]);
        }
        $model->password = '';
        return $this->render('edit' , ['model' => $model]);
    }

    /*
     * 删除用户
     */
    public function actionDelete()
    {
        //var_dump("actionDelete");
        $selected = Yii::$app->request->post('selected' , []);
        if(User::deleteIn($selected,$this->userId)){
            Yii::$app->session->setFlash('success' , '删除用户成功');
        }else{
            Yii::$app->session->setFlash('error' , '删除用户失败');
        }
        return $this->redirect(['index']);
    }
    /*
     *
     * 禁用账号
     * 根据id
     */
    public function actionDisableAccountOne()
    {
        //var_dump("actionDisableAccountOne");
        //var_dump(Yii::$app->request->post());
        if(Yii::$app->request->isPost){
            $username = Yii::$app->request->post()['User']['username'];
            if(empty($username)){
                Yii::$app->session->setFlash('error' , '禁用账号不能为空');
                return $this->redirect(['disable']);
            }
            else{
                if(User::find()->where(['username' => $username ])->count() ==0){
                    Yii::$app->session->setFlash('error' , '禁用账号不存在');
                    return $this->redirect(['disable']);
                }
                if(User::find()->where(['username' => $username,'status'=>'disable'])->count()>0){
                    Yii::$app->session->setFlash('error' , '该账号已经被禁用');
                    return $this->redirect(['disable']);
                }
                if(User::updateAll(['status'=>'disable'],['username'=>$username])){
                    Yii::$app->session->setFlash('success' , '禁用成功');
                }
                else{
                    Yii::$app->session->setFlash('error' , '禁用失败');
                }

            }
        }
        return $this->redirect(['disable']);

    }
    /*
     * 启用账户-选择一个或者多个
     */
    public function actionEnableAccount()
    {
        //var_dump("actionEnableAccount");
        $selected = Yii::$app->request->post('selected' , []);
        //var_dump($selected);
        foreach($selected as $select){
            $data[] = (int)$select;
        }
       // User::updateAll(['status'=>'enable'],['id'=>$data]);
        //return $this->redirect(['index']);
        if(User::updateIn($selected,'enable')){
            Yii::$app->session->setFlash('success' , '启用成功');
        }else{
            Yii::$app->session->setFlash('error' , '启用失败');
        }
        return $this->redirect(['disable']);
    }
    /*
     * 账号启用管理
     */
    public function actionEnable()
    {
        //var_dump("actionEnable");
        //$model = User::find()->where(['or', 'status' => null, 'status'=>'enable']);
        $model = User::find()->where("status IS NULL  OR status='enable'");
        //$model = User::findBySql("SELECT * FROM user WHERE STATUS IS NULL OR STATUS='enable'");
        //var_dump($model->count());
        $pagination = new Pagination(['totalCount' => $model->count() , 'pageSize' => 10]);
        $result = $model->offset($pagination->offset)->limit($pagination->limit)->all();
        $m =  User::findOne($this->userId);
        $m->password = '';
        $m->level = '';
        $m->username = '';
        return $this->render('enable' ,['result' => $result , 'pagination' => $pagination,'model' => $m]);
    }
    /*
     * 启用账号
     * 根据id
     */
    public function actionEnableAccountOne()
    {
        //var_dump("actionDisableAccountOne");
        //var_dump(Yii::$app->request->post());
        //die();
        if(Yii::$app->request->isPost){
            $username = Yii::$app->request->post()['User']['username'];
            if(empty($username)){
                Yii::$app->session->setFlash('error' , '启用账号不能为空');
                return $this->redirect(['enable']);
            }
            else{
                if(User::find()->where(['username' => $username ])->count() ==0){
                    Yii::$app->session->setFlash('error' , '启用账号不存在');
                    return $this->redirect(['enable']);
                }
                if(User::find()->where(['username' => $username,'status'=>'enable'])->count()>0){
                    Yii::$app->session->setFlash('error' , '该账号已经启用');
                    return $this->redirect(['enable']);
                }
                if(User::updateAll(['status'=>'disable'],['username'=>$username])){
                    Yii::$app->session->setFlash('success' , '启用成功');
                }
                else{
                    Yii::$app->session->setFlash('error' , '启用失败');
                }

            }
        }
        return $this->redirect(['enable']);

    }
    /*
    * 禁用账户-选择一个或者多个
    */
    public function actionDisableAccount()
    {
        //var_dump("actionDisableAccount");
        $selected = Yii::$app->request->post('selected' , []);
        //var_dump($selected);
        foreach($selected as $select){
            $data[] = (int)$select;
        }
        if(User::updateIn($selected,'disable')){
            Yii::$app->session->setFlash('success' , '禁用成功');
        }else{
            Yii::$app->session->setFlash('error' , '禁用失败');
        }
        return $this->redirect(['enable']);
    }
    /*
     * 账号禁用管理
     */
    public function actionDisable()
    {
        //var_dump("actionDisable");
        $model = User::find()->where([ 'status' =>['disable']]);
        $pagination = new Pagination(['totalCount' => $model->count() , 'pageSize' => 10]);
        $result = $model->offset($pagination->offset)->limit($pagination->limit)->all();
        $m =  User::findOne($this->userId);
        $m->password = '';
        $m->level = '';
        $m->username = '';
        return $this->render('disable' ,['result' => $result , 'pagination' => $pagination,'model' => $m]);

    }
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}