<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 11:45
 */

namespace app\modules\admin\models;


use app\models\Banner;

class BannerListForm extends Model
{
    public $store_id;

    public function search()
    {
        $query = Banner::find()->where(['store_id'=>$this->store_id,'is_delete'=>0]);
        $count = $query->count();
        $list = $query->orderBy(['sort'=>SORT_ASC])->asArray()->all();
        return [
            'list'=>$list,
            'row_count'=>$count
        ];
    }
}