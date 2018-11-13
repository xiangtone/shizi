<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/4/3
 * Time: 20:35
 */

namespace app\modules\admin\models;


use app\models\User;
use app\models\UserCoupon;
use yii\data\Pagination;

class UserCouponForm extends Model
{
    public $coupon_name;
    public $store_id;
    public $limit;
    public $store;
    public $keyword;

    public function rules()
    {
        return [
            [['coupon_name'], 'string', 'max' => 255],
            [['keyword'], 'trim'],
            [['keyword'], 'string']
        ];
    }
    public function getList()
    {

        $query = UserCoupon::find()->alias('vc')->where(['vc.store_id' => $this->store_id,'vc.is_delete'=>0])
            ->leftJoin(User::tableName().' v','v.id=vc.user_id');
        if($this->coupon_name){
            $query->andWhere(['like','vc.coupon_name',$this->coupon_name]);
        }

        if($this->keyword){
            $query->andWhere(['like','v.nickname',$this->keyword]);
        }
        $count = $query->count();

        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $list = $query->select(['v.nickname','vc.*'])
            ->orderBy([ 'vc.addtime' => SORT_DESC])->offset($p->offset)->limit($p->limit)->asArray()->all();

        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count
        ];
    }

}