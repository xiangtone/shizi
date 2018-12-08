<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 18:06
 */

namespace app\modules\api\models;


use app\models\Cat;
use app\models\Video;
use yii\data\Pagination;

class CatForm extends Model
{
    public $store_id;
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page','limit'],'integer'],
            [['page'],'default','value'=>1],
            [['limit'],'default','value'=>10]
        ];
    }

    public function search()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $query = Cat::find()->select([
            'id','name','pic_url','update_time'
        ])->where([
            'store_id'=>$this->store_id,
            'is_delete'=>0,
            'is_display'=>1
        ]);
        $list_count = $query->count();

        $p = new Pagination(['totalCount'=>$list_count,'pageSize'=>$this->limit,'page'=>$this->page-1]);


        $cat_list = $query->offset($p->offset)->limit($p->limit)->orderBy(['sort'=>SORT_ASC])->asArray()->all();
        foreach($cat_list as $index=>$value){
            $cat_list[$index]['update_time'] = date('Y-m-d',$value['update_time']);
            $count = Video::find()->where([
                'store_id'=>$this->store_id,
                'is_delete'=>0,
                'cat_id'=>$value['id'],
                'status'=>0
            ])->count();
            $cat_list[$index]['video_count'] = $count;
        }
        return [
            'code'=>0,
            'msg'=>'success',
            'data'=>[
                'cat_list'=>$cat_list,
                'page_count'=>$p->pageCount,
                'row_count'=>$list_count
            ]
        ];
    }
}