<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21
 * Time: 10:41
 */

namespace app\modules\admin\models;


use app\models\Option;

class TplForm extends Model
{
    public $store_id;
    public $pay_tpl;
    public $refund_tpl;

    public function rules()
    {
        return [
            [['pay_tpl','refund_tpl'],'trim'],
            [['pay_tpl','refund_tpl'],'string'],
        ];
    }

    public function save()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }

        $res = Option::setList([
            [
                'name'=>'pay_tpl',
                'value'=>$this->pay_tpl
            ],
            [
                'name'=>'refund_tpl',
                'value'=>$this->refund_tpl
            ]
        ],$this->store_id,'tpl');
        if($res){
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        }else{
            return [
                'code'=>1,
                'msg'=>'异常'
            ];
        }

    }
}