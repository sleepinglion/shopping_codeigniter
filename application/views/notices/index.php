<div id="sl_notice_index" class="col-12">
	<div class="table-responsive">
		<table class="table table-striped" border="0" cellpadding="0" cellspacing="0">
			<colgroup>
				<col />
				<col />
				<col />
				<col />
				<col />
				<col />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th class="sl_t_id hidden-sm hidden-xs"><?php echo _('Id') ?></th>
					<th class="sl_t_title"><?php echo _('Title') ?></th>
					<!-- <th class="sl_t_count"><?php echo _('label_comment_count')?></th> -->
					<th class="sl_t_count"><?php echo _('View Count') ?></th>
					<th class="sl_t_created_at"><?php echo _('Created At') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($data['total']): ?>
				<?php foreach ($data['list'] as $index=>$value): ?>
				<tr>
					<td class="sl_t_id hidden-sm hidden-xs"><?php echo $value['id'] ?></td>
					<td class="sl_t_title"><?php echo sl_show_anchor('notices/'.$value['id'], $value['title']) ?></td>
					<!-- <td class="sl_t_count"></td> -->
					<td class="sl_t_count"><?php echo $value['count'] ?></td>
					<td class="sl_t_created_at"><time datetime="<?php echo sl_date($value['created_at'], 'w3c') ?>" pubdate><?php echo sl_date($value['created_at']) ?></time></td>
				</tr>
				<?php endforeach ?>
				<?php else: ?>
				<tr>
					<td colspan="4" class="no_data"><?php echo _('No Article') ?></td>
				</tr>
				<?php endif ?>
			</tbody>
		</table>
	</div>
	<div id="sl_index_bottom_menu">
		<?php echo $this -> pagination -> create_links(); ?>
		<?php echo $Layout -> element('search'); ?>
		<?php if ($this->session->userdata('admin')): ?>
			<?php echo anchor('/notices/add', '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>'._('new_link'), array('class'=>"btn btn-default col-xs-12 col-md-2")) ?>
		<?php endif ?>
	</div>
</div>
