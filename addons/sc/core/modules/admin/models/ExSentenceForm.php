<?php

namespace app\modules\admin\models;

use Yii;
use app\models\ExSentence;
use yii\data\Pagination;
/**
 * This is the model class for table "{{%ex_sentence}}".
 *
 * @property integer $id
 * @property integer $video_id
 * @property string $segment1
 * @property string $segment2
 * @property string $segment3
 * @property string $segment4
 * @property string $voice_url
 */
class ExSentenceForm extends Model
{
    public $exSentence;
    public $keyword;
    public $limit;
    public $page;
    public $video_id;
    public $segment1;
    public $segment2;
    public $segment3;
    public $segment4;

    public $voice_url;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ex_sentence}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1],
            [['video_id'], 'integer'],
            [['segment1', 'segment2', 'segment3', 'segment4'], 'string', 'max' => 32],
            [['keyword'], 'trim'],
            [['keyword','voice_url'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'video_id' => Yii::t('app', 'Video ID'),
            'segment1' => Yii::t('app', 'Segment1'),
            'segment2' => Yii::t('app', 'Segment2'),
            'segment3' => Yii::t('app', 'Segment3'),
            'segment4' => Yii::t('app', 'Segment4'),
            'voice_url' => Yii::t('app', 'Voice Url'),
        ];
    }
    /**
     * 页面右上角的生字搜索
     * 和进入列表页面一起
     */
    public function search(){
        //不用校验先
        /*
        if (!$this->validate())
            return $this->getModelError();
        */
        //根据video_id查找生字练习
        $query = ExSentence::find()->where(['video_id' => $this->video_id]);
        //搜索关键字为空则不处理
        if(!empty($this->keyword)){
            //搜索条件成立
            $query->andWhere(['like', 'segment1', $this->keyword]);
        }
        
        //分页
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->offset($p->offset)->limit($p->limit)->asArray()->all();
        
        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count
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

        
        $this->exSentence->video_id         =  $this->video_id;
        $this->exSentence->segment1         =  $this->segment1;
        $this->exSentence->segment2         =  $this->segment2;
        $this->exSentence->segment3         =  $this->segment3;
        $this->exSentence->segment4         =  $this->segment4;
        $this->exSentence->voice_url        =  $this->voice_url;

        if ($this->exSentence->save()) {
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
        
        $exSentence = new ExSentence();
        $ret = $exSentence->findOne($id)->delete();

        //var_dump($ret);die();
        return ret;


    }
}
