<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 17:30
 */

namespace app\modules\api\models;


use app\models\CommentType;

class ThumpForm extends Model
{
    public $store_id;
    public $user_id;

    public $c_id;

    public function rules()
    {
        return [
            [['c_id'], 'required']
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $comment_type = CommentType::find()->where(['store_id' => $this->store_id, 'c_id' => $this->c_id, 'user_id' => $this->user_id])->one();
        if ($comment_type) {
            $comment_type->is_delete = ($comment_type->is_delete + 1) % 2;
        }else{
            $comment_type = new CommentType();
            $comment_type->user_id = $this->user_id;
            $comment_type->store_id = $this->store_id;
            $comment_type->c_id = $this->c_id;
            $comment_type->is_delete = 0;
            $comment_type->type = 0;
            $comment_type->addtime = time();
        }
        if($comment_type->save()){
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        }else{
            return $this->getModelError($comment_type);
        }
    }
}