<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 17:10
 */

namespace app\modules\admin\models;


use app\models\Option;

class HomeForm extends Model
{
    public $store_id;
    public $cat_show;
    public $advertisement_show;
    public $cat_text;
    public $cat_pic;
    public $video_text;
    public $video_pic;
    public $index_icon;
    public $top_icon;

    public function rules()
    {
        return [
            [['cat_text', 'video_text', 'cat_pic', 'video_pic', 'index_icon', 'top_icon'], 'trim'],
            [['cat_text', 'video_text', 'cat_pic', 'video_pic', 'index_icon', 'top_icon'], 'string'],
            [['cat_show','advertisement_show'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'cat_text' => '首页分类文字',
            'cat_pic' => '首页分类小图标',
            'video_text' => '首页精选文字',
            'video_pic' => '首页精选小图标',
            'index_icon' => '返回首页图标',
            'top_icon' => '返回顶部图标',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $list = [
            ['name' => 'cat_show', 'value' => $this->cat_show],
            ['name' => 'advertisement_show', 'value' => $this->advertisement_show],
            ['name' => 'cat_text', 'value' => $this->cat_text],
            ['name' => 'cat_pic', 'value' => $this->cat_pic],
            ['name' => 'video_text', 'value' => $this->video_text],
            ['name' => 'video_pic', 'value' => $this->video_pic],
            ['name' => 'index_icon', 'value' => $this->index_icon],
            ['name' => 'top_icon', 'value' => $this->top_icon],
        ];
        $res = Option::setList($list, $this->store_id, 'home');
        if ($res) {
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '失败'
            ];
        }
    }
}