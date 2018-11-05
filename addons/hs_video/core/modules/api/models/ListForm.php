<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9
 * Time: 18:12
 */

namespace app\modules\api\models;


use app\extensions\getInfo;
use app\extensions\TimeToDay;
use app\models\Banner;
use app\models\Advertisement;
use app\models\Cat;
use app\models\Collect;
use app\models\Comment;
use app\models\CommentType;
use app\models\Option;
use app\models\Order;
use app\models\Store;
use app\models\User;
use app\models\Video;
use app\models\VideoPay;
use yii\data\Pagination;

class ListForm extends Model
{
    public $store_id;
    public $user_id;


    public $limit;
    public $page;

    public function rules()
    {
        return [
            [['limit'], 'default', 'value' => 5],
            [['page'], 'default', 'value' => 0]
        ];
    }

    public function search()
    {
        $store = Store::findOne(['id' => $this->store_id]);
        if (!$store) {
            return [
                'code' => 1,
                'msg' => 'Store不存在'
            ];
        }

        $banner_list = Banner::find()->select([
            'id', 'introduce', 'banner_url','url'
        ])->where([
            'store_id' => $this->store_id,
            'is_delete' => 0,
        ])->orderBy('sort ASC')->asArray()->all();
        if(!$banner_list){
            $banner_list = Video::find()->where([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'is_show'=>1,
            ])->select([
                'id','introduce','banner_url'
            ])->orderBy('sort ASC')->asArray()->all();
        }

        $cat_list = Cat::find()->select([
            'id', 'name', 'cover_url'
        ])->where([
            'is_delete' => 0,
            'store_id' => $this->store_id,'is_show'=>1
        ])->orderBy(['sort'=>SORT_ASC])->asArray()->all();

        $home = Option::getGroup($this->store_id,'home');
        // 广告位
        $advertisement = Advertisement::find()->where([
            'store_id' => $this->store_id
        ])->asArray()->one();
        
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'banner_list' => $banner_list,
                'cat_list' => $cat_list,
                'home'=>$home,
                'advertisement'=>$advertisement
            ]
        ];
    }

    public function getList()
    {
        if (!$this->validate())
            return $this->getModelError();
        $query = Video::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'status'=>0
        ]);


        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page]);
        $list = $query->select([
            '*'
        ])->orderBy(['sort'=>SORT_ASC,'addtime' => SORT_DESC])->limit($p->limit)->offset($p->offset)->asArray()->all();
        $user = User::findOne(['id'=>$this->user_id]);
        foreach ($list as $index => $value) {
            $list[$index]['show'] = -1;
            $exit = Collect::find()->where([
                'store_id' => $this->store_id, 'user_id' => $this->user_id, 'video_id' => $value['id'], 'is_delete' => 0
            ])->exists();
            if ($exit) {
                $list[$index]['collect'] = 0;
            } else {
                $list[$index]['collect'] = 1;
            }
            $count = Collect::find()->where([
                'store_id' => $this->store_id, 'video_id' => $value['id'], 'is_delete' => 0
            ])->count();
            $list[$index]['collect_count'] = $count;

            $comment_count = Comment::find()->where([
                'store_id' => $this->store_id, 'video_id' => $value['id'],
                'is_delete' => 0,
            ])->count();
            $list[$index]['comment_count'] = $comment_count;

            $list[$index]['video_time'] = TimeToDay::time($value['video_time']);
            $list[$index]['page_view'] = TimeToDay::getPageView($value['page_view']);
            if($value['type'] == 1){//腾讯视频解析
                $res = getInfo::getVideoInfo($value['video_url']);
                $list[$index]['video_url'] = $res['url'];
            }
            $order = Order::find()->where([
                'store_id'=>$this->store_id,'type'=>1,'is_pay'=>1, 'video_id'=>$value['id'],
                'user_id'=>$this->user_id,'is_delete'=>0
            ])->exists();
            if($order){
                $list[$index]['is_pay'] = 0;
            }
            if($user->is_member == 1){
                $list[$index]['is_pay'] = 0;
            }
            $video_pay = VideoPay::find()->where(['video_id'=>$value['id'],'type'=>0])->asArray()->one();
            if($video_pay && $value['is_pay'] == 1){
                $video_pay['d_time'] = TimeToDay::date($video_pay['time']);
                $list[$index]['pay'] = $video_pay;
            }else{
                $list[$index]['pay'] = null;
            }
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'list' => $list,
                'pagination' => $p,
                'row_count' => $count
            ]
        ];
    }
}