<?php

	if(empty($data['content']['weight'])) {
		$weight='';
	} else {
		$weight=$data['content']['weight'];
	} 

	if(empty($data['content']['height'])) {
		$height='';
	} else {
		$height=$data['content']['height'];
	}
?>
<section id="sl_user_edit_form" class="sl_user_form col-12">
	<?php echo form_open_multipart('users/edit', array('class' => 'form-horizontal','id'=>'user_form')) ?>
	<input type="hidden" id="message_required" value="<?php echo _('The %s field is required.') ?>" />
	<input type="hidden" id="message_min_length" value="<?php echo	_('The %s field must be at least %s characters in length.') ?>" />	
	<article class="card">
		<div class="card-body">
		<h3 class="hidden-title"><?php echo _('User Data Default Insert') ?></h3>
		<div class="form-group">
			<label class="control-label col-sm-2" for="sl_email"><?php echo _('Email') ?></label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $data['content']['email'] ?></p>
			</div>
		</div>
		<div class="form-group<?php if(form_error('password')){echo ' has-error';} ?>">
			<label class="control-label col-sm-2" for="sl_password"><?php echo _('Password') ?></label>
			<div class="col-sm-10">
				<input type="password" class="form-control" id="sl_password" name="password" value="<?php echo set_value('password'); ?>" maxlength="255" placeholder="<?php echo _('User Edit Password') ?>" <?php if(ENVIRONMENT == 'production'): ?>required="required"<?php endif ?> />
			</div>
		</div>
		<div class="form-group<?php if(form_error('password_confirm')){echo ' has-error';} ?>">
			<label class="control-label col-sm-2" for="sl_password_confirm"><?php echo _('Password Confirm') ?></label>
			<div class="col-sm-10">
				<input type="password" class="form-control" id="sl_password_confirm" name="password_confirm" value="<?php echo set_value('password_confirm'); ?>" maxlength="255" placeholder="<?php echo _('User Edit Password Confirm') ?>" maxlength="255" <?php if(ENVIRONMENT == 'production'): ?>required="required"<?php endif ?> />
			</div>
		</div>
		<div class="form-group<?php if(form_error('name')){echo ' has-error';} ?>">
			<label class="control-label col-sm-2" for="sl_name"><?php echo _('Name') ?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="sl_name" name="name" maxlength="60" value="<?php echo set_value('name',$data['content']['name']); ?>" maxlength="255"  />
			</div>
		</div>		
		<div class="form-group<?php if(form_error('phone')){echo ' has-error';} ?>">
			<label class="control-label col-sm-2" for="sl_phone"><?php echo _('Phone') ?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="sl_phone" name="phone" value="<?php echo set_value('phone',$data['content']['phone']); ?>" maxlength="255"   maxlength="255" <?php if(ENVIRONMENT == 'production'): ?>required="required"<?php endif ?> />
			</div>
		</div>
		<div class="form-group<?php if(form_error('birthday')){echo ' has-error';} ?>">
			<label class="control-label col-sm-2" for="sl_birthday"><?php echo _('Birthday') ?></label>
			<div class="col-sm-10">
				<input type="date" class="form-control datepicker" data-date-end-date="-6570d" id="sl_birthday" name="birthday" value="<?php echo set_value('birthday',$data['content']['birthday']); ?>" <?php if(ENVIRONMENT == 'production'): ?>required="required"<?php endif ?> />
			</div>			
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="sl_sex"><?php echo _('Sex') ?></label>
			<div class="col-sm-10">		
			<label class="radio-inline">
				<input type="radio" name="sex" value="1" <?php if(!empty($data['content']['sex'])): ?>checked="checked"<?php endif ?> /> <?php echo _('Male'); ?>
			</label>
			<label class="radio-inline">
				<input type="radio" name="sex" value="0" <?php if(empty($data['content']['sex'])): ?>checked="checked"<?php endif ?> /> <?php echo _('Female') ?>
			</label>
			</div>
		</div>
		</div>
  	</article>
	<div class="section_bottom">
		<input type="submit" class="btn btn-primary btn-block btn-lg" value="<?php echo _('User Form Edit Submit') ?>" />
	</div>
	<?php echo form_close() ?>		
</section>