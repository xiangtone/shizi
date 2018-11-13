<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/22
 * Time: 10:13
 */

namespace app\modules\admin\models;

use app\models\UserCoupon;
use app\models\Video;
use yii\data\Pagination;
use app\models\Coupon;
use app\models\VideoCoupon;

class VideoCouponForm extends Model
{
    public $user_id_list;
    public $store_id;
    public $total_count;
    public $user_num;
    public $desc;
    public $coupon_id;
    public $coupon_name;
    public $draw_type;
    public $cost_price;
    public $coupon_price;
    public $original_cost;
    public $sub_price;
    public $expire_type;
    public $expire_day;
    public $begin_time;
    public $end_time;


    public $keyword;
    public $limit;
    public $store;


    public function rules()
    {
        return [
            [['user_id_list','user_num','total_count','desc','coupon_id'], 'required'],
            [['store_id',  'draw_type', 'expire_type', 'expire_day', 'begin_time', 'end_time',], 'integer'],
            [['cost_price', 'coupon_price', 'original_cost', 'sub_price'], 'number'],
            [['coupon_name'], 'string', 'max' => 255],
            [['keyword'], 'trim'],
            [['keyword'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'store_id' => 'Store ID',
            'user_num' => 'User Num',
            'total_count' => 'Total Count',
            'desc' => 'Desc',
            'coupon_id' => 'Coupon Id',
            'coupon_name' => 'Coupon Name',
            'draw_type' => 'Draw Type',
            'cost_price' => 'Cost Price',
            'coupon_price' => 'Coupon Price',
            'original_cost' => 'Original Cost',
            'sub_price' => 'Sub Price',
            'expire_type' => 'Expire Type',
            'expire_day' => 'Expire Day',
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
        ];
    }

    public function save(){
        if (!$this->validate())
            return $this->getModelError();
        foreach ($this->total_count as $key => &$value) {
            if(!$value){
                unset($this->total_count[$key]);
            }
        }
        foreach ($this->user_num as $key => &$value) {
            if(!$value){
                unset($this->user_num[$key]);
            }
        }
        foreach ($this->desc as $key => &$value) {
            if(!$value){
                unset($this->desc[$key]);
            }
        }
        if(count($this->user_id_list) > count($this->total_count))
        return [
            'code' => 1,
            'msg' => "请输入总发放数",
        ];
        if(count($this->user_id_list) > count($this->user_num))
            return [
                'code' => 1,
                'msg' => "请输入可领数量",
            ];
        if(count($this->user_id_list) > count($this->desc)){
            return [
                'code' => 1,
                'msg' => "请输入简短描述",
            ];
        }


        return Coupon::userAddCoupon($this->user_id_list,$this->store_id,$this->total_count,$this->user_num,$this->desc,$this->coupon_id,$this->coupon_name,$this->draw_type,$this->cost_price,$this->coupon_price,$this->original_cost,$this->sub_price,$this->expire_type,$this->expire_day,$this->begin_time,$this->end_time);
    }

    public function edit($id)
    {
        $videoCoupon = VideoCoupon::findOne($id);
        $videoCoupon->total_count = $this->total_count;
        $videoCoupon->user_num = $this->user_num;
        $videoCoupon->desc = $this->desc;
        if($videoCoupon->save()){
            return [
                'code' => 0,
                'msg' => "修改成功",
            ];
        }else{
            return [
                'code' => 1,
                'msg' => "修改失败",
            ];
        }

    }


    public function getList()
    {
        $query = VideoCoupon::find()->alias('vc')->where(['vc.store_id' => $this->store_id,'vc.is_delete'=>0])
            ->leftJoin(Video::tableName().' v','v.id=vc.video_id');

        if($this->coupon_name){
            $query->andWhere(['like','vc.coupon_name',$this->coupon_name]);
        }
        if($this->keyword){
            $query->andWhere(['like','v.title',$this->keyword]);
        }
        $count = $query->count();

        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $list = $query->select(['v.title video_title','vc.*'])
            ->orderBy([ 'vc.addtime' => SORT_DESC])->offset($p->offset)->limit($p->limit)->asArray()->all();

        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count
        ];
    }

}