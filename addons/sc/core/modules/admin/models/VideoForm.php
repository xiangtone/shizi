<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/30
 * Time: 11:35
 */

namespace app\modules\admin\models;

use app\models\Cat;
use app\models\Form;

/**
 * @property \app\models\Video $video
 * @property \app\models\VideoPay $pay
 */
class VideoForm extends Model
{
    public $video;
    public $store_id;
    public $pay;

    public $cat_id;
    public $title;
    public $pic_url;
    public $video_url;
    public $video_720;
    public $video_1080;
    public $content;
    public $sort;
    public $is_show;
    public $introduce;
    public $banner_url;
    public $video_time;
    public $detail;
    public $form_list;
    public $order;
    public $money;
    public $refund;
    public $page_view;
    public $type;
    public $style;

    public $is_pay;
    //pay_ 为前缀的表示视频的付费设置
    public $pay_price;
    public $pay_time;
    public $form_name;

    public function rules()
    {
        return [
            [['video_url'], 'required', 'on' => 'VIDEO'],
            [['pay_time'], 'integer', 'on' => 'VIDEO'],
            [['cat_id', 'title', 'pic_url', 'content'], 'required'],
            [['title', 'pic_url', 'video_url', 'video_720','video_1080', 'content', 'introduce', 'banner_url', 'form_list', 'form_name'], 'trim'],
            [['title', 'pic_url', 'video_url', 'video_720','video_1080', 'content', 'introduce', 'banner_url', 'detail', 'form_name'], 'string'],
            [['sort', 'is_show', 'order', 'refund', 'page_view', 'type', 'style', 'is_pay'], 'integer'],
            [['sort'], 'default', 'value' => 100],
            [['video_time', 'money'], 'number', 'min' => 0],
            [['money', 'pay_time'], 'default', 'value' => 0],
            [['pay_price'], 'default', 'value' => 0.01],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cat_id' => '分类',
            'title' => '标题',
            'pic_url' => '封面图片',
            'video_url' => '多媒体链接',
            'content' => '简介',
            'sort' => '排序',
            'introduce' => '介绍',
            'banner_url' => '轮播图',
            'detail' => '图文详情',
            'money' => '预定金额',
            'page_view' => '浏览量',
            'type' => '视频来源',
            'is_pay' => '是否开启付费观看',
            'pay_price' => '付费金额',
            'pay_time' => '免费观看时长',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

        $cat = Cat::find()->where(['is_delete' => 0, 'id' => $this->cat_id])->one();
        if (!$cat) {
            return [
                'code' => 1,
                'msg' => '分类不存在',
            ];
        }

        if ($this->video->isNewRecord) {
            $this->video->is_delete = 0;
            $this->video->addtime = time();
            $this->video->status = 0;
            $this->video->store_id = $this->store_id;

            $cat->update_time = time();
            if (!$cat->save()) {
                return [
                    'code' => 1,
                    'msg' => '网络异常',
                ];
            }
        }
        //判断付费视频参数
        if ($this->is_pay == 1 && $this->style != 2) {
            // if ($this->pay_price < 0.01) {
            //     return [
            //         'code' => 1,
            //         'msg' => '付费金额不能小于0.01',
            //     ];
            // }
            if (floatval($this->pay_time) > floatval($this->video_time)) {
                return [
                    'code' => 1,
                    'msg' => '免费时长不能超过视频时长',
                ];
            }
        }
        $this->video->cat_id = $this->cat_id;
        $this->video->title = $this->title;
        $this->video->pic_url = $this->pic_url;
        $this->video->video_url = $this->video_url;
        $this->video->video_720 = $this->video_720;
        $this->video->video_1080 = $this->video_1080;
        $this->video->content = $this->content;
        $this->video->sort = $this->sort;
        $this->video->is_show = $this->is_show;
        $this->video->video_time = $this->video_time;
        $this->video->detail = $this->detail;
        $this->video->order = $this->order;
        $this->video->money = $this->money;
        $this->video->refund = $this->refund;
        $this->video->page_view = $this->page_view;
        $this->video->type = $this->type;
        $this->video->style = $this->style;
        if ($this->is_show == 1 && false) {
            if (!$this->banner_url) {
                return [
                    'code' => 1,
                    'msg' => '请上传轮播图',
                ];
            }
            $this->video->introduce = $this->introduce;
            $this->video->banner_url = $this->banner_url;
        }
        $this->video->is_pay = $this->is_pay;
        if ($this->video->style == 2) {
            $this->video->is_pay = 0;
        }
        if ($this->video->save()) {
            Form::updateAll(['is_delete' => 1], ['store_id' => $this->store_id, 'video_id' => $this->video->id]);
            if ($this->form_list) {
                foreach ($this->form_list as $index => $value) {
                    if (!$value['name']) {
                        return [
                            'code' => 1,
                            'msg' => '请输入字段名称',
                        ];
                    }
                    if ($value['id']) {
                        $form = Form::findOne(['store_id' => $this->store_id, 'id' => $value['id']]);
                    } else {
                        $form = new Form();
                    }
                    $form->is_delete = 0;
                    $form->addtime = time();
                    $form->type = $value['type'];
                    $form->name = $value['name'];
                    $form->default = $value['default'];
                    $form->required = $value['required'] ? $value['required'] : 0;
                    $form->tip = $value['tip'];
                    $form->sort = $index;
                    $form->store_id = $this->store_id;
                    $form->video_id = $this->video->id;
                    $form->save();
                }
            }
            if ($this->form_name) {
                $form_name = Form::findOne(['store_id' => $this->store_id, 'type' => 'form_name', 'video_id' => $this->video->id]);
                if (!$form_name) {
                    $form_name = new Form();
                    $form_name->type = 'form_name';
                    $form_name->store_id = $this->store_id;
                    $form_name->video_id = $this->video->id;
                }
                $form_name->is_delete = 0;
                $form_name->addtime = time();
                $form_name->name = $this->form_name;
                $form_name->save();
            }
            if ($this->is_pay == 1 && $this->video->style != 2) {
                if ($this->pay->isNewRecord) {
                    $this->pay->video_id = $this->video->id;
                    $this->pay->store_id = $this->store_id;
                }
                $this->pay->price = $this->pay_price;
                $this->pay->time = $this->pay_time;
                $this->pay->type = 0;
                $this->pay->save();
            }
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return $this->getModelError($this->video);
        }
    }
}
