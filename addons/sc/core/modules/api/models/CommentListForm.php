<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 14:16
 */

namespace app\modules\api\models;


use app\models\Comment;
use app\models\CommentType;
use app\models\User;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\VarDumper;

class CommentListForm extends Model
{
    public $store_id;
    public $user_id;


    public $video_id;
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['video_id'],'required'],
            [['page'],'default','value'=>1],
            [['limit'],'default','value'=>10],
        ];
    }

    public function search()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $query = Comment::find()->alias('c')->where(['c.store_id'=>$this->store_id,'c.is_delete'=>0,'c.c_id'=>0,'top_id'=>0,'video_id'=>$this->video_id])
            ->leftJoin(CommentType::tableName().' ct','ct.c_id=c.id')->leftJoin(User::tableName().' u','u.id=c.user_id');
        $count = $query->count();
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);
        //顶层评论
        $list_first = $query->select([
            'c.content','c.id','c.upload_img','c.addtime','u.avatar_url',
            'sum(case when ct.is_delete = 0 and ct.type = 0 then 1 else 0 end) thump_count','u.nickname'])
            ->limit($p->limit)->offset($p->offset)->orderBy(['c.addtime'=>SORT_DESC])->groupBy('c.id')->asArray()->all();


        $id_arr = [];
        foreach($list_first as $index=>$value){
            $id_arr[] = $value['id'];
        }

        //顶层评论的回复
        $list_second = Comment::find()->alias('c')->where([
            'c.store_id'=>$this->store_id,'c.is_delete'=>0,'c.video_id'=>$this->video_id
        ])->andWhere(['>','c.top_id',0])->andWhere(['>','c.c_id',0])->andWhere(['in','c.top_id',$id_arr])
            ->leftJoin(User::tableName().' u','u.id=c.user_id')
            ->leftJoin(Comment::tableName().' c1','c1.id=c.c_id')->leftJoin(User::tableName().' u1','u1.id=c1.user_id')->select([
                'u.nickname','c.top_id','c.content','c.upload_img','c.addtime','u1.nickname p_name','u.avatar_url','c.id'
            ])->asArray()->all();
        foreach($list_first as $index=>$value) {
            $list_first[$index]['time'] = $this->time($value['addtime']);
            $list_first[$index]['img'] = json_decode($value['upload_img'],true);
            $list_first[$index]['children'] = [];
            foreach($list_second as $i=>$v){
                $list_second[$i]['time'] = $this->time($v['addtime']);
                $list_second[$i]['img'] = json_decode($v['upload_img'],true);
                $list_second[$i]['content'] = CommentForm::userTextDecode($v['content']);
                if($v['top_id'] == $value['id']){
                    $list_first[$index]['children'][] = $list_second[$i];
                }
            }

            $exit = CommentType::find()->where(['store_id'=>$this->store_id,'is_delete'=>0,'c_id'=>$value['id'],'user_id'=>$this->user_id,'type'=>0])->exists();
            if($exit){
                $list_first[$index]['thump'] = true;
            }else{
                $list_first[$index]['thump'] = false;
            }
            $list_first[$index]['content'] = CommentForm::userTextDecode($value['content']);
        }

        $comment_count = Comment::find()->where(['is_delete'=>0,'store_id'=>$this->store_id,'video_id'=>$this->video_id])->count();
        return [
            'code'=>0,
            'msg'=>'success',
            'data'=>[
                'list'=>$list_first,
                'page_count'=>$p->pageCount,
                'row_count'=>$comment_count,
                'cc_count'=>$count
            ]
        ];

    }

    public static function time($addtime)
    {
        $time = time();
        $s_time = floor(($time-$addtime));
        if($s_time < 60){
            return $s_time.'秒前';
        }else if($s_time/60 < 60){
            $i_time =floor( $s_time/60);
            return $i_time.'分钟前';
        }else if($s_time/3600<24){
            $h_time = floor($s_time/3600);
            return $h_time.'小时前';
        }else if($s_time/86400<31){
            $d_time = floor($s_time/86400);
            return $d_time.'天前';
        }else if($s_time/(86400*31)<12){
            $m_time = floor($s_time/(86400*31));
            return $m_time.'月前';
        }else{
            $y_time = floor($s_time/(86400*31*12));
            return $y_time.'年前';
        }
    }
}