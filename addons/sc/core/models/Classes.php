<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%classes}}".
 *
 * @property string $id
 * @property string $class_name
 * @property integer $type
 * @property string $create_user_id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $img_url
 */
class Classes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%classes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'create_user_id', 'create_time', 'is_delete'], 'integer'],
            [['img_url'], 'string'],
            [['class_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_name' => 'Class Name',
            'type' => '0系统自动创建1老师创建',
            'create_user_id' => '0系统自动创建，老师user_id',
            'create_time' => 'Create Time',
            'is_delete' => '是否已经删除',
            'img_url' => 'Img Url',
        ];
    }
}
