<?php

namespace app\modules\admin\models;


use app\models\Order;
use app\models\School;
use app\models\User;
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

    public function rules()
    {
        return [
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1],
            [['keyword', 'keyword_1'], 'trim'],
            [['keyword', 'keyword_1'], 'string'],
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

    public function clerk()
    {
        if (!$this->validate())
            return $this->getModelError();

        $query = User::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'type' => 1,
            'is_clerk' => 1
        ]);

        $query->andWhere(['like', 'nickname', $this->keyword]);

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->orderBy(['addtime' => SORT_DESC])->limit($p->limit)->offset($p->offset)->asArray()->all();
        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count
        ];
    }


    public function getUser()
    {
        $query = User::find()->where([
            'type' => 1,
            'store_id' => $this->store_id,
            'is_clerk' => 0,
            'is_delete' => 0
        ]);
        if ($this->keyword_1)
            $query->andWhere(['LIKE', 'nickname', $this->keyword_1]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('addtime DESC')->asArray()->all();
//        $list = $query->orderBy('addtime DESC')->asArray()->all();

        return $list;
    }

    public function addMember()
    {
        $user = User::findOne($this->user_id);
        if(!$user){
            return;
        }
        $user->is_member = 1;
        $user->due_time = strtotime($this->due_time);
        if($user->save()){
            return [
                'code' => 0,
            ];
        }
    }

}