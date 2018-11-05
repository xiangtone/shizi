<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/20
 * Time: 17:32
 */

namespace app\modules\admin\models;

use app\models\Coupon;
use yii\data\Pagination;
use app\extensions\TimeToDay;
use app\models\Collect;

class CouponForm extends Model
{
    public $coupon;
    public $store_id;
    public $name;
    public $draw_type;
    public $cost_price;
    public $coupon_price;
    public $original_cost;
    public $sub_price;
    public $expire_type;
    public $expire_day;
    public $begin_time;
    public $end_time;
    public $sort;


    public $limit;
    public $keyword;
    public $store;

    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['name', 'cost_price','coupon_price','sub_price','expire_type','draw_type', 'expire_day', 'begin_time', 'end_time','original_cost'], 'required'],
            [['expire_day','sort'], 'integer', 'min' => 0],
            [[ 'sub_price','original_cost'], 'number', 'min' => 0,],
            [['sort'],'default','value'=>100],
            [['keyword'], 'trim'],
            [['keyword'], 'string']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => '优惠券名称',
            'draw_type' => '领取方式',
            'cost_price' => '原价',
            'coupon_price' => '劵后价',
            'original_cost'=>'满消费额度',
            'sub_price' => '优惠额度',
            'expire_type' => '到期类型',
            'expire_day' => '有效天数',
            'begin_time' => '有效期开始时间',
            'end_time' => '有效期结束时间',
            'sort' => '排序',
        ];
    }


    public function save()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        if($this->coupon->isNewRecord){
            $this->coupon->addtime = time();
            $this->coupon->store_id = $this->store_id;
            $this->coupon->is_delete = 0;
        }
        $this->coupon->name = $this->name;
        $this->coupon->draw_type = $this->draw_type;
        $this->coupon->cost_price = $this->cost_price;
        $this->coupon->coupon_price = $this->coupon_price;
        $this->coupon->original_cost = $this->original_cost;
        $this->coupon->sub_price = $this->sub_price;
        $this->coupon->expire_type = $this->expire_type;
        $this->coupon->expire_day = $this->expire_day;
        $this->coupon->begin_time = strtotime($this->begin_time . ' 00:00:00');
        $this->coupon->end_time = strtotime($this->end_time . ' 23:59:59');
        $this->coupon->sort = $this->sort;
        if($this->coupon->save()){
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        }else{
            return $this->getModelError($this->coupon);
        }
    }


    public function getList()
    {
        $query = Coupon::find()->where(['store_id' => $this->store_id,'is_delete'=>0]);

        if ($this->keyword){
            $query->andWhere(['like', 'name', $this->keyword]);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $list = $query->orderBy(['sort' => SORT_ASC, 'addtime' => SORT_DESC])->offset($p->offset)->limit($p->limit)->asArray()->all();
        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count
        ];
    }

}