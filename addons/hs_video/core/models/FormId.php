<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%form_id}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $wechat_open_id
 * @property string $order_no
 * @property string $form_id
 * @property string $type
 * @property integer $send_count
 * @property integer $addtime
 */
class FormId extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form_id}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'send_count', 'addtime'], 'integer'],
            [['wechat_open_id', 'order_no', 'form_id', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'user_id' => 'User ID',
            'wechat_open_id' => 'Wechat Open ID',
            'order_no' => 'Order No',
            'form_id' => 'Form ID',
            'type' => 'Type',
            'send_count' => 'Send Count',
            'addtime' => 'Addtime',
        ];
    }


    /**
     * @param array $args
     * [
     * 'store_id'=>'店铺id',
     * 'user_id'=>'用户id',
     * 'wechat_open_id'=>'微信openid',
     * 'form_id'=>'Form Id 或 Prepay Id'
     * 'type'=>'form_id或prepay_id'
     * ]
     */
    public static function addFormId($args)
    {
        if (!isset($args['form_id']) || !$args['form_id'])
            return false;
        $model = new FormId();
        $model->attributes = $args;
        $model->addtime = time();
        return $model->save();
    }
}
