<?php if($this->session->flashdata('message')): ?>
<?php $message=$this->session->flashdata('message') ?>
<div class="alert alert-<?php echo $message['type']  ?>" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo _('close') ?></span></button>
  <?php echo $message['message'] ; ?>
</div>
<?php endif ?>
