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
 * @property integer $is_top
 * @property integer $ex_count
 * @property integer $lesson_count
 * @property integer $comment_count
 * @property integer $ex_top
 * @property integer $lesson_top
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
            [['type', 'create_user_id', 'create_time', 'is_delete', 'is_top', 'ex_count', 'lesson_count', 'comment_count', 'ex_top', 'lesson_top'], 'integer'],
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
            'id' => '自增id',
            'class_name' => '班级名称',
            'type' => '0系统自动创建1老师创建',
            'create_user_id' => '0系统自动创建，创建者user_id',
            'create_time' => '创建时间',
            'is_delete' => '是否已经删除',
            'img_url' => '图片url',
            'is_top' => '是否进入排行榜',
            'ex_count' => '练习次数-相当于班级贡献累计',
            'lesson_count' => '课时次数',
            'comment_count' => '点评次数',
            'ex_top' => '练习排名',
            'lesson_top' => '课时排名',
        ];
    }
}
