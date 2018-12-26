<?php

/**
 * 小游戏编辑控制器
 */
namespace app\modules\admin\controllers;


use app\models\ExChar;
use app\modules\admin\models\ExCharForm;
use app\models\ExWord;
use app\modules\admin\models\ExWordFrom;
use app\models\ExSentence;
use app\modules\admin\models\ExSentenceForm;

class GameController extends Controller
{

    public function actionCharList(){

        //echo \Yii::$app->params['zuciUrl'];return;
        $form = new ExCharForm();
        //从http的post 和 get中获取参数
        $form->attributes = \Yii::$app->request->get();
        
        //var_dump( $form->attributes);return;
        $arr = $form->search();
        //echo $form->attributes['video_id'];return;
        return $this->render('char-list', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count'],
            'video_id' => $form->attributes['video_id'],
        ]);
    }

    public function actionCharEdit($id = null, $video_id = null){
        //根据id查询是否有记录,有记录就是修改,没有就是添加
        $exWord = ExChar::findOne([
            'id' => $id,
        ]);
        //echo $id;echo $video_id;return;
        //var_dump($classes);die();
        if (!$exWord) {
            $exWord = new ExChar();
        }
        if (\Yii::$app->request->isPost) {

            $form = new ExCharForm();
            $form->exWord = $exWord;
            $form->attributes = \Yii::$app->request->post();

            // $this->renderJson([
            //     'code' => 0,
            //     'msg' => '成功',
            // ]);
            $this->renderJson($form->save());
        } else {
            return $this->render('char-edit', [
                'exWord' => $exWord,
                'video_id' => $video_id,
            ]);
        }
    }
   
    public function actionCharDel($id){
        $form = new ExCharForm();
        if ($form->del($id)) {
            $this->renderJson([
                'code' => 0,
                'msg' => '刪除成功'
            ]);
        } else {
            $this->renderJson([
                'code' => 1,
                'msg' => '刪除失敗'
            ]);
        }
    }

    /**
     * 组词游戏 字汇列表
     */
    public function actionWordList(){

        //echo \Yii::$app->params['zuciUrl'];return;
        $form = new ExWordFrom();
        //从http的post 和 get中获取参数
        $form->attributes = \Yii::$app->request->get();
        
        //var_dump( $form->attributes);return;
        $arr = $form->search();
        //echo $form->attributes['video_id'];return;
        return $this->render('word-list', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count'],
            'video_id' => $form->attributes['video_id'],
        ]);
    }


    /**
     *  组词游戏编辑
     */
    public function actionWordEdit($id = null, $video_id = null){
        //根据id查询是否有记录,有记录就是修改,没有就是添加
        $exWord = ExWord::findOne([
            'id' => $id,
        ]);
        //echo $id;echo $video_id;return;
        //var_dump($classes);die();
        if (!$exWord) {
            $exWord = new ExWord();
        }
        if (\Yii::$app->request->isPost) {

            $form = new ExWordFrom();
            $form->exWord = $exWord;
            $form->attributes = \Yii::$app->request->post();

            // $this->renderJson([
            //     'code' => 0,
            //     'msg' => '成功',
            // ]);
            $this->renderJson($form->save());
        } else {
            return $this->render('word-edit', [
                'exWord' => $exWord,
                'video_id' => $video_id,
            ]);
        }
    }
    /*
     * 组词游戏删除
     */
    public function actionWordDel($id){
        $form = new ExWordFrom();
        if ($form->del($id)) {
            $this->renderJson([
                'code' => 0,
                'msg' => '刪除成功'
            ]);
        } else {
            $this->renderJson([
                'code' => 1,
                'msg' => '刪除失敗'
            ]);
        }
    }
     //////////////////////////////组句相关操作/////////////////////
    /**
     * 组句游戏列表
     */
    public function actionSentenceList()
    {
        //echo "actionSentenceList called";
        $form = new ExSentenceForm();
        //从http的post 和 get中获取参数
        $form->attributes = \Yii::$app->request->get();
        
        //var_dump( $form->attributes);return;
        $arr = $form->search();
        //echo $form->attributes['video_id'];return;
        return $this->render('sentence-list', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count'],
            'video_id' => $form->attributes['video_id'],
        ]);
    }

     /**
     *  组句游戏编辑
     */
    public function actionSentenceEdit($id = null, $video_id = null){
        //根据id查询是否有记录,有记录就是修改,没有就是添加
        $exSentence = ExSentence::findOne([
            'id' => $id,
        ]);
        //echo $id;echo $video_id;return;
        //var_dump($classes);die();
        if (!$exSentence) {
            $exSentence = new ExSentence();
        }
        if (\Yii::$app->request->isPost) {

            $form = new ExSentenceForm();
            $form->exSentence = $exSentence;
            $form->attributes = \Yii::$app->request->post();

            // $this->renderJson([
            //     'code' => 0,
            //     'msg' => '成功',
            // ]);
            $this->renderJson($form->save());
        } else {
            return $this->render('sentence-edit', [
                'exSentence' => $exSentence,
                'video_id' => $video_id,
            ]);
        }
    }

    /*
     * 组句游戏删除
     */
    public function actionSentenceDel($id){
        $form = new ExSentenceForm();
        if ($form->del($id)) {
            $this->renderJson([
                'code' => 0,
                'msg' => '刪除成功'
            ]);
        } else {
            $this->renderJson([
                'code' => 1,
                'msg' => '刪除失敗'
            ]);
        }
    }
}