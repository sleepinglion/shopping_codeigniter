<div id="sl_question_edit">
	<?php echo validation_errors('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>') ?>
	<?php echo $this->session->flashdata('error_message'); ?>
	<?php echo form_open(null,array('role'=>'form')) ?>
  	<div class="form-group">
  		<label for="sl_title"><?php echo _('Title') ?></label>
  		<input type="text" class="form-control" id="sl_title" name="title" maxlength="60" required="required" />
  	</div>
  	<div class="form-group">
  		<label for="sl_content"><?php echo _('Content') ?></label>
  		<?php if($this->session->user_id('user_id')): ?>
  		<textarea id="sl_content" name="content" class="form-control" required="required"><?php echo $data['content']['content'] ?></textarea>
  		<?php else: ?>
  		<textarea id="sl_content" name="content" class="form-control" required="required"><?php echo nl2br($data['content']['content']) ?></textarea>
  		<?php endif ?>
  	</div>
  	<input type="submit" class="btn btn-primary" value="<?php echo _('Submit') ?>" />
	<?php echo form_close() ?>
</div>
