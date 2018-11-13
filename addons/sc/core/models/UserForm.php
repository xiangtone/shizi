<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%user_form}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $video_id
 * @property integer $user_id
 * @property integer $order_id
 * @property string $key
 * @property string $value
 * @property integer $is_delete
 * @property integer $addtime
 */
class UserForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_form}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'video_id', 'user_id', 'order_id', 'is_delete', 'addtime'], 'integer'],
            [['key', 'value'], 'string', 'max' => 255],
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
            'video_id' => 'Video ID',
            'user_id' => 'User ID',
            'order_id' => 'Order ID',
            'key' => 'Key',
            'value' => 'Value',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
    public function beforeSave($insert)
    {
        $this->key = Html::encode($this->key);
        $this->value = Html::encode($this->value);
        return parent::beforeSave($insert);
    }
}
