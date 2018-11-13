<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/22
 * Time: 9:58
 */

namespace app\behaviors;


use app\models\User;
use luweiss\wechat\Wechat;
use yii\base\Behavior;
use yii\web\Controller;

class OrderBehavior extends Behavior
{
    public $store;


    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    public function beforeAction($event)
    {
        try{
            $this->checkMember($event);//判断会员是否到期
        }catch(\Exception $e){

        }
    }

    /*
     * @param $event
     * 判断会员是否到期
     */
    private function checkMember($event)
    {
        $user_max = 100;
        $cache_key = 'check_member_time';
        if (\Yii::$app->cache->get($cache_key))
            return true;
        \Yii::$app->cache->set($cache_key, true, 10);

        /** @var Wechat $wechat */
        $wechat = isset($event->action->controller->wechat) ? $event->action->controller->wechat : null;
        if (!$wechat) {
            \Yii::$app->cache->set($cache_key, false);
            return true;
        }
        /** @var User[] $user_list */
        $user_list = User::find()->where([
            'and',
            'is_member'=>1,
            ['<=','due_time',time()]
        ])->limit($user_max)->all();
        foreach($user_list as $index=>$user){
            $user->is_member = 0;
            $user->due_time = 0;
            $user->save(false);
        }

        \Yii::$app->cache->set($cache_key, false);
        return true;
    }
}