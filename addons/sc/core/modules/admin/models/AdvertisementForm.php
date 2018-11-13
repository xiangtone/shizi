<?php

namespace app\modules\admin\models;

class AdvertisementForm extends Model
{
    public $advertisement;
    public $title;
    public $pic;
    public $appid;
    public $path;
    public $store_id;

    public function rules()
    {
        return [
            [['title'],'trim'],
            [['title','appid','pic','path'],'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'=>'广告标题',
            'pic'=>'封面图片',
            'appid'=>'要打开的小程序appid',
            'path'=>'小程序路径',
        ];
    }


    public function save()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }

        if($this->advertisement->isNewRecord){
            $this->advertisement->created_at = time();
            $this->advertisement->store_id = $this->store_id;
        }
        if($this->pic){
            if(!$this->appid){
                return [
                    'code'=>'1',
                    'msg'=>'appid不能为空'
                ];
            }
        }
        $this->advertisement->title = $this->title;
        $this->advertisement->pic = $this->pic;
        $this->advertisement->appid = $this->appid;
        $this->advertisement->path = $this->path;
        $this->advertisement->created_at = time();

        if($this->advertisement->save()){
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        }else{
            return $this->getModelError($this->advertisement);
        }
    }
}