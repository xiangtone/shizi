<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property integer $addtime
 * @property integer $is_delete
 * @property string $wechat_open_id
 * @property string $wechat_union_id
 * @property string $nickname
 * @property string $avatar_url
 * @property integer $store_id
 * @property integer $is_comment
 * @property integer $is_clerk
 * @property integer $is_member
 * @property integer $due_time
 * @property string $binding
 * @property integer $teacher_id
 * @property integer $channel_id
 * @property integer $is_teacher
 * @property integer $last_video
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'addtime', 'is_delete', 'store_id', 'is_comment', 'is_clerk', 'is_member', 'due_time', 'teacher_id', 'channel_id', 'is_teacher', 'last_video'], 'integer'],
            [['username', 'password', 'auth_key', 'access_token'], 'required'],
            [['avatar_url'], 'string'],
            [['username', 'password', 'auth_key', 'access_token', 'wechat_open_id', 'wechat_union_id', 'nickname'], 'string', 'max' => 255],
            [['binding'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '用户类型：0=管理员，1=普通用户',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'wechat_open_id' => '微信openid',
            'wechat_union_id' => '微信用户union id',
            'nickname' => '昵称',
            'avatar_url' => '头像url',
            'store_id' => '商城id',
            'is_comment' => '是否禁言 0--否 1--是',
            'is_clerk' => 'Is Clerk',
            'is_member' => '是否是会员',
            'due_time' => '会员到期时间',
            'binding' => '授权手机号',
            'teacher_id' => '上线老师ID',
            'channel_id' => '渠道ID',
            'is_teacher' => '是否是老师',
            'last_video' => '最后打开的视频',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}
