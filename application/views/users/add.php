<section id="sl_user_add_form" class="sl_user_form">
	<?php echo $Layout->element('form_error_message'); ?>
	<?php echo form_open_multipart('users/add', array('class' => 'form-horizontal', 'id' => 'user_form')); ?>
	<input type="hidden" id="message_required" value="<?php echo _('The %s field is required.'); ?>" />
	<input type="hidden" id="message_no_email" value="<?php printf(_('The %s field is required.'), _('Email')); ?>" />
	<input type="hidden" id="message_invalid_email" value="<?php printf(_('The %s field must contain a valid email address.'), _('Email')); ?>" />
	<input type="hidden" id="message_exists_email" value="<?php printf(_('%s field must contain a unique value.'), _('Email')); ?>" />
	<input type="hidden" id="message_available_email" value="<?php echo _('Available Email'); ?>" />
	
	
	<div class="card">
  <div class="card-header">
    <ul class="nav nav-pills card-header-pills">
      <li class="nav-item">
        <a class="nav-link<?php if ($this->session->userdata('user_open')): ?><?php if ($this->session->userdata('user_open') == 'default'): ?> active<?php endif; ?><?php else: ?><?php if (empty($data['content'])): ?> active<?php endif; ?><?php endif; ?>" href="#"><?php echo _('Default Info'); ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link<?php if ($this->session->userdata('user_open')): ?><?php if ($this->session->userdata('user_open') == 'detail'): ?> active<?php endif; ?><?php endif; ?>"  href="#"><?php echo _('Detail Info'); ?></a>
      </li>
    </ul>
    <div class="float-right buttons">
      <i class="material-icons"><?php if ($this->session->userdata('user_open')): ?>keyboard_arrow_up<?php else:?><?php if (empty($data['content'])): ?>keyboard_arrow_up<?php else: ?>keyboard_arrow_down<?php endif; ?><?php endif; ?></i>
    </div>
  </div>
  <div class="card-body" <?php if (!$this->session->userdata('user_open') and !empty($data['content'])): ?> style="display:none"<?php endif; ?>>
    <article class="card-block"<?php if ($this->session->userdata('user_open')): ?><?php if ($this->session->userdata('user_open') == 'detail'): ?> style="display:none"<?php endif; ?><?php endif; ?>>
		<h3><?php echo _('User Data Default Insert'); ?></h3>
		<div class="form-group row <?php if (form_error('email')) {
    echo ' has-error';
} ?>">
			<label class="col-form-label col-sm-2" for="sl_email"><?php echo _('Email'); ?></label>
			<div class="col-sm-6">
					<input type="text" class="form-control" id="sl_email" name="email" maxlength="255" value="<?php echo set_value('email'); ?>" />
			</div>
			<div class="col-sm-4">
					<input type="button" id="check_email_available_button" class="form-control btn btn-success" value="<?php echo _('check_email_available'); ?>" />
			</div>
		</article>
		<div class="form-group row <?php if (form_error('password')) {
    echo ' has-error';
} ?>">
			<label class="col-form-label col-sm-2" for="sl_password"><?php echo _('Password'); ?></label>
			<div class="col-sm-10">
				<input type="password" class="form-control" id="sl_password" name="password" value="<?php echo set_value('password'); ?>" maxlength="255" <?php if (ENVIRONMENT == 'production'): ?>required="required"<?php endif; ?> />
			</div>
		</div>
		<div class="form-group row <?php if (form_error('password_confirm')) {
    echo ' has-error';
} ?>">
			<label class="col-form-label col-sm-2" for="sl_password_confirm"><?php echo _('Password Confirm'); ?></label>
			<div class="col-sm-10">
				<input type="password" class="form-control" id="sl_password_confirm" name="password_confirm" value="<?php echo set_value('password_confirm'); ?>" maxlength="255"   maxlength="255" <?php if (ENVIRONMENT == 'production'): ?>required="required"<?php endif; ?> />
			</div>
		</div>
		<div class="form-group row <?php if (form_error('name')) {
    echo ' has-error';
} ?>">
			<label class="col-form-label col-sm-2" for="sl_name"><?php echo _('Name'); ?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="sl_name" name="name" maxlength="60" value="<?php echo set_value('name'); ?>" maxlength="255"  />
			</div>
		</div>
		<div class="form-group row <?php if (form_error('phone')) {
    echo ' has-error';
} ?>">
			<label class="col-form-label col-sm-2" for="sl_phone"><?php echo _('Phone'); ?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="sl_phone" name="phone" value="<?php echo set_value('phone'); ?>" maxlength="255"   maxlength="255" <?php if (ENVIRONMENT == 'production'): ?>required="required"<?php endif; ?> />
			</div>
		</div>
		<div class="form-group row <?php if (form_error('birthday')) {
    echo ' has-error';
} ?>">
			<label class="col-form-label col-sm-2" for="sl_birthday"><?php echo _('Birthday'); ?></label>
			<div class="col-sm-10">
				<input type="date" class="form-control datepicker" data-date-end-date="-6570d" id="sl_birthday" name="birthday" value="<?php echo set_value('birthday'); ?>" <?php if (ENVIRONMENT == 'production'): ?>required="required"<?php endif; ?> />
			</div>
		</div>
		<div class="radio">
			<label class="col-form-label col-sm-2" for="sl_sex"><?php echo _('Sex'); ?></label>
			<div class="col-sm-10<?php if (form_error('sex')) {
    echo ' has-error';
} ?>">
			<label class="radio-inline">
				<input type="radio" name="sex" value="1" <?php echo set_radio('sex', 1, true); ?> /> <?php echo _('Male'); ?>
			</label>
			<label class="radio-inline">
				<input type="radio" name="sex" value="0" <?php echo set_radio('sex', 0); ?> /> <?php echo _('Female'); ?>
			</label>
			</div>
		</div>
