<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ex_redical}}".
 *
 * @property integer $id
 * @property integer $video_id
 * @property string $new_word
 * @property string $voice_url
 * @property string $redical_word
 * @property string $combine_word
 * @property string $combine_type
 */
class ExRedical extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ex_redical}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_id'], 'integer'],
            [['voice_url'], 'string'],
            [['new_word', 'redical_word', 'combine_word', 'combine_type'], 'string', 'max' => 4],
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
            'voice_url' => '音频url',
            'redical_word' => '偏旁',
            'combine_word' => '组合的字',
            'combine_type' => '组合类型，偏旁在的位置，上下左右围',
        ];
    }
}
