<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ex_word}}".
 *
 * @property integer $id
 * @property integer $video_id
 * @property string $new_word
 * @property string $target_word
 */
class ExWord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ex_word}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_id'], 'integer'],
            [['new_word'], 'string', 'max' => 4],
            [['target_word'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'video_id' => 'Video ID',
            'new_word' => 'New Word',
            'target_word' => 'Target Word',
        ];
    }
}
