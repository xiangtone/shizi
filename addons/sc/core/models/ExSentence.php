<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ex_sentence}}".
 *
 * @property integer $id
 * @property integer $video_id
 * @property string $segment1
 * @property string $segment2
 * @property string $segment3
 * @property string $segment4
 * @property string $voice_url
 */
class ExSentence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ex_sentence}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_id'], 'integer'],
            [['voice_url'], 'string'],
            [['segment1', 'segment2', 'segment3', 'segment4'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'video_id' => Yii::t('app', 'Video ID'),
            'segment1' => Yii::t('app', 'Segment1'),
            'segment2' => Yii::t('app', 'Segment2'),
            'segment3' => Yii::t('app', 'Segment3'),
            'segment4' => Yii::t('app', 'Segment4'),
            'voice_url' => Yii::t('app', 'Voice Url'),
        ];
    }
}
