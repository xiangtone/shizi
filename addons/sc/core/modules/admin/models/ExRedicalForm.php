<?php

namespace app\modules\admin\models;

use yii\data\Pagination;
use app\models\ExRedical;

/**
 * This is the model class for table "{{%ex_word}}".
 *
 * @property integer $id
 * @property integer $video_id
 * @property string $new_word
 * @property string $target_word
 */
class ExRedicalForm extends Model
{
    public $exRedical;
    public $keyword;
    public $limit;
    public $page;
    public $video_id;
    public $new_word;
    public $redical_word;
    public $combine_word;
    public $combine_type;
    public $voice_url;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1],
            [['video_id'], 'integer'],
            [['new_word'], 'string', 'max' => 4],
            [['keyword','voice_url','new_word','redical_word','combine_type','combine_word'], 'trim'],
            [['keyword', 'voice_url'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'video_id' => 'Video ID',
            'new_word' => 'New Word',
            'voice_url' => 'Voice Url',
        ];
    }
    /**
     * 页面右上角的生字搜索
     * 和进入列表页面一起
     */
    public function search()
    {
        //不用校验先
        /*
        if (!$this->validate())
        return $this->getModelError();
         */
        //根据video_id查找生字练习
        $query = ExRedical::find()->where(['video_id' => $this->video_id]);
        //搜索关键字为空则不处理
        if (!empty($this->keyword)) {
            //搜索条件成立
            $query->andWhere(['like', 'new_word', $this->keyword]);
        }

        //分页
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->offset($p->offset)->limit($p->limit)->asArray()->all();

        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count,
        ];
    }
    /**
     * 保存
     */
    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

        $this->exRedical->video_id = $this->video_id;
        $this->exRedical->new_word = $this->new_word;
        $this->exRedical->redical_word = $this->redical_word;
        $this->exRedical->combine_word = $this->combine_word;
        $this->exRedical->combine_type = $this->combine_type;
        $this->exRedical->voice_url = $this->voice_url;

        if ($this->exRedical->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return $this->getModelError($this->classes);
        }
    }
    /*
     * 删除
     */
    public function del($id)
    {

        $item = new ExRedical();
        $ret = $item->findOne($id)->delete();

        //var_dump($ret);die();
        return $ret;

    }

}
