<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 11:35
 */

namespace app\modules\admin\models;

/**
 * @property \app\models\Banner $banner;
 */
class BannerForm extends Model
{
    public $store_id;

    public $banner;

    public $banner_url;
    public $introduce;
    public $sort;
    public $url;

    public function rules()
    {
        return [
            [['banner_url'],'required'],
            [['banner_url','introduce','sort','url'],'trim'],
            [['sort'],'integer','min'=>1],
            [['sort'],'default','value'=>100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'banner_url'=>'轮播图',
            'introduce'=>'介绍',
            'sort'=>'排序',
            'url'=>'链接'
        ];
    }

    public function save()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }

        if($this->banner->isNewRecord){
            $this->banner->is_delete = 0;
            $this->banner->addtime = time();
            $this->banner->store_id = $this->store_id;
        }
        $this->banner->banner_url = $this->banner_url;
        $this->banner->introduce = $this->introduce;
        $this->banner->sort = $this->sort;
        $this->banner->url = $this->url;
        if($this->banner->save()){
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        }else{
            return $this->getModelError($this->banner);
        }

    }
}