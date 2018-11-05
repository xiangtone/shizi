<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/20
 * Time: 17:11
 */

namespace app\modules\admin\models;


use app\models\MemberOrder;
use app\models\Order;
use app\models\User;
use app\models\Video;
use yii\data\Pagination;

class BuyVideoForm extends Model
{
    public $store_id;
    public $page;
    public $limit;
    public $keyword;

    public function rules()
    {
        return [
            [['page', 'limit'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 24],
            [['keyword'], 'trim'],
            [['keyword'], 'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

//        $query = Video::find()->alias('v')->where(['v.store_id' => $this->store_id])
//            ->leftJoin(Order::tableName() . ' o', 'o.video_id=v.id and o.type = 1')
//            ->leftJoin(User::tableName() . ' u', 'u.id=o.user_id')
//            ->andWhere(['o.is_pay' => 1, 'o.is_delete' => 0]);


        $query = Order::find()->alias('o')->where([
            'o.store_id' => $this->store_id, 'o.type' => 1, 'o.is_pay' => 1, 'o.is_delete' => 0
        ])->leftJoin(Video::tableName() . ' v', 'v.id=o.video_id')
            ->leftJoin(User::tableName() . ' u', 'u.id=o.user_id');
        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['like', 'u.nickname', $this->keyword],
                ['like', 'o.order_no', $this->keyword]
            ]);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query->select([
            'o.id', 'v.is_delete', 'v.title', 'v.pic_url', 'v.status', 'v.is_delete', 'v.video_url'
            , 'o.addtime', 'u.nickname', 'o.order_no', 'o.price'
        ])->orderBy(['o.addtime' => SORT_DESC])->limit($p->limit)->offset($p->offset)->asArray()->all();
        return [
            'video_list' => $list,
            'pagination' => $p,
            'row_count' => $count
        ];
    }

    /**
     * 会员购买记录
     */
    public function member()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $query = MemberOrder::find()->alias('o')->where([
            'o.is_delete' => 0, 'o.store_id' => $this->store_id, 'o.is_pay' => 1])
            ->leftJoin(['u' => User::tableName()], 'u.id = o.user_id');
        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['like', 'u.nickname', $this->keyword],
                ['like', 'o.order_no', $this->keyword]
            ]);
        }
        $count = $query->count();

        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $list = $query->select([
            'o.*', 'u.nickname'
        ])->offset($p->offset)->limit($p->limit)->orderBy(['o.addtime' => SORT_DESC])->asArray()->all();


        return [
            'video_list' => $list,
            'pagination' => $p,
            'row_count' => $count
        ];
    }
}