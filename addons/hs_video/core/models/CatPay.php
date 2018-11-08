<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cat_pay}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $cat_id
 * @property integer $type
 * @property string $price
 */
class CatPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cat_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'cat_id', 'type'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'store_id' => Yii::t('app', '小程序id'),
            'cat_id' => Yii::t('app', '视频分类'),
            'type' => Yii::t('app', '付费类型'),
            'price' => Yii::t('app', '收费价格'),
        ];
    }
}
