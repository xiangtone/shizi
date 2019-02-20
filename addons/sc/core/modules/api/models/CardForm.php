<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/26
 * Time: 11:34
 */

namespace app\modules\api\models;

use app\models\Card;
use app\models\CardType;
use app\models\Order;
use app\models\User;

class CardForm extends Model
{
    public $store_id;
    public $store;
    public $wechat_app;
    public $user_id;
    public $card_id;
    public $password;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['password'], 'integer'],
            [['card_id'], 'string'],
            //[['class_name', 'upload_img'], 'validateCheck'],
        ];
    }

    public function activeCard()
    {
        // $query = Comment::find()->alias('c')->where(['c.store_id' => $this->store_id, 'c.is_delete' => 0])
        //     ->leftJoin(User::tableName() . ' u', 'u.id=c.user_id')
        $card = Card::findOne(['id' => strtoupper($this->card_id), 'password' => $this->password]);
        // ->alias('c')->where(['c.id' => $this->card_id, 'c.password' => $this->password])
        //     ->leftJoin(CardType::tableName() . ' ct', 'ct.id=c.card_type_id')->select([
        //     'c.*', 'ct.product_id',
        // ])->asArray()->one();
        if (!$card) {
            return [
                'code' => 2,
                'msg' => "卡号密码不正确",
            ];
        } else {
            if ($card->user_id) {
                return [
                    'code' => 2,
                    'msg' => '已经被使用的无效卡',
                ];
            }
            $cardType = CardType::findOne($card->card_type_id);
            if ($cardType) {
                $checkOrder = Order::find()->where(['user_id' => $this->user_id,
                    'product_id' => $cardType->product_id,
                    'product_type' => 'cat',
                    'is_pay' => 1])->one();
                if ($checkOrder) {
                    return [
                        'code' => 2,
                        'msg' => '已经拥有该课程',
                    ];
                }
                $order = new Order();
                $order->store_id = $this->store_id;
                $order->product_id = $cardType->product_id;
                $order->product_type = 'cat';
                $order->user_id = $this->user_id;
                $order->is_delete = 0;
                $order->addtime = time();
                $order->order_no = $this->card_id;
                $order->is_pay = 1;
                $order->is_refund = 0;
                $order->pay_time = time();
                $order->type = 1;
                $order->pay_type = 0;
                $order->price = 0;
                $card->user_id = $this->user_id;
                if ($order->save() && $card->save()) {
                    return [
                        'code' => 0,
                        'msg' => '使用成功',
                        'data' => $cardType->product_id,
                    ];
                }
            }
        }
        return [
            'code' => 2,
            'msg' => "使用失败",
        ];
    }
}
