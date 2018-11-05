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

    public $store_id;
    public $name;
    public $pic_url;
    public $sort;
    public $is_show;
    public $cover_url;

    public function rules()
    {
        return [
            [['name','pic_url','cover_url','sort'],'trim'],
            [['name','pic_url'],'required'],
            [['sort'],'default','value'=>100],
            [['sort'],'integer','min'=>1],
            [['is_show'],'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'分类名称',
            'pic_url'=>'封面图片',
            'sort'=>'分类排序'
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
        $this->cat->name = $this->name;
        $this->cat->pic_url = $this->pic_url;
        $this->cat->sort = $this->sort;
        $this->cat->is_show = $this->is_show;
        $this->cat->cover_url = $this->cover_url;
        if($this->is_show == 1 && !$this->cover_url){
            return [
                'code'=>1,
                'msg'=>'请上传首页缩略图'
            ];
        }
        if($this->cat->save()){
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        }else{
            return $this->getModelError($this->cat);
        }
    }
}