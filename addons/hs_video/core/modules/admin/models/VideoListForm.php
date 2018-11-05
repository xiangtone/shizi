<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/30
 * Time: 13:54
 */

namespace app\modules\admin\models;


use app\models\Cat;
use app\models\Collect;
use app\models\Video;
use yii\data\Pagination;
use app\extensions\TimeToDay;

class VideoListForm extends Model
{
    public $limit;
    public $page;
    public $store_id;

    public $cat_id;
    public $keyword;


    public function rules()
    {
        return [
            [['limit', 'page', 'store_id', 'cat_id'], 'integer'],
            [['limit'], 'default', 'value' => 10],
            [['keyword'], 'trim'],
            [['keyword'], 'string']
        ];
    }

    public function getList()
    {
        $query = Video::find()->alias('v')->where(['v.is_delete' => 0, 'v.store_id' => $this->store_id])
            ->leftJoin(Cat::tableName() . ' c', 'c.id=v.cat_id');
        if ($this->cat_id) {
            $query->andWhere(['v.cat_id' => $this->cat_id]);
        }
        if ($this->keyword)
            $query->andWhere(['like', 'v.title', $this->keyword]);

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query->orderBy(['v.sort' => SORT_ASC, 'v.addtime' => SORT_DESC])->offset($p->offset)->limit($p->limit)
            ->select([
                'v.*', 'c.name'
            ])->asArray()->all();
        foreach ($list as $index => $value) {
            $list[$index]['collect_count'] = Collect::find()->where([
                'store_id' => $this->store_id, 'video_id' => $value['id'], 'is_delete' => 0
            ])->count();

            $list[$index]['video_time'] = TimeToDay::time($value['video_time']);
            $list[$index]['page_view'] = TimeToDay::getPageView($value['page_view']);
            $list[$index]['content_len'] = mb_strlen($value['content']);
        }
        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count
        ];
    }
}