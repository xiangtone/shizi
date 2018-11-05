<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%level}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $title
 * @property string $price
 * @property string $s_price
 * @property integer $date
 * @property integer $is_groom
 * @property integer $sort
 * @property integer $is_delete
 * @property integer $addtime
 */
class Level extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%level}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'date', 'is_groom', 'sort', 'is_delete', 'addtime'], 'integer'],
            [['price', 's_price'], 'number'],
            [['title'], 'string', 'max' => 255],
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
            'title' => '描述',
            'price' => '价格',
            's_price' => '续费价格',
            'date' => '时间（单位：天）',
            'is_groom' => '是否设为推荐  0--不是 1--是',
            'sort' => '排序',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }

    public function beforeSave($insert)
    {
        $this->title = Html::encode($this->title);
        return parent::beforeSave($insert);
    }
}
