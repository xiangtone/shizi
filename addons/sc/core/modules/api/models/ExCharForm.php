<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9
 * Time: 18:12
 */

namespace app\modules\api\models;

use app\models\ExChar;

class ExCharForm extends Model
{
    public $store_id;
    public $video_id;

    public function rules()
    {
        return [
            [['video_id'], 'integer'],
        ];
    }

    public function search()
    {
        $list = ExChar::find()->where(['video_id' => $this->video_id])->asArray()->all();
        if (!$list) {
            return [
                'code' => 1,
                'msg' => '记录不存在',
            ];
        } else {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'list' => $list,
                ],
            ];
        }
    }
}
