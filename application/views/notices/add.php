<section id="sl_notice_add">
	<?php echo validation_errors() ?>	
	<?php echo form_open(null,array('role'=>'form')) ?>
  	<div class="form-group">
  		<label for="sl_title"><?php echo _('Title') ?></label>
  		<input type="text" class="form-control" id="sl_title" name="title" maxlength="60" required="required" />
  	</div>	
  	<div class="form-group">
  		<label for="sl_content"><?php echo _('Content') ?></label>
  		<textarea id="sl_content" name="content" class="form-control" required="required"></textarea>
  	</div>
  	<input type="submit" class="btn btn-primary" value="<?php echo _('Submit') ?>" />
	<?php echo form_close() ?>
</section>
