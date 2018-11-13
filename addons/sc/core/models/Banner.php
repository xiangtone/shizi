<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $banner_url
 * @property string $url
 * @property string $introduce
 * @property integer $sort
 * @property integer $is_delete
 * @property integer $addtime
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'sort', 'is_delete', 'addtime'], 'integer'],
            [['banner_url', 'url', 'introduce'], 'string'],
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
            'banner_url' => 'Banner Url',
            'url' => 'Url',
            'introduce' => '介绍',
            'sort' => '100',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }

    public function beforeSave($insert)
    {
        $this->introduce = Html::encode($this->introduce);
        $this->url = Html::encode($this->url);
        $this->banner_url = Html::encode($this->banner_url);
        return parent::beforeSave($insert);
    }
}
