<?php
/**
 * 小游戏编辑控制器
 */
namespace app\modules\admin\controllers;


use app\models\ExWord;
use app\modules\admin\models\ExWordFrom;

class GameController extends Controller
{
    
   
    /**
     * 组词游戏 字汇列表
     */
    public function actionWordList()
    {
        
        $form = new ExWordFrom();
        //从http的post 和 get中获取参数
        $form->attributes = \Yii::$app->request->get();
        //var_dump( $form->attributes);return;
        $arr = $form->search();
        //echo $form->attributes['video_id'];return;
        return $this->render('game-list',[
            'list'=>$arr['list'],
            'pagination'=>$arr['pagination'],
            'row_count'=>$arr['row_count'],
            'video_id'=>$form->attributes['video_id'],
        ]);
        
        
    }

    /**
     *  组词游戏编辑
     */
    public function actionEdit($id = null,$video_id=null)
    {
        //根据id查询是否有记录,有记录就是修改,没有就是添加
        $exWord = ExWord::findOne([
            'id' => $id,
        ]);
        //echo $id;echo $video_id;return;
        //var_dump($classes);die();
        if (!$exWord) {
            $exWord = new ExWord();
        }
        
        if(\Yii::$app->request->isPost){

             $form = new ExWordFrom();
             $form->exWord = $exWord;
             $form->attributes = \Yii::$app->request->post();
            
            // $this->renderJson([
            //     'code' => 0,
            //     'msg' => '成功',
            // ]);
            $this->renderJson($form->save());


        }else{
            return $this->render('edit',[
                'exWord'=>$exWord,
                'video_id'=>$video_id,
            ]);
        }
        
    }
    /*
     * 组词游戏删除
     */
    public function actionDel($id)
    {

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

    
}