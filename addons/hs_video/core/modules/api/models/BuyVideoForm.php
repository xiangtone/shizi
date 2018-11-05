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
            ->andWhere(['o.is_pay' => 1, 'o.user_id' => $this->user->id, 'o.is_delete' => 0]);

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
}