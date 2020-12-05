<div id="sl_messages" class="col-12">
	<?php if(validation_errors() != false): ?>
	<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<?php echo validation_errors() ?>
	</div>
	<?php endif ?>
  <?php if($this->session->flashdata('message')): ?>
  <?php $message=$this->session->flashdata('message') ?>
  <div class="alert alert-<?php echo $message['type']  ?>" role="alert">
    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo _('Close') ?></span></button>
    <?php echo $message['message'] ; ?>
  </div>
  <?php endif ?>
  <?php if($this->session->flashdata('swal-enable')): ?>
  <input type="hidden" id="swal-enable" value="1">
  <?php endif ?>
</div>