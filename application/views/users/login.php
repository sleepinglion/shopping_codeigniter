<?php echo $Layout->element('form_error_message'); ?>	
<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'sl_login_form', 'role' => 'form')); ?>
	<?php if ($this->input->get('redirect')): ?>
	<input type="hidden" name="redirect_url" value="<?php echo $_SERVER['HTTP_REFERER']; ?>"  />
	<?php endif; ?>
	<div class="form-group row">
		<label for="sl_email" class="col-sm-2 col-form-label text-right"><?php echo _('Email'); ?></label>
		<div class="col-sm-10">
			<input type="text" class="form-control form-control-lg" id="sl_email" name="email" value="<?php echo set_value('email'); ?>" <?php if (ENVIRONMENT == 'production'): ?>required="required"<?php endif; ?> />
		</div>
	</div>
	<div class="form-group row">
		<label for="sl_password" class="col-sm-2 col-form-label text-right"><?php echo _('Password'); ?></label>
		<div class="col-sm-10">
			<input type="password" class="form-control form-control-lg" id="sl_password" name="password" value="<?php echo set_value('password'); ?>" <?php if (ENVIRONMENT == 'production'): ?>required="required"<?php endif; ?> />
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-offset-2 col-sm-10">
			<div class="form-check">
				<input type="checkbox" class="form-check-input" id="remember_email">
				<label class="form-check-label" for="remember_email"><?php echo _('Remember Email'); ?></label>
			</div>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-12">
			<button type="submit" class="btn btn-primary btn-block btn-lg"><?php echo _('Login'); ?></button>
		</div>
	</div>
<?php echo form_close(); ?>