</article>
<article class="card-block" <?php if ($this->session->userdata('user_open')): ?><?php if ($this->session->userdata('user_open') != 'detail'): ?>style="display:none"<?php endif; ?><?php else: ?>style="display:none"<?php endif; ?>>
		<h3><?php echo _('User Data Optional Insert'); ?></h3>
		<div class="form-group row <?php if (form_error('height')) {
    echo ' has-error';
} ?>">
			<label class="col-form-label col-sm-2" for="sl_height"><?php echo _('Height'); ?></label>
			<div class="col-sm-10">
				<input type="text" min="50" class="form-control" id="sl_height" name="height" value="<?php echo set_value('height'); ?>" />
			</div>
		<div class="form-group row <?php if (form_error('weight')) {
    echo ' has-error';
} ?>">
			<label class="col-form-label col-sm-2" for="sl_weight"><?php echo _('Weight'); ?></label>
			<div class="col-sm-10">
				<input type="text" min="30" class="form-control" id="sl_weight" name="weight" value="<?php echo set_value('weight'); ?>" />
			</div>
		</div>
		</div>
  	</article>
	<article class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo _('User Agree'); ?></h3>
		</div>
		<div class="panel-body">
		<div class="col-md-offset-2<?php if (form_error('agree[service]')) {
    echo ' has-error';
} ?>">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="agree[service]" value="1" <?php echo set_checkbox('agree[service]', '1', false); ?> /> <span><?php echo _('Service Agreement'); ?></span> <?php echo anchor('users/see_agree_service', _('See It'), array('data-target' => '#myModal', 'data-toggle' => 'modal', 'class' => 'modal_link')); ?>
				</label>
			</div>
		</div>
		<div class="col-md-offset-2<?php if (form_error('agree[information]')) {
    echo ' has-error';
} ?>">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="agree[information]" value="1" <?php echo set_checkbox('agree[information]', '1', false); ?> /> <span><?php echo _('Personal Information Agreement'); ?></span> <?php echo anchor('users/see_agree_information', _('See It'), array('data-target' => '#myModal', 'data-toggle' => 'modal', 'class' => 'modal_link')); ?>
				</label>
			</div>
		</div>
	</article>
</div>
	<div class="section_bottom">
		<input type="submit" class="btn btn-primary btn-block btn-lg" value="<?php echo _('User Form Submit'); ?>" />
	</div>
	<?php echo form_close(); ?>
</section>
