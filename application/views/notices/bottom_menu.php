<div class="sl_view_bottom_menu">
	<div class="pull-left">
		<?php echo sl_index_anchor('/notices','<span class="glyphicon glyphicon-list" aria-hidden="true"></span> '._('index_link'),array('class'=>"btn btn-default")) ?>
	</div>
	<?php if($this->session->userdata('admin')): ?>
	<div class="pull-right">
	<?php echo anchor('/notices/confirm_delete/'.$data['content']['id'],'<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'._('delete_link'),array('class'=>"pull-right btn btn-default")) ?>
	<?php echo anchor('/notices/edit/'.$data['content']['id'],'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>'._('edit_link'),array('class'=>"pull-right btn btn-default"))	?>
  </div>
	<?php endif ?>
</div>