<?php
defined('YII_RUN') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.

 * Date: 2017/8/2
 * Time: 18:00
 */

$current_url = Yii::$app->request->absoluteUrl;
$key = 'addons/';
$we7_url = mb_substr($current_url, 0, stripos($current_url, $key));
?>
<div class="nav-right">
    <div class="btn-group">
        <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            系统
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="<?=$we7_url?>web/index.php?c=user&a=profile">我的账号</a>
            <a class="dropdown-item" href="<?=$we7_url?>web/index.php?c=user&a=logout">退出</a>
        </div>
    </div>
</div>