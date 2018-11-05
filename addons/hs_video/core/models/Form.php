<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%form}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $video_id
 * @property string $name
 * @property string $type
 * @property integer $required
 * @property string $default
 * @property string $tip
 * @property integer $is_delete
 * @property integer $addtime
 */
class Form extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'video_id', 'required', 'is_delete', 'addtime'], 'integer'],
            [['name', 'type', 'default', 'tip'], 'string', 'max' => 255],
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
            'name' => '字段名称',
            'type' => '字段类型',
            'required' => '是否必填',
            'default' => '默认值',
            'tip' => '提示语',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
    public function beforeSave($insert)
    {
        $this->name = Html::encode($this->name);
        $this->default = Html::encode($this->default);
        $this->tip = Html::encode($this->tip);
        return parent::beforeSave($insert);
    }
}
