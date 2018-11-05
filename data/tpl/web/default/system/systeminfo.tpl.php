<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="we7-page-title">系统信息</div>
<ul class="we7-page-tab"></ul>
<div class="main">

	<table class="table we7-table table-hover site-list" id="system-info" ng-controller="systemInfoCtrl" ng-cloak>
		<tr>
			<th colspan="2" class="text-left">系统信息</th>
		</tr>
		<tr>
			<td class="text-left">系统程序版本</td>
			<td class="text-left">WeEngine <?php  echo IMS_VERSION;?> Release <?php  echo IMS_RELEASE_DATE;?> &nbsp; &nbsp;

			</td>
		</tr>
		<tr>
			<td class="text-left">产品系列</td>
			<td class="text-left">
				<?php  if(IMS_FAMILY == 'v') { ?>
				您的产品是开源版, 没有购买商业授权, 不能用于商业用途
				<?php  } else if(IMS_FAMILY == 's') { ?>
				您的产品是商业版
				<?php  } else if(IMS_FAMILY == 'x') { ?>
				您的产品是商业版
				<?php  } else { ?>
				您的产品是商业版
				<?php  } ?>
			</td>
		</tr>
		<tr>
			<td class="text-left">服务器系统</td>
			<td class="text-left"><?php  echo $info['os'];?></td>
		</tr>
		<tr>
			<td class="text-left">PHP版本 </td>
			<td class="text-left">PHP Version <?php  echo $info['php'];?></td>
		</tr>
		<tr>
			<td class="text-left">服务器软件</td>
			<td class="text-left"><?php  echo $info['sapi'];?></td>
		</tr>
		<tr>
			<td class="text-left">服务器 MySQL 版本</td>
			<td class="text-left"><?php  echo $info['mysql']['version'];?></td>
		</tr>
		<tr>
			<td class="text-left">上传许可</td>
			<td class="text-left"><?php  echo $info['limit'];?></td>
		</tr>
		<tr>
			<td class="text-left">当前数据库尺寸</td>
			<td class="text-left"><?php  echo $info['mysql']['size'];?></td>
		</tr>
		<tr>
			<td class="text-left">当前附件根目录</td>
			<td class="text-left"><?php  echo $info['attach']['url'];?></td>
		</tr>
		<tr>
			<td class="text-left">当前附件尺寸</td>
			<td class="text-left"><?php  echo $info['attach']['size'];?></td>
		</tr>
	</table>

	<?php  if($_W['isfounder']) { ?>
	<table class="table we7-table table-hover site-list">
		<col width="150px" />
		<col width="" />
		<th colspan="2" class="text-left">系统开发团队</th>
		<tr>
			<td class="text-left">资源邦</td>
			<td>
				<a href="http://www.wazyb.com" target="_blank" style="color: #428bca;"><b>资源邦</b></a>
			</td>
		</tr>
		<tr>
			</td>
		</tr>
		<tr>
			<td class="text-left">联系客服</td>
			<td>
				<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=993424780&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:993424780:51" alt="资源邦" title="资源邦"/></a>
			</td>
		</tr>
		<tr>
			<td class="text-left">相关链接</td>
			<td>
				<a href="http://www.wazyb.com/" class="lightlink2" target="_blank" style="color: #428bca;">公司网站</a>&nbsp;&nbsp;
				<a href="http://www.wazyb.com" class="lightlink2" target="_blank" style="color: #428bca;">购买授权</a>&nbsp;&nbsp;
				<a href="http://www.wazyb.com/" class="lightlink2" target="_blank" style="color: #428bca;">更多模块</a>&nbsp;&nbsp;
				<a href="http://www.kancloud.cn/donknap/we7/136556" class="lightlink2" target="_blank" style="color: #428bca;">文档</a>&nbsp;&nbsp;
				<a href="http://www.wazyb.com/" class="lightlink2" target="_blank" style="color: #428bca;">讨论区</a>
			</td>
		</tr>
	</table>
	<?php  } ?>
	<script type="text/javascript">
        angular.module('systemApp').value('config', {
            'get_attach_size_url' : "<?php  echo url('system/systeminfo', array('do' => 'get_attach_size'))?>"
        });
        angular.bootstrap($('#system-info'), ['systemApp']);
	</script>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
