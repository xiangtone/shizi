<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%card}}".
 *
 * @property string $id
 * @property string $password
 * @property integer $card_type_id
 * @property integer $user_id
 * @property string $use_time
 * @property string $add_time
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'password', 'card_type_id'], 'required'],
            [['card_type_id', 'user_id'], 'integer'],
            [['use_time', 'add_time'], 'safe'],
            [['id'], 'string', 'max' => 20],
            [['password'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'Password',
            'card_type_id' => 'Card Type ID',
            'user_id' => 'User ID',
            'use_time' => 'Use Time',
            'add_time' => 'Add Time',
        ];
    }
}
