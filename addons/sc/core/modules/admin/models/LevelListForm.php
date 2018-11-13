<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 15:55
 */

namespace app\modules\admin\models;


use app\models\Level;
use yii\data\Pagination;

class LevelListForm extends Model
{
    public $store_id;

    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page','limit'],'integer'],
            [['page'],'default','value'=>1],
            [['limit'],'default','value'=>20],
        ];
    }

    public function search()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $query = Level::find()->where(['store_id'=>$this->store_id,'is_delete'=>0]);
        $count = $query->count();

        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);

        $list = $query->orderBy(['sort'=>SORT_ASC,'addtime'=>SORT_DESC])
            ->limit($p->limit)->offset($p->offset)->asArray()->all();
        return [
            'list'=>$list,
            'pagination'=>$p,
            'row_count'=>$count
        ];
    }
}