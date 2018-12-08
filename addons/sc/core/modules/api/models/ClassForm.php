<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/26
 * Time: 11:34
 */

namespace app\modules\api\models;

use app\models\Classes;
use app\models\ClassUser;
use app\models\User;

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

    public function listClass()
    {
        if ($this->user_id){
            $queryMyClass = ClassUser::find()->alias('uc')->where(['user_id' => $this->user_id])->leftJoin(Classes::tableName() . 'c', 'c.id=uc.class_id');
            $my_class_count = $queryMyClass->count();
            $my_class_list = $queryMyClass->select([
                'uc.*', 'c.*',
            ])->asArray()->all();
        }
        $max_top_count = 3;
        $query_lesson_top = Classes::find()->where(['is_top'=>1]);
        $list_lesson_top = $query_lesson_top->orderBy(['lesson_count'=> SORT_DESC])->limit($max_top_count)->asArray()->all();

        $query_ex_top = Classes::find()->where(['is_top'=>1]);
        $list_ex_top = $query_ex_top->orderBy(['ex_count'=> SORT_DESC])->limit($max_top_count)->asArray()->all();
        
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'list_my_class' => $my_class_list,
                'list_lesson_top' => $list_lesson_top,
                'list_ex_top' => $list_ex_top,
                'my_class_count' => $count,
            ],
        ];
    }

    public function info()
    {
        $class = Classes::find()->where(['id' => $this->class_id])->asArray()->one();
        $query = ClassUser::find()->alias('uc')->where(['class_id' => $this->class_id])->leftJoin(User::tableName() . 'u', 'u.id=uc.user_id');
        $count = $query->count();
        $list = $query->select([
            'uc.*', 'u.*',
        ])->asArray()->all();
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'list' => $list,
                'class' => $class,
                'row_count' => $count,
            ],
        ];
    }

    public function join()
    {
        $class = Classes::find()->where(['id' => $this->class_id])->asArray()->one();
        $query = ClassUser::find()->alias('uc')->where(['class_id' => $this->class_id,'user_id' => $this->user_id]);
        $count = $query->count();
        if ($count>0){
            return [
                'code' => 2,
                'msg' => '已经加入班级，不能重复加入',
            ];  
        }else{
            $classUser = new ClassUser();
            $classUser->user_id = $this->user_id;
            $classUser->class_id = $this->class_id;
            $classUser->role = 0;
            $classUser->create_time = time();
            if ($classUser->save()) {
                return [
                    'code' => 0,
                    'class_id' => $class->id,
                ];
            } else {
                return [
                    'code' => 6,
                    'msg' => "班级保存名单失败，请重试",
                ];
            }
        }
    }

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
                'msg' => $this->class_id . "已经有重名班级了",
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
                    preg_match_all('/\"(.*?)\"/', $this->upload_img, $aharr);
                    $class->img_url = str_replace("\"", "", $aharr[0][0]);
                    if ($class->save()) {
                        $classUser = new ClassUser();
                        $classUser->user_id = $this->user_id;
                        $classUser->class_id = $class->id;
                        $classUser->role = 1;
                        $classUser->create_time = time();
                        if ($classUser->save()) {
                            return [
                                'code' => 0,
                                'class_id' => $class->id,
                            ];
                        } else {
                            return [
                                'code' => 6,
                                'msg' => "班级保存名单失败，请重试",
                            ];
                        }
                    } else {
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
