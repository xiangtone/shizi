<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%advertisement}}".
 *
 * @property string $id
 * @property string $name
 * @property string $pic
 * @property string $appid
 * @property string $path
 * @property integer $created_at
 */
class Advertisement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advertisement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pic', 'path'], 'string'],
            [['created_at'], 'integer'],
            [['title', 'appid'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'pic' => 'Pic',
            'appid' => 'Appid',
            'path' => 'Path',
            'created_at' => 'Created At',
        ];
    }
}
