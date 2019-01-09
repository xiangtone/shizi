<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/20
 * Time: 14:31
 */

namespace app\modules\api\models;


use app\models\Order;
use app\models\Video;
use app\models\Cat;
use yii\data\Pagination;

class BuyVideoForm extends Model
{
    public $store_id;
    public $user;

    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page', 'limit'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 10],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

        $query = Video::find()->alias('v')->where(['v.store_id' => $this->store_id])
            ->leftJoin(Order::tableName() . ' o', 'o.video_id=v.id and o.type = 1')
            ->andWhere(['o.is_pay' => 1, 'o.user_id' => $this->user->id, 'o.is_delete' => 0,'o.product_type'=>'video']);

        $count = $query->count();
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);

        $list = $query->select([
            'v.id','v.is_delete','v.title','v.pic_url','v.status','v.is_delete','o.addtime'
        ])->orderBy(['o.addtime'=>SORT_DESC])->limit($p->limit)->offset($p->offset)->asArray()->all();
        foreach($list as $index=>$value){
            $list[$index]['add_time'] = date('Y-m-d H:i',$value['addtime']);
        }
        return [
            'data'=>[
                'video_list'=>$list,
                'page_count'=>$p->pageCount,
                'row_count'=>$count
            ]
        ];
    }

    public function searchCat()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

        $query = Cat::find()->alias('v')->where(['v.store_id' => $this->store_id])
            ->leftJoin(Order::tableName() . ' o', 'o.product_id=v.id and o.type = 1')
            ->andWhere(['o.is_pay' => 1, 'o.user_id' => $this->user->id, 'o.is_delete' => 0,'o.product_type'=>'cat']);

        $count = $query->count();
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);

        $list = $query->select([
            'v.id','v.is_delete','v.name as title','v.pic_url','v.is_delete','o.addtime'
        ])->orderBy(['o.addtime'=>SORT_DESC])->limit($p->limit)->offset($p->offset)->asArray()->all();
        foreach($list as $index=>$value){
            $list[$index]['add_time'] = date('Y-m-d H:i',$value['addtime']);
            $list[$index]['status'] = 0;
        }
        return [
            'data'=>[
                'video_list'=>$list,
                'page_count'=>$p->pageCount,
                'row_count'=>$count
            ]
        ];
    }
}