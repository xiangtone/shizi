<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 11:48
 */

namespace app\modules\api\models;


use app\models\Comment;
use app\models\Video;
use app\models\User;
use yii\helpers\Html;

class CommentForm extends Model
{
    public $store_id;
    public $user_id;

    public $video_id;
    public $c_id;
    public $content;
    public $upload_img;

    public $form_id;


    public function rules()
    {
        return [
            [['video_id', 'c_id'], 'required'],
            [['content', 'upload_img','form_id'], 'string'],
            [['content', 'upload_img'], 'validateCheck'],
        ];
    }

    public function validateCheck($attr, $param)
    {
        $upload_img = json_decode($this->upload_img, true);
        if (!$this->content && $this->content != 0 && empty($upload_img)) {
            $this->addError("content", "请输入内容或上传一张图片");
        }
    }

    public function attributeLabels()
    {
        return [
            'content' => '内容',
            'upload_img' => '图片'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $is_comment = \Yii::$app->user->identity->is_comment;
        if($is_comment == 1){
            return [
                'code'=>2,
                'msg'=>'您已被管理员禁言！若要解除，请联系管理员'
            ];
        }
        $comment = new Comment();
        $top_id = 0;
        if ($this->c_id != 0) {
            $top = Comment::findOne(['id' => $this->c_id, 'store_id' => $this->store_id, 'is_delete' => 0]);
            if ($top->top_id != 0) {
                $top_id = $top->top_id;
            } else {
                $top_id = $top->id;
            }
        }
        $comment->is_delete = 0;
        $comment->addtime = time();
        $comment->store_id = $this->store_id;
        $comment->user_id = $this->user_id;
        $comment->c_id = $this->c_id;
        $comment->top_id = $top_id;
        $comment->content = Html::encode($this->content);
        $comment->content = $this->userTextEncode($comment->content);
//        $comment->content = preg_replace('/[\xf0-\xf7].{3}/', '', $comment->content);
//        $comment->content = mb_convert_encoding($comment->content, 'UTF-8');
        $comment->video_id = $this->video_id;
        $comment->upload_img = $this->upload_img;
        if ($comment->save()) {
            $video = Video::findOne(['id' => $this->video_id]);
            $video->comment_count = $video->comment_count +1;
            $video->save();
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            $this->getModelError($comment);
        }
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


    public function send()
    {
        $user = User::findOne(['id'=>$this->user_id,'store_id'=>$this->store_id]);
        $access_token = $this->wechat->getAccessToken();
        $api = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
        $data = (object)[
            'touser' => $user->wechat_open_id,
            'template_id' => '',
            'form_id' => $this->form_id,
            'page' => 'pages/order/order?status=2',
            'data' => (object)[
                'keyword1' => (object)[
                    'value' => 'ceshi',
                    'color' => '#333333',
                ],
                'keyword2' => (object)[
                    'value' => '测试',
                    'color' => '#333333',
                ],
            ],
        ];
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $wechat = $this->getWechat();
        $wechat->curl->post($api, $data);
        $res = json_decode($wechat->curl->response, true);
        if (!empty($res['errcode']) && $res['errcode'] != 0) {
            \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($res, JSON_UNESCAPED_UNICODE));
        }
    }
}