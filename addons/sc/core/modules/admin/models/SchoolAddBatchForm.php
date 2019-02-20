<?php

namespace app\modules\admin\models;

use app\models\School;

class SchoolAddBatchForm extends Model
{
    public $store_id;

    public $keyword;
    public $limit;
    public $page;
    public $keyword_1;
    public $user_id;
    public $due_time;
    public $username;
    public $password;
    public $product_id;
    public $product_type;
    public $prefix;
    public $start_num;
    public $end_num;
    public $expire_date;
    // public $model;

    public function rules()
    {
        return [
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1],
            [['product_type'], 'default', 'value' => 'cat'],
            [['keyword', 'keyword_1'], 'trim'],
            [['prefix'], 'string'],
            [['expire_date'], 'date','format'=>'yyyy-mm-dd'],
            [['start_num', 'end_num'], 'integer'],
            [['prefix', 'start_num', 'end_num'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

        $arr = [];
        for ($i = $this->start_num; $i <= $this->end_num; $i++) {
            $password = rand(100000, 999999);
            $arr[] = [
                'username' => $this->prefix.$i,
                'password' => md5($password),
                'init_password' => $password,
                'level' => 'admin',
                'expire_date' => $this->expire_date,
            ];
        }
        if (\Yii::$app->db->createCommand()->batchInsert(School::tableName(), ['username', 'password', 'init_password', 'level','expire_date'], $arr)->execute()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '批量插入学校用户失败',
            ];
        }

        return [
            'code' => 0,
            'msg' => '成功',
        ];

        // if ($this->school->save()) {
        //     return [
        //         'code' => 0,
        //         'msg' => '成功',
        //     ];
        // } else {
        //     return $this->getModelError($this->school);
        // }
    }

}
