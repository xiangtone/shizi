<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property string $id
 * @property integer $status
 * @property string $teacher_name
 * @property string $school_name
 * @property string $last_withdraw_time
 * @property string $apply_withdraw_time
 * @property integer $withdraw_status
 * @property string $ratio_ad
 * @property string $ratio_qa
 * @property string $bank_name
 * @property string $bank_account
 * @property string $total_withdraw_amount
 * @property string $current_withdrew_amount
 */
class Teacher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teacher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'status', 'last_withdraw_time', 'apply_withdraw_time', 'withdraw_status'], 'integer'],
            [['ratio_ad', 'ratio_qa', 'total_withdraw_amount', 'current_withdrew_amount'], 'number'],
            [['teacher_name'], 'string', 'max' => 20],
            [['school_name'], 'string', 'max' => 100],
            [['bank_name'], 'string', 'max' => 80],
            [['bank_account'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '同user表主键',
            'status' => '0等待审核1审核完成',
            'teacher_name' => '教师姓名',
            'school_name' => '学校名称',
            'last_withdraw_time' => '最后提现时间',
            'apply_withdraw_time' => '申请提现时间',
            'withdraw_status' => '提现状态:0没有提现1提现申请中',
            'ratio_ad' => '引流分成比例',
            'ratio_qa' => '提问点评分成比例',
            'bank_name' => '银行名称',
            'bank_account' => '银行账号',
            'total_withdraw_amount' => '累计提现',
            'current_withdrew_amount' => '提现中金额',
        ];
    }
}
