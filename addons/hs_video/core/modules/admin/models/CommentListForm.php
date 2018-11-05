<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 18:02
 */

namespace app\modules\admin\models;


use app\models\Comment;
use app\models\CommentType;
use app\models\User;
use app\models\Video;
use app\modules\api\models\Model;
use yii\data\Pagination;

class CommentListForm extends Model
{
    public $store_id;

    public $page;
    public $limit;
    public $keyword;


    public function rules()
    {
        return [
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 20],
            [['keyword'], 'trim'],
            [['keyword'], 'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $query = Comment::find()->alias('c')->where(['c.store_id' => $this->store_id, 'c.is_delete' => 0])
            ->leftJoin(User::tableName() . ' u', 'u.id=c.user_id')
            ->leftJoin(Video::tableName() . ' v', 'v.id=c.video_id')
            ->leftJoin(CommentType::tableName() . ' ct', 'ct.c_id=c.id');

        if ($this->keyword || $this->keyword == 0) {
            $query->andWhere(['like', 'u.nickname', $this->keyword]);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $list = $query->select([
            'c.*', 'u.nickname', 'v.title',
            'sum(case when ct.is_delete = 0 and ct.type = 0 then 1 else 0 end) thump_count',
        ])->limit($p->limit)->offset($p->offset)->groupBy('c.id')->orderBy(['c.addtime' => SORT_DESC])->asArray()->all();
        foreach ($list as $index => $value) {
            $list[$index]['img'] = json_decode($value['upload_img'], true);
            $thump_count = CommentType::find()->where([
                'store_id' => $this->store_id, 'is_delete' => 0, 'c_id' => $value['id'], 'type' => 0
            ])->count();
            $list[$index]['thump_count'] = $thump_count;
            $list[$index]['content'] = $this->userTextDecode($value['content']);
        }
        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count
        ];
    }
    /**
    把用户输入的文本转义（主要针对特殊符号和emoji表情）
     */
    public static function userTextEncode($str){
        if(!is_string($str))return $str;
        if(!$str || $str=='undefined')return '';

        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
//            var_dump(addslashes($str[0]));
            return addslashes($str[0]);
        },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
        return json_decode($text);
    }
    /**
    解码上面的转义
     */
    public static function userTextDecode($str){
        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback('/\\\\\\\\/i',function($str){
            return '\\';
        },$text); //将两条斜杠变成一条，其他不动
        return json_decode($text);
    }
}