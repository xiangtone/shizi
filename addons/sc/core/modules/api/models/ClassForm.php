<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/26
 * Time: 11:34
 */

namespace app\modules\api\models;

use app\models\Classes;
use app\models\User;
use app\models\ClassUser;

class ClassForm extends Model
{
    public $store_id;
    public $store;
    public $wechat_app;
    public $user_id;

    public $upload_img;
    public $class_name;
    public $class_id;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['class_id'], 'integer'],
            [['upload_img', 'class_name'], 'string'],
            //[['class_name', 'upload_img'], 'validateCheck'],
        ];
    }

    // public function validateCheck($attr, $param)
    // {
    //     $upload_img = json_decode($this->upload_img, true);
    //     if (!$this->class_name && $this->class_name != 0 && empty($upload_img)) {
    //         $this->addError("content", "请输入内容或上传一张图片");
    //     }
    // }

    public function edit()
    {
        $max_create_count = 3;
        $user = User::find()->where(['store_id' => $this->store_id, 'id' => $this->user_id])->one();
        if (!$user) {
            return [
                'code' => 2,
                'msg' => "未找到用户",
            ];
        }
        
        if ($this->class_id) {
            return [
                'code' => 7,
                'msg' => $this->class_id."7已经有重名班级了",
            ];
        } else {
            $count = Classes::find()->where(['create_user_id' => $this->user_id, 'is_delete' => 0])->count();
            if ($count < $max_create_count) {
                if ($this->checkClassName()) {
                    $class = new Classes();
                    $class->class_name = $this->class_name;
                    $class->type = 1;
                    $class->create_user_id = $this->user_id;
                    $class->create_time = time();
                    preg_match_all('/\"(.*?)\"/',$this->upload_img,$aharr);
                    $class->img_url =str_replace("\"","",$aharr[0][0]); 
                    if($class->save()){
                        $classUser = new ClassUser();
                        $classUser->user_id = $this->user_id;
                        $classUser->class_id = $class->id;
                        $classUser->role = 1;
                        $classUser->create_time = time();
                        if ($classUser->save()){
                            return [
                                'code' => 0,
                            ];
                        }else{
                            return [
                                'code' => 6,
                                'msg' => "班级保存名单失败，请重试",
                            ];
                        }
                    }else{
                        return [
                            'code' => 5,
                            'msg' => "班级保存失败，请重试",
                        ];
                    } 
                } else {
                    return [
                        'code' => 4,
                        'msg' => "已经有重名班级了，请修改名称",
                    ];
                }
            } else {
                return [
                    'code' => 3,
                    'msg' => "最多只能创建" . $max_create_count . "班级",
                ];
            }
        }
        return [
            'code' => 0,
        ];
    }
    private function checkClassName()
    {
        $class = Classes::find()->where(['class_name' => $this->class_name, 'is_delete' => 0])->one();
        if ($class) {
            return false;
        } else {
            return true;
        }
    }

}
