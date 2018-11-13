<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 15:34
 */

namespace app\modules\admin\models;
use app\models\Option;

/**
 * @property \app\models\Level $level
 */
class LevelForm extends Model
{
    public $store_id;
    public $level;

    public $title;
    public $price;
    public $s_price;
    public $date;
    public $is_groom;
    public $sort;
    public $content;

    public function rules()
    {
        return [
            [['title', 'price', 's_price', 'date', 'is_groom'], 'required', 'on' => 'edit'],
            [['title', 'content'], 'string'],
            [['title', 'content'], 'trim'],
            [['price', 's_price'], 'number', 'min' => 0.01],
            [['date', 'sort'], 'integer','min'=>1],
            [['is_groom'], 'integer'],
            [['sort'], 'default', 'value' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => '会员名称',
            'price' => '会员价格',
            's_price' => '续费价格',
            'date' => '会员时间',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        if ($this->level->isNewRecord) {
            $this->level->is_delete = 0;
            $this->level->addtime = time();
        }
        $this->level->store_id = $this->store_id;
        $this->level->attributes = $this->attributes;
        if ($this->level->save()) {
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return $this->getModelError($this->level);
        }

    }

    public function saveContent()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        Option::set('content',$this->content, $this->store_id, 'level');
        return [
            'code' => 0,
            'msg' => ''
        ];
    }
}