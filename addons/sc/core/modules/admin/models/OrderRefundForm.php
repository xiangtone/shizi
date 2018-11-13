<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20
 * Time: 16:06
 */

namespace app\modules\admin\models;


use app\models\OrderRefund;
use app\models\User;
use app\models\Video;
use yii\data\Pagination;

class OrderRefundForm extends Model
{
    public $store_id;
    public $status;
    public $keyword;
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['status', 'page', 'limit'], 'integer'],
            [['status'], 'in', 'range' => [-1, 1, 2]],
            [['keyword'],'trim'],
            [['keyword'],'string'],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 10],

        ];
    }

    public function search()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }

        $query = OrderRefund::find()->alias('or')->leftJoin(Video::tableName().' v','v.id=or.video_id')
            ->leftJoin(User::tableName().' u','u.id=or.user_id')
            ->where([
                'or.store_id'=>$this->store_id,
                'or.is_delete'=>0
            ]);

        if($this->status == 1){
            $query->andWhere(['or.status'=>0]);
        }

        if($this->status == 2){
            $query->andWhere(['or',['or.status'=>1],['or.status'=>2]]);
        }

        if($this->keyword){
            $query->andWhere([
                'or',
                ['like','or.order_refund_no',$this->keyword],
                ['like','u.nickname',$this->keyword]
            ]);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);

        $list = $query->select([
            'or.*','u.nickname','v.title','v.pic_url'
        ])->offset($p->offset)->limit($p->limit)->orderBy(['or.addtime'=>SORT_DESC])->asArray()->all();

        return [
            'list'=>$list,
            'pagination'=>$p,
            'row_count'=>$count
        ];
    }
}