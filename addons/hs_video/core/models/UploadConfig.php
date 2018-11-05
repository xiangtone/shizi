<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%upload_config}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $storage_type
 * @property string $aliyun
 * @property string $qcloud
 * @property string $qiniu
 * @property integer $addtime
 * @property integer $is_delete
 */
class UploadConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%upload_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'addtime', 'is_delete'], 'integer'],
            [['aliyun', 'qcloud', 'qiniu'], 'string'],
            [['storage_type'], 'string', 'max' => 255],
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
            'storage_type' => 'Storage Type',
            'aliyun' => 'Aliyun',
            'qcloud' => 'Qcloud',
            'qiniu' => 'Qiniu',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }
}
