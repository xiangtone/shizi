<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/11
 * Time: 17:04
 */

namespace app\modules\api\models;


use app\models\Search;
use app\models\Video;
use yii\data\Pagination;

class SearchForm extends Model
{
    public $store_id;

    public $keyword;
    public $limit;
    public $page;

    public function rules()
    {
        return [
            [['keyword'], 'trim'],
            [['keyword'], 'string'],
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function search()
    {
        if (!$this->validate())
            return $this->getModelError();
        if (!$this->keyword && $this->keyword != 0) {
            return [
                'code' => 1,
                'msg' => 'fail'
            ];
        }

        $query = Video::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'status' => 0
        ])->andWhere(['like', 'title', $this->keyword]);

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->select([
            'id', 'title', 'pic_url', 'video_time','style'
        ])->orderBy(['sort' => SORT_ASC,'addtime'=>SORT_DESC])->limit($p->limit)->offset($p->offset)->asArray()->all();
        $hot_search = new Search();
        $hot_search->store_id = $this->store_id;
        $hot_search->is_delete = 0;
        $hot_search->keyword = mb_substr($this->keyword, 0, 5, 'utf-8');
        $hot_search->user_id = \Yii::$app->user->identity->id || 0;
        $hot_search->addtime = time();
        $hot_search->save();

        $hot_list = Search::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => 0
        ])->select([
            'count(id) count', 'keyword'
        ])->groupBy('keyword')->orderBy(['count' => SORT_DESC])->limit(10)->asArray()->all();
        foreach ($list as $index => $value) {
            if ($value['video_time'] >= 3600) {
                $list[$index]['video_time'] = date('H:i:s', $value['video_time']);
            } else {
                $list[$index]['video_time'] = date('i:s', $value['video_time']);
            }
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'video_list' => $list,
                'hot_list' => $hot_list
            ]
        ];

    }
}