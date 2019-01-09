<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29
 * Time: 14:46
 */

namespace app\modules\admin\models;

/**
 * @property \app\models\Cat $cat;
 */
class CatForm extends Model
{
    public $cat;
    public $cat_pay;

    public $store_id;
    public $name;
    public $pic_url;
    public $sort;
    public $is_show;
    public $is_display;
    public $cover_url;
    public $is_pay;
    public $pay_price;

    public function rules()
    {
        return [
            [['name','pic_url','cover_url','sort'],'trim'],
            [['name','pic_url'],'required'],
            [['sort'],'default','value'=>100],
            [['sort'],'integer','min'=>1],
            [['is_show','is_display','is_pay'],'integer'],
            [['pay_price'], 'default', 'value' => 0.01],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'分类名称',
            'pic_url'=>'封面图片',
            'sort'=>'分类排序',
            'pay_price' => '付费金额',
            'is_pay' => '是否开启付费观看',
        ];
    }


    public function save()
    {
        if(!$this->validate())
            return $this->getModelError();
        if($this->cat->isNewRecord){
            $this->cat->is_delete = 0;
            $this->cat->store_id = $this->store_id;
            $this->cat->addtime = time();
            $this->cat->update_time = time();
        }
        //判断付费视频参数
        if ($this->is_pay == 1) {
            if ($this->pay_price < 0) {
                return [
                    'code' => 1,
                    'msg' => '付费金额不能小于0',
                ];
            }
        }
        $this->cat->name = $this->name;
        $this->cat->pic_url = $this->pic_url;
        $this->cat->sort = $this->sort;
        $this->cat->is_show = $this->is_show;
        $this->cat->is_display = $this->is_display;
        $this->cat->cover_url = $this->cover_url;
        $this->cat->is_pay = $this->is_pay;
        if($this->is_show == 1 && !$this->cover_url){
            return [
                'code'=>1,
                'msg'=>'请上传首页缩略图'
            ];
        }
        if($this->cat->save()){
            //对zjhj_video_cat_pay进行操作
            if ($this->is_pay == 1) {
                if ($this->cat_pay->isNewRecord) {
                    $this->cat_pay->cat_id = $this->cat->id;
                    $this->cat_pay->store_id = $this->store_id;
                }
                $this->cat_pay->price = $this->pay_price;
                $this->cat_pay->type = 0;
                $this->cat_pay->save();
            }
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
            
        }else{
            return $this->getModelError($this->cat);
        }
    }
}