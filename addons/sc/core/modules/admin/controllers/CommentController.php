<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 17:58
 */

namespace app\modules\admin\controllers;

use app\models\Comment;
use app\models\Video;
use app\modules\admin\models\CommentListForm;

class CommentController extends Controller
{
    public function actionIndex()
    {
        $form = new CommentListForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        return $this->render('index', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count'],
        ]);
    }

    public function actionDel($id = null)
    {
        $comment = Comment::findOne(['store_id' => $this->store->id, 'is_delete' => 0, 'id' => $id]);
        if (!$comment) {
            $this->renderJson([
                'code' => 1,
                'msg' => '评论已删除',
            ]);
        }
        $comment->is_delete = 1;
        if ($comment->save()) {
            $count = Comment::updateAll(['is_delete' => 1], ['store_id' => $this->store->id, 'top_id' => $comment->id])+1;
            $video = Video::findOne(['id' => $comment->video_id]);
            if ($video->comment_count >= $count) {
                $video->comment_count = $video->comment_count - $count;
                $video->save();
            }
            $this->renderJson([
                'code' => 0,
                'msg' => '删除成功',
            ]);
        } else {
            $this->renderJson([
                'code' => 1,
                'msg' => '网络异常',
            ]);
        }

    }

    public function actionBatch()
    {
        $get = \Yii::$app->request->get();
        Comment::updateAll(['is_delete' => 1], ['and', ['store_id' => $this->store->id], ['in', 'id', $get['check']]]);
        Comment::updateAll(['is_delete' => 1], ['and', ['store_id' => $this->store->id], ['in', 'top_id', $get['check']]]);
        $this->renderJson([
            'code' => 0,
            'msg' => '删除成功',
        ]);
    }
}
