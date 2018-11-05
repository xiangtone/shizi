<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/11
 * Time: 10:30
 */

namespace app\modules\api\models;


use app\extensions\TimeToDay;
use app\models\Collect;
use app\models\Video;
use yii\data\Pagination;

class CollectVideoForm extends Model
{
    public $store_id;
    public $user_id;

    public $limit;
    public $page;

    public function rules()
    {
        return [
            [['limit'], 'default', 'value' => 20],
            [['page'], 'default', 'value' => 0]
        ];
    }

    public function search()
    {
        if (!$this->validate())
            return $this->getModelError();

        $query = Collect::find()->alias('c')->where([
            'c.is_delete' => 0, 'c.store_id' => $this->store_id, 'c.user_id' => $this->user_id
        ])->leftJoin(Video::tableName() . ' v', 'v.id=c.video_id')->andWhere([
            'v.store_id' => $this->store_id, 'v.is_delete' => 0
        ]);

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page]);
        $list = $query->select([
            'v.id', 'v.pic_url', 'v.title', 'video_time','v.status','v.style'
        ])->orderBy(['c.addtime' => SORT_DESC])->limit($p->limit)->offset($p->offset)->asArray()->all();

        foreach ($list as $index => $value) {
            $list[$index]['video_time'] = TimeToDay::time($value['video_time']);
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'list' => $list
            ]
        ];
    }

}