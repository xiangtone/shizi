<?php

namespace app\models;

use Yii;


/**
 * Class Classes
 * @package app\models
 */
class Classes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zjhj_video_classes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','create_user_id'], 'integer'],
            [['class_name'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_name' => '班级名称',
            'type' => '创建类型',
            'create_user_id' => '创建者ID',
            'create_time' => '创建时间',

        ];
    }




}

