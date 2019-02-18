<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%card_type}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $product_type
 * @property string $prefix
 * @property string $start_num
 * @property string $end_num
 */
class CardType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%card_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'product_type', 'prefix', 'start_num', 'end_num'], 'required'],
            [['product_id', 'start_num', 'end_num'], 'integer'],
            [['product_type', 'prefix'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'product_type' => 'Product Type',
            'prefix' => 'Prefix',
            'start_num' => 'Start Num',
            'end_num' => 'End Num',
        ];
    }
}
