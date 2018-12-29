<?php

namespace app\modules\admin\models;

use app\models\School;
use yii\data\Pagination;

class SchoolForm extends Model
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
    public $school;

    public function rules()
    {
        return [
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1],
            [['keyword', 'keyword_1'], 'trim'],
            [['keyword', 'keyword_1','username','password'], 'string'],
            [['user_id', 'due_time',], 'integer'],
        ];
    }

    public function search()
    {
        if (!$this->validate())
            return $this->getModelError();

        $query = School::find()->where([
            
        ]);

        // $query->andWhere(['like', 'u.nickname', $this->keyword]);

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->orderBy(['id' => SORT_DESC])->limit($p->limit)->offset($p->offset)->asArray()->all();
        
        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

        $this->school->username = $this->username;
        $this->school->password = md5($this->password);
        $this->school->level = 'admin';

        if ($this->school->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return $this->getModelError($this->school);
        }
    }

}