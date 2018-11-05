<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%search}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $keyword
 * @property integer $is_delete
 * @property integer $addtime
 */
class Search extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%search}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'is_delete', 'addtime'], 'integer'],
            [['keyword'], 'string', 'max' => 255],
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
            'keyword' => 'Keyword',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
    public function beforeSave($insert)
    {
        $this->keyword = Html::encode($this->keyword);
        return parent::beforeSave($insert);
    }
}
