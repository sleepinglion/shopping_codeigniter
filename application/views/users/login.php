<?php echo $Layout->element('form_error_message') ?>	
<?php echo form_open('',array('class'=>"form-horizontal",'id'=>"sl_login_form",'role'=>"form" )) ?>
	<?php if($this->input->get('redirect')): ?>
	<input type="hidden" name="redirect_url" value="<?php echo $_SERVER['HTTP_REFERER'] ?>"  />
	<?php endif ?>
	<div class="form-group">
		<label for="sl_email" class="col-sm-2 control-label"><?php echo _('Email') ?></label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="sl_email" name="email" value="<?php echo set_value('email'); ?>" <?php /*required="required" */ ?> />
		</div>
	</div>
	<div class="form-group">
		<label for="sl_password" class="col-sm-2 control-label"><?php echo _('Password') ?></label>
		<div class="col-sm-10">
			<input type="password" class="form-control" id="sl_password" name="password" value="<?php echo set_value('password'); ?>" <?php /*required="required" */ ?> />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<div class="checkbox">
				<label>
					<input type="checkbox" id="remember_email"><?php echo _('Remember Email') ?>
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-primary"><?php echo _('Login') ?></button>
		</div>
	</div>
<?php echo form_close() ?>
