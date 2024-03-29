<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/26
 * Time: 14:55
 * http://127.0.0.1/ll/shizi/addons/sc/core/web/index.php?store_id=1&r=api/exsentence/index&video_id=9
 * https://www.dushujielong.com/ll/shizi/addons/sc/core/web/index.php?store_id=1&r=api/exsentence/index&video_id=9
 */

namespace app\modules\api\controllers;
use app\models\ExSentence;

class ExsentenceController extends Controller
{
    public function actionIndex()
    {
        //echo "Exsentence has been called";
        
        $video_id = \Yii::$app->request->get('video_id');
        
        $sentence_arr = ExSentence::find()->select(['segment1','segment2','segment3','segment4','voice_url'])->asArray()->where(['video_id' => $video_id])->all();
        //var_dump($sentence_arr);return;
        $rand_segment = array();
        $sentence = "";
        $info = "";
        if(!empty($sentence_arr)){
            
            foreach($sentence_arr as $key => $value){
                //$rand_word[$key] = $value['new_word'];
                $rand_segment= '';
                $sentence = "";
                foreach($value as $k =>$v){
                    if($k != 'voice_url'){
                        $rand_segment[$k] = $v; 
                        $sentence = $sentence .$v; 
                    }   
                    //var_dump($k);
                }
                //随机排序一下数组
                shuffle($rand_segment);
                //var_dump($rand_segment);
                //重新赋值
                $info[$key]['segment'] = $rand_segment;
                $info[$key]['sentence'] = $sentence;
                $info[$key]['voice_url'] =  str_replace("http","https",$sentence_arr[$key]['voice_url']);
                
                //shuffle($rand_segment);
            }
            //var_dump($sentence_arr);
            
            
        }
        //设置可以跨域访问
        header("Access-Control-Allow-Origin: *");
        //输出json
        $json_str  = json_encode($info);
        echo $json_str;
        
        
        
    }
       
}