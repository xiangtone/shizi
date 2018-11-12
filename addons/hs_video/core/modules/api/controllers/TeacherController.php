<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 14:26
 */

namespace app\modules\api\controllers;


use app\models\User;
use app\modules\api\behaviors\LoginBehavior;
use app\models\Teacher;
use app\modules\api\models\TeacherForm;

class TeacherController extends Controller
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

    //    绑定手机号
    public function actionEdit()
    {
        $form = new TeacherForm();
        $form->attributes = \Yii::$app->request->post();
        $form->wechat_app = $this->wechat_app;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->store_id = $this->store->id;
        $this->renderJson($form->edit());
    }

    /**
     * 用户状态
     */
    public function actionIndex()
    {
        $user = User::findOne(['id' => \Yii::$app->user->identity->id, 'store_id' => $this->store->id]);
        if (!$user) {
            $this->renderJson([
                'code' => -2,
                'msg' => ''
            ]);
        }
        if (!$user->binding){
            $this->renderJson([
                'code' => -3,
                'msg' => '请先绑定手机号码'
            ]);
        }
        $teacher = Teacher::findOne(['id' => \Yii::$app->user->identity->id]);
        if (!$teacher){
            $this->renderJson([
                'code' => -4,
                'msg' => '请填写资料'
            ]);
        }
        $due_time = date('Y-m-d', $user->due_time);
        $this->renderJson([
            'code' => 0,
            'msg' => '',
            // 'data' => [
            //     'user_info' => (object)[
            //         'access_token' => $user->access_token,
            //         'nickname' => $user->nickname,
            //         'avatar_url' => $user->avatar_url,
            //         'id' => $user->id,
            //         'is_comment' => $user->is_comment,
            //         'is_clerk' => $user->is_clerk,
            //         'is_member' => $user->is_member,
            //         'due_time' => $user->due_time ? $due_time : 0,
            //         'binding' => $user->binding,
            //         'teacher_id' => $user->teacher_id,
            //         'channel_id' => $user->channel_id,
            //         'is_teacher' => $user->is_teacher,
            //     ]
            // ]
        ]);
    }

    

}