<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16
 * Time: 14:59
 */

namespace app\modules\admin\models;


use app\models\Order;
use app\models\User;
use app\models\UserForm;
use app\models\Video;
use yii\data\Pagination;

class OrderListForm extends Model
{
    public $store_id;

    public $status;
    public $page;
    public $limit;

    public $keyword;

    public function rules()
    {
        return [
            [['status', 'page', 'limit'], 'integer'],
            [['status'], 'in', 'range' => [-1, 1, 2, 3]],
            [['status'], 'default', 'value' => -1],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 10],
            [['keyword'], 'trim'],
            [['keyword'], 'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $query = Order::find()->alias('o')->where([
            'o.store_id' => $this->store_id, 'o.is_pay' => 1, 'o.is_delete' => 0,'o.type'=>0
        ])->leftJoin(User::tableName() . ' u', 'u.id=o.user_id')
            ->leftJoin(Video::tableName() . ' v', 'v.id=o.video_id');

        if($this->status){
            switch ($this->status){
                case 1:$query->andWhere(['o.is_use'=>0]);
                    break;
                case 2:$query->andWhere(['o.is_use'=>1]);
                    break;
                case 3:$query->andWhere(['o.is_use'=>2]);
                    break;
            }
        }

        if($this->keyword){
            $query->andWhere([
                'or',
                ['like','o.order_no',$this->keyword],
                ['like','u.nickname',$this->keyword]
            ]);
        }

        $count = $query->count();
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);

        $list = $query->select([
            'o.*','u.nickname','v.title','v.pic_url'
        ])->offset($p->offset)->limit($p->limit)->orderBy(['o.addtime'=>SORT_DESC])->asArray()->all();
        foreach($list as $index=>$value){
            $user_form = UserForm::find()->where(['order_id'=>$value['id'],'store_id'=>$this->store_id])->asArray()->all();
            $list[$index]['user_form'] = $user_form;
        }

        return [
            'list'=>$list,
            'pagination'=>$p,
            'row_count'=>$count
        ];
    }
}