<?php

namespace app\modules\admin\models;

use app\models\CardType;
use app\models\Card;
use app\models\Cat;
use yii\data\Pagination;

class CardTypeForm extends Model
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
    // public $model;

    public function rules()
    {
        return [
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1],
            [['product_type'], 'default', 'value' => 'cat'],
            [['keyword', 'keyword_1'], 'trim'],
            [['prefix'], 'string'],
            [['start_num', 'end_num'], 'integer'],
            [['prefix', 'start_num', 'end_num', 'product_id'], 'required'],
        ];
    }

    public function search()
    {
        // if (!$this->validate())
        //     return $this->getModelError();

        $query = CardType::find()->alias('ct')->where([

        ])->leftJoin(Cat::tableName() . ' c', 'c.id=ct.product_id');

        // $query->andWhere(['like', 'u.nickname', $this->keyword]);

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->select([
            'ct.*', 'c.name',
        ])->orderBy(['id' => SORT_DESC])->limit($p->limit)->offset($p->offset)->asArray()->all();

        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count,
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        
        if (Card::findOne($this->prefix.$this->start_num)||Card::findOne($this->prefix.$this->end_num)){
            return [
                'code' => 1,
                'msg' => '卡号已存在',
            ];
        }
        
        $cardType = new CardType;
        $cardType->product_id = $this->product_id;
        $cardType->product_type = $this->product_type;
        $cardType->prefix = $this->prefix;
        $cardType->start_num = $this->start_num;
        $cardType->end_num = $this->end_num;
        if ($cardType->save()) {
            $arr = [];
            for ($i = $this->start_num; $i <= $this->end_num; $i++) {
                $arr[] = [
                    'id' => "" . $this->prefix . $i,
                    'password' => rand(100000, 999999),
                    'card_type_id' => $cardType->id,
                ];
            }
            if (\Yii::$app->db->createCommand()->batchInsert(Card::tableName(), ['id', 'password', 'card_type_id'], $arr)->execute()) {
                return [
                    'code' => 0,
                    'msg' => '成功',
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '批量插入card失败',
                ];
            }
        } else {
            return [
                'code' => 1,
                'msg' => '保存cardtype失败',
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
