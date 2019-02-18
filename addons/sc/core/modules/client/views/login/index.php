<?php
// use app\modules\school\assets\LoginAsset;

// LoginAsset::register($this);
//$this->registerCssFile('@web/css/login.css');
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
    <title>学懂汉字</title>
    
    <link href="<?=Yii::$app->request->baseUrl?>/css/login.css" rel="stylesheet">

    <script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>   
    <?php $this->head()?>
</head>
<?php $this->beginBody()?>
<body>
   <div id="login_box">
   <h1>微信用户扫码登录</h1>
       <div id='qrcodeArea' style="width:300px;height:250px;margin:auto;"></div>
       <!-- <img id='qrcodeArea' style="width:250px;height:250px;"> -->
   </div>
   <!-- Modal -->
<div class="modal fade" id="wxappModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">没有找到您的课程，请使用微信，扫描下面的圆形小程序二维码，点击登录，确认已经在我的课程中再使用。</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div style="width:200px;height:200px;margin:auto">
        <img src='http://qiniu.xuedonghanzi.com/uploads/image/78/78fdeabe475bf68319fd07460e489548.png' style="width:200px;height:200px;margin:auto">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
        
      </div>
    </div>
  </div>
</div>
</body>
<?php $this->endBody()?>
</html>
<?php $this->endPage()?>
<script>
function getUrlParam(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
	var r = window.location.search.substr(1).match(reg); //匹配目标参数
	if (r != null) return unescape(r[2]);
	return null; //返回参数值
}
window.onload = function(){
    var obj = new WxLogin({
        self_redirect:false,
        id:"qrcodeArea",
        appid: "<?php echo \Yii::$app->params['wxPcAppId'];?>",
        scope: "snsapi_login",
        redirect_uri: encodeURI("<?php echo \Yii::$app->params['wxPcBase'];?>addons/sc/core/web/index.php?r=client/login/accesstoken"),
        state: "state123",
        style: "",
        href: "https://<?php echo \Yii::$app->params['baseUrl'];?>/addons/sc/core/web/css/wxPcLogin.css"
        });
    if (getUrlParam('reason')=='noRecord'){
        $('#wxappModal').modal({show:true})
    }
}

</script>