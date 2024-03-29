<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/26
 * Time: 14:55
 * http://127.0.0.1/ll/shizi/addons/sc/core/web/index.php?store_id=1&r=api/exword&video_id=9
 * https://www.dushujielong.com/ll/shizi/addons/sc/core/web/index.php?store_id=1&r=api/exword/index&video_id=9
 */

namespace app\modules\api\controllers;
use app\models\ExWord;

class ExwordController extends Controller
{
    public function actionIndex()
    {
        //echo "ExWord has been called";
        
        $video_id = \Yii::$app->request->get('video_id');
        //$new_word_arr = ExWord::find()->select(['new_word'])->asArray()->where(['video_id' => 9])->groupBy('new_word')->orderBy("RAND()")->all();
        $new_word_arr = ExWord::find()->select(['new_word'])->asArray()->where(['video_id' => $video_id])->groupBy('new_word')->all();
        //var_dump($new_word_arr);return;
        $rand_word = array();
        $target_word_arr = array();
        if(!empty($new_word_arr)){
            
            foreach($new_word_arr as $key => $value){
                $rand_word[$key] = $value['new_word'];
            }
            //var_dump($rand_word);return;
            //根据video_id获取targetword,然后根据targetword组成信息给用户
            $target_word_arr = ExWord::find()->select(['new_word','target_word','voice_url'])->asArray()->where(['video_id' => $video_id])->all();
            //$data=array();
            for($i = 0; $i < count($target_word_arr) ;$i++){
                shuffle($rand_word);//随机一下生字
                $target_word_arr[$i]['rand_word'] = $rand_word;//赋值
                //var_dump($target_word_arr[$i]);
                //处理一下url 把HTTP换成HTTPS 客户端的URL保持一致为HTTPS
                if($target_word_arr[$i]['voice_url'] != ''){
                    
                    $target_word_arr[$i]['voice_url'] =  str_replace("http","https",$target_word_arr[$i]['voice_url']);
                    //echo $target_word_arr[$i]['voice_url'];
                }
                
            }
        }
        
        
        //设置可以跨域访问
        header("Access-Control-Allow-Origin: *");
        //数组元素随机排序
        $json_str  = json_encode($target_word_arr);
        echo $json_str;
        //var_dump($json_str);
        //var_dump(json_decode($json_str));
        /*
        for($i = 0;$i < count($new_word_arr); $i++){
            //var_dump($target_word_arr[$i]) ;
            
            
            for($j = 0;$j < count($target_word_arr); $j++){
                
               
                $pos = strpos($target_word_arr[$j]['target_word'], $new_word_arr[$i]['new_word']);
                //echo "xx=".$pos;
                // if($pos){
                //     echo $target_word_arr[$j]['target_word'].$i."\n";
                // }
                
                echo "!new=".$new_word_arr[$i]['new_word'].",target=".$target_word_arr[$j]['target_word'];
                
                
            }
            
        }
        */
        //array("new_word"=>'耳',target_word='耳朵',rand_word="口手耳目");
        //var_dump($target_word_arr);

    }
}