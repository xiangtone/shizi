<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('system/user-setting-header', TEMPLATE_INCLUDEPATH)) : (include template('system/user-setting-header', TEMPLATE_INCLUDEPATH));?>
<form action="" method="post" class="we7-form">
	<?php  if($do == 'login') { ?>
	<div id="login">
		
		<div class="form-group">
			<label class="col-sm-2 control-label" style="text-align:left;">是否开启验证码</label>
			<div class="col-sm-8 form-control-static">
				<input type="radio" id="verifycode-1" name="verifycode" <?php  if($settings['verifycode'] == 1) { ?> checked="checked" <?php  } ?> value="1" />
				<label class="radio-inline" for="verifycode-1">
					是
				</label>
				<input type="radio" id="verifycode-0" name="verifycode" <?php  if($settings['verifycode'] == 0) { ?> checked="checked" <?php  } ?> value="0" />
				<label class="radio-inline" for="verifycode-0">
					否
				</label>
				<span class="help-block"> 开启后，用户登录需要输入图形验证码，验证通过后方可登录。</span>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" style="text-align:left;">是否开启手机登录</label>
			<div class="col-sm-8 form-control-static">
				<input type="radio" name="mobile_status" id="mobile_status_status-1" <?php  if($settings['mobile_status'] == 1) { ?> checked="checked" <?php  } ?> value="1" />
				<label class="radio-inline" for="mobile_status_status-1">
					是
				</label>
				<input type="radio" name="mobile_status" id="mobile_status_status-0" <?php  if($settings['mobile_status'] == 0) { ?> checked="checked" <?php  } ?> value="0" />
				<label class="radio-inline" for="mobile_status_status-0">
					否
				</label>
				<span class="help-block"> 开启后，用户可以通过已绑定的手机号和密码登录系统。</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" style="text-align:left;">默认登录方式</label>
			<div class="col-sm-8 form-control-static">
				<input type="radio" name="login_type" id = "login_type_status-0" <?php  if($settings['login_type'] == 0 || $settings['mobile_status'] == 0) { ?> checked="checked" <?php  } ?> value="0" />
				<label class="radio-inline" for="login_type_status-0">
					账号密码登录
				</label>
				<input type="radio" name="login_type" id = "login_type_status-1" <?php  if($settings['login_type'] == 1) { ?> checked="checked" <?php  } ?> <?php  if($settings['mobile_status'] == 0) { ?> disabled <?php  } ?>value="1" />
				<label class="radio-inline" for="login_type_status-1">
					手机登录
				</label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" style="text-align:left;">用户首页设置</label>
			<div class="col-sm-8">
				<select name="welcome_link" class="form-control">
					<option value="<?php echo WELCOME_DISPLAY_TYPE;?>" <?php  if($settings['welcome_link']==WELCOME_DISPLAY_TYPE) { ?>selected<?php  } ?>>用户欢迎页</option>
					<option value="<?php echo PLATFORM_DISPLAY_TYPE;?>" <?php  if($settings['welcome_link']==PLATFORM_DISPLAY_TYPE) { ?>selected<?php  } ?>>平台</option>
				</select>
				<div class="help-block">统一设置用户登录后跳转的页面，用户也可以自行设置，以用户设置的为准</div>
			</div>
		</div>
	</div>
	<?php  } ?>

	<?php  if($do == 'binding') { ?>
	<div id="binding">
	<div class="form-group">
		<label class="col-sm-2 control-label" style="text-align:left;">强制绑定信息</label>
		<div class="col-sm-8 form-control-static">
			<input type="radio" id="bind_status-0" name="bind" value="" <?php  if(empty($settings['bind'])) { ?>checked<?php  } ?>/>
			<label class="radio-inline" for="bind_status-0">
				无
			</label>
			<input type="radio" id="bind_status-1" name="bind" value="qq" <?php  if($settings['bind'] == 'qq') { ?>checked<?php  } ?>/>
			<label class="radio-inline" for="bind_status-1">
				qq
			</label>
			<input type="radio" id="bind_status-2" name="bind" value="wechat" <?php  if($settings['bind'] == 'wechat') { ?>checked<?php  } ?>/>
			<label class="radio-inline" for="bind_status-2">
				微信
			</label>
			<input type="radio" id="bind_status-3" name="bind" value="mobile" <?php  if($settings['bind'] == 'mobile') { ?>checked<?php  } ?>/>
			<label class="radio-inline" for="bind_status-3">
				手机号
			</label>
			<span class="help-block"> 选择后，用户通过用户名和密码登录后，必须绑定所选信息后才可以登录成功，并关联绑定。</span>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" style="text-align:left;">第三方登录绑定</label>
		<div class="col-sm-8 form-control-static">
			<input type="radio" name="oauth_bind" id="oauth_bind-1" value="1" <?php  if($settings['oauth_bind'] == 1) { ?>checked<?php  } ?> />
			<label class="radio-inline" for="oauth_bind-1">
				是
			</label>
			<input type="radio" name="oauth_bind" id="oauth_bind-0" value="0"  <?php  if($settings['oauth_bind'] == 0) { ?>checked<?php  } ?>/>
			<label class="radio-inline" for="oauth_bind-0">
				否
			</label>
			<div class="help-block">开启后，用户直接通过第三方帐号登录后，必须绑定已有用户名或新注册系统帐号。</div>
		</div>
	</div>


</div>
	<?php  } ?>
	<input type="submit" name="submit" value="提交" class="btn btn-primary" style="padding: 6px 50px;">
	<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
</form>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>