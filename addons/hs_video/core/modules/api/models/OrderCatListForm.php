<?php
/***
 */
namespace app\modules\api\models;


use app\models\Order;
use app\models\OrderRefund;
use app\models\Video;
use yii\data\Pagination;

class OrderCatListForm extends Model
{
    public $store_id;
    public $user_id;

    public $status;
    public $page;
    public $limit;


    public function rules()
    {
        return [
            [['status', 'page', 'limit'], 'integer'],
            [['status'], 'default', 'value' => -1],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 10],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

        $query = OrderCat::find()->alias('o')->where([
            'o.store_id' => $this->store_id, 'o.is_pay' => 1, 'o.is_delete' => 0, 'o.user_id' => $this->user_id
        ])->leftJoin(Video::tableName() . ' v', 'v.id=o.video_id');

        if($this->status == 1){//未使用
            $query->andWhere(['o.is_use'=>0]);
        }
        if($this->status == 2){//已使用
            $query->andWhere(['o.is_use' => 1]);
        }
        if($this->status == 3){//退款
            return $this->getRefundList();
        }

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);

        $list = $query->select([
            'o.order_no', 'o.is_use', 'o.price', 'v.title', 'v.pic_url', 'o.id','o.addtime','o.is_refund'
        ])->offset($p->offset)->limit($p->limit)->orderBy(['o.addtime' => SORT_DESC])->asArray()->all();
        foreach($list as $index=>$value){
            $list[$index]['addtime'] = date('Y-m-d H:i:s',$value['addtime']);
        }
        return [
            'code' => 0,
            'msg' => '',
            'data' => [
                'order_list' => $list,
                'row_count' => $count,
                'page_count' => $p->pageCount
            ]
        ];
    }

    private function getRefundList()
    {
        $query = OrderRefund::find()->alias('or')->leftJoin(Order::tableName().' o','o.id=or.order_id')
            ->leftJoin(Video::tableName().' v','v.id=or.video_id')
            ->where([
                'or.store_id'=>$this->store_id,
                'or.user_id'=>$this->user_id,
                'or.is_delete'=>0,
                'o.is_delete'=>0,
                'v.is_delete'=>0,
                'o.is_use'=>2
            ]);
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->select([
            'o.id','o.price','o.is_use','v.title','v.pic_url','or.status','or.type','o.order_no','o.addtime'
        ])->offset($p->offset)->limit($p->limit)->orderBy(['or.addtime' => SORT_DESC])->asArray()->all();

        foreach($list as $index=>$value){
            $list[$index]['addtime'] = date('Y-m-d H:i:s',$value['addtime']);
        }
        return [
            'code' => 0,
            'msg' => '',
            'data' => [
                'order_list' => $list,
                'row_count' => $count,
                'page_count' => $p->pageCount
            ]
        ];
    }
}