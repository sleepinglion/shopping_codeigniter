<section id="sl_question_add">
	<?php echo validation_errors('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>') ?>
	<?php echo $this->session->flashdata('error_message'); ?>
	<?php echo form_open(null,array('role'=>'form')) ?>
		<?php if(!$this->session->userdata('user_id')): ?>
  	<div class="form-group">
  		<label for="sl_name"><?php echo _('Name') ?></label>
  		<input type="name" class="form-control" id="sl_name" name="name" maxlength="60" required="required" />
  	</div>	
  	<div class="form-group">
  		<label for="sl_password"><?php echo _('Password') ?></label>
  		<input type="password" class="form-control" id="sl_password" name="password" maxlength="60" required="required" />
  	</div>
  	<?php endif ?>
  	<div class="form-group">
  		<label for="sl_title"><?php echo _('Title') ?></label>
  		<input type="text" class="form-control" id="sl_title" name="title" maxlength="60" required="required" />
  	</div>
  	<div class="form-group">
  		<label for="sl_secret"><?php echo _('Secret') ?></label>
  		<input type="checkbox" class="form-control" id="sl_secret" name="secret" maxlength="60" />
  	</div>
  	<div class="form-group">
  		<label for="sl_content"><?php echo _('Content') ?></label>
  		<textarea id="sl_content" name="content" class="form-control" required="required"></textarea>
  	</div>
  	<input type="submit" class="btn btn-primary" value="<?php echo _('Submit') ?>" />
	<?php echo form_close() ?>
</section>
