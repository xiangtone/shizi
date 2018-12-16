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
 * @property string $voice_url
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
            [['voice_url'], 'string'],
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
            'id' => '自增id',
            'video_id' => '视频id',
            'new_word' => '生字',
            'target_word' => '词汇',
            'voice_url' => '音频url',
        ];
    }
}
