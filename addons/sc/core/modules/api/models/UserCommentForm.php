<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/28
 * Time: 11:44
 */

namespace app\modules\api\models;


use app\models\Comment;
use app\models\User;
use yii\data\Pagination;

class UserCommentForm extends Model
{
    public $store_id;
    public $user_id;

    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 10]
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $query = Comment::find()->alias('c')->where(['c.store_id' => $this->store_id, 'c.is_delete' => 0])
            ->andWhere(['>', 'c.c_id',0])->leftJoin(Comment::tableName().' c1','c1.id=c.c_id')
            ->leftJoin(User::tableName().' u','u.id=c.user_id')
            ->andWhere(['c1.user_id'=>$this->user_id]);
        $count = $query->count();
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);

        $list = $query->select([
            'c.video_id','c.content','c.upload_img','u.nickname','c.id','c.addtime',
            'c1.content c1_content','c1.upload_img c1_upload_img','u.avatar_url'
        ])->offset($p->offset)->limit($p->limit)->orderBy(['c.addtime'=>SORT_DESC])->asArray()->all();

        foreach($list as $index=>$value){
            $list[$index]['img'] = json_decode($value['upload_img'],true);
            $list[$index]['time'] = CommentListForm::time($value['addtime']);
            $list[$index]['u_img'] = json_decode($value['c1_upload_img'],true);
            $list[$index]['content'] = CommentForm::userTextDecode($value['content']);
        }
        return [
            'code'=>0,
            'msg'=>'',
            'data'=>[
                'list'=>$list,
                'page_count'=>$p->pageCount,
                'row_count'=>$count
            ]
        ];
    }
}