<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/30
 * Time: 13:54
 */

namespace app\modules\school\models;


use app\models\Cat;
use app\models\Collect;
use app\models\Video;
use yii\data\Pagination;
use app\extensions\TimeToDay;
use yii\base\Model;

class VideoListForm extends Model
{

    public $cat_id;


    public function rules()
    {
        return [
            [['cat_id'], 'integer']
        ];
    }

    public function getList()
    {
        $query = Video::find()->alias('v')->where(['v.is_delete' => 0,'status'=>1,'cat_id'=> $this->cat_id])
            ->leftJoin(Cat::tableName() . ' c', 'c.id=v.cat_id');



        $list = $query->orderBy(['v.sort' => SORT_ASC, 'v.addtime' => SORT_DESC])->asArray()->all();
        foreach ($list as $index => $value) {
           

            $list[$index]['video_time'] = TimeToDay::time($value['video_time']);
            // $list[$index]['page_view'] = TimeToDay::getPageView($value['page_view']);
            // $list[$index]['content_len'] = mb_strlen($value['content']);
        }
        return [
            'list' => $list,
            
        ];
    }
}