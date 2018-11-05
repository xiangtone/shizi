<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/11
 * Time: 16:41
 */

namespace app\modules\api\models;


use app\models\Search;
use app\models\Video;

class HotSearchForm extends Model
{
    public $store_id;


    public function search()
    {
        //视频标题
        $video_title = Video::find()->where([
            'store_id'=>$this->store_id,
            'is_delete'=>0,
            'status'=>0
        ])->select(['title'])->asArray()->all();

        //热门搜索
        $hot_list = Search::find()->where([
            'store_id'=>$this->store_id,
            'is_delete'=>0
        ])->select([
            'count(id) count','keyword'
        ])->groupBy('keyword')->orderBy(['count'=>SORT_DESC])->limit(10)->asArray()->all();

        return [
            'code'=>0,
            'msg'=>'success',
            'data'=>[
                'video_title'=>$video_title,
                'hot_list'=>$hot_list
            ]
        ];
    }
}