<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 18:07
 */

namespace app\modules\api\models;


use app\extensions\TimeToDay;
use app\models\Cat;
use app\models\Video;
use yii\data\Pagination;

class CatVideoForm extends Model
{
    public $store_id;
    public $cat_id;
    public $limit;
    public $page;

    public function rules()
    {
        return [
            [['limit'],'default','value'=>10],
            [['page'],'default','value'=>0],
            [['cat_id'],'required']
        ];
    }

    public function search()
    {
        if(!$this->validate())
            return $this->getModelError();

        $query = Video::find()->alias('v')->where([
            'v.store_id'=>$this->store_id,'v.cat_id'=>$this->cat_id,'v.is_delete'=>0,'v.status'=>0
        ]);

        $count = $query->count();
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit,'page'=>$this->page]);
        $list = $query->select([
            'v.id','v.pic_url','v.title','v.video_time','v.style'
        ])->orderBy(['v.sort'=>SORT_ASC,'v.addtime'=>SORT_DESC])->offset($p->offset)->limit($p->limit)->asArray()->all();
        $cat = Cat::findOne(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>$this->cat_id]);
        foreach($list as $index=>$value){
            $list[$index]['video_time'] = TimeToDay::time($value['video_time']);
        }
        return [
            'code'=>0,
            'msg'=>'success',
            'data'=>[
                'list'=>$list,
                'cat_name'=>$cat->name
            ]
        ];
    }
}