<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 14:39
 */

namespace app\modules\api\models;


use app\models\Collect;

class CollectForm extends Model
{
    public $store_id;
    public $user_id;

    public $video_id;


    public function rules()
    {
        return [
            [['video_id'],'required']
        ];
    }

    public function save()
    {
        if(!$this->validate())
            return $this->getModelError();
        $collect = Collect::find()->where(['store_id'=>$this->store_id,'user_id'=>$this->user_id,'video_id'=>$this->video_id])->one();
        $collect_count = Collect::find()->where(['store_id'=>$this->store_id,'video_id'=>$this->video_id,'is_delete'=>0])->count();
        if(!$collect){
            $collect = new Collect();
            $collect->video_id = $this->video_id;
            $collect->store_id = $this->store_id;
            $collect->user_id = $this->user_id;
            $collect->is_delete = 0;
            $collect->addtime = time();
            if($collect->save()){
                $collect_count += 1;
                return [
                    'code'=>0,
                    'msg'=>'success',
                    'data'=>[
                        'collect'=>$collect->is_delete,
                        'collect_count'=>$collect_count
                    ]
                ];
            }else{
                return [
                    'code'=>1,
                    'msg'=>'fail'
                ];
            }
        }
        if($collect->is_delete == 0){
            $collect->is_delete = 1;
            if($collect->save()){
                $collect_count -= 1;
                return [
                    'code'=>0,
                    'msg'=>'success',
                    'data'=>[
                        'collect'=>$collect->is_delete,
                        'collect_count'=>$collect_count
                    ]
                ];
            }else{
                return [
                    'code'=>1,
                    'msg'=>'fail'
                ];
            }
        }else{
            $collect->is_delete = 0;
            if($collect->save()){
                $collect_count += 1;
                return [
                    'code'=>0,
                    'msg'=>'success',
                    'data'=>[
                        'collect'=>$collect->is_delete,
                        'collect_count'=>$collect_count
                    ]
                ];
            }else{
                return [
                    'code'=>1,
                    'msg'=>'fail'
                ];
            }
        }

    }
}