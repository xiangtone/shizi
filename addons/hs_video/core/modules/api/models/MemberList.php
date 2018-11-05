<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 16:58
 */

namespace app\modules\api\models;


use app\models\Level;
use app\models\Option;
use app\models\User;
use yii\data\Pagination;

class MemberList extends Model
{
    public $store_id;
    public $page;
    public $limit;


    public function rules()
    {
        return [
            [['page', 'limit'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 10],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $query = Level::find()->where(['store_id' => $this->store_id, 'is_delete' => 0]);

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query->limit($p->limit)->offset($p->offset)
            ->orderBy(['sort' => SORT_ASC, 'addtime' => SORT_DESC])->asArray()->all();
        foreach ($list as $index => $value) {
            $list[$index]['price'] = floatval($value['price']);
            $list[$index]['s_price'] = floatval($value['s_price']);
        }
        $user = User::findOne(['id' => \Yii::$app->user->identity->id, 'store_id' => $this->store_id]);
        return [
            'code' => 0,
            'msg' => '',
            'data' => [
                'list' => $list,
                'row_count' => $count,
                'page_count' => $p->pageCount,
                'content' => Option::get('content', $this->store_id, 'level', ''),
                'user_info' => (object)[
                    'access_token' => $user->access_token,
                    'nickname' => $user->nickname,
                    'avatar_url' => $user->avatar_url,
                    'id' => $user->id,
                    'is_comment' => $user->is_comment,
                    'is_clerk' => $user->is_clerk,
                    'is_member' => $user->is_member,
                    'due_time' => $user->due_time ? date('Y-m-d', $user->due_time) : 0,
                ]
            ]
        ];
    }
}