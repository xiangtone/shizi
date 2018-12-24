<?php
/**
 * 游戏完成需要的操作
 * 
 * http://127.0.0.1/ll/shizi/addons/sc/core/web/index.php?store_id=1&r=api/excomplete&video_id=9&user_id=16&ex_type=1
 * https://www.dushujielong.com/ll/shizi/addons/sc/core/web/index.php?store_id=1&r=api/excomplete/index&video_id=9&user_id=16&ex_type=1
 */

namespace app\modules\api\controllers;
use app\models\UserEx;
use app\models\ClassUser;
use app\models\Classes;

class ExcompleteController extends Controller
{
    public function actionIndex()
    {
        //从http的post 和 get中获取参数
        $http_para = \Yii::$app->request->get();//取一下http参数
        $video_id = $http_para["video_id"];
        $user_id  = $http_para["user_id"];
        $ex_type  = $http_para["ex_type"];
        //var_dump($http_para);
        

        
        $model = new UserEx();
        //1 对 zjhj_video_user_ex 根据video_id 和 user_id 查找记录，不存在插入，存在则更新
        $query = $model->find()->where(['user_id' => $user_id,'video_id'=>$video_id])->one();
        //var_dump($query);var_dump($query->video_id);
        if (!$query) {//没有找到记录就插入
            $model->video_id = $video_id;
            $model->user_id = $user_id;
            $model->ex_type = $ex_type; 
            $model->ex_count = 1;
            $model->create_time = time();
            $model->save();
        }else{
            $query->ex_count = $query->ex_count+1;
            $query->modify_time = time();
            $query->save();
        }
        
        //2 zjhj_video_class_user 根据user_id查找 是否加入过班级
        //$sql = "UPDATE zjhj_video_class_user SET ex_count=ex_count+1 WHERE user_id='user_id'";
        //UPDATE zjhj_video_classes SET ex_count=ex_count+1 WHERE id IN (SELECT class_id FROM zjhj_video_class_user WHERE user_id=14 )
        $sql = "UPDATE zjhj_video_class_user SET ex_count=ex_count+1 WHERE user_id='$user_id'";
        $res = \Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE zjhj_video_classes SET ex_count=ex_count+1 WHERE id IN (SELECT class_id FROM zjhj_video_class_user WHERE user_id='$user_id')";
        $res = \Yii::$app->db->createCommand($sql)->execute();
        //设置可以跨域访问
        header("Access-Control-Allow-Origin: *");
        //var_dump($res);
        echo "sucess";
        
    }
}