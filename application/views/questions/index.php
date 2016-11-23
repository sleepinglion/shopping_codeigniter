<div id="sl_question_index">
	<article class="table-responsive">
		<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped">
			<colgroup>
				<col class="sl_t_id" />
				<col class="sl_t_title" />
				<col class="sl_t_count" />
				<col class="sl_t_count" />
				<col class="sl_t_created_at" />
			</colgroup>
			<thead>
				<tr>
					<th class="sl_t_id hidden-sm hidden-xs"><?php echo _('Name') ?></th>
					<th class="sl_t_title"><?php echo _('Title') ?></th>
					<th class="sl_t_count"><?php echo _('Answer')?></th>
					<th class="sl_t_count"><?php echo _('View Count') ?></th>
					<th class="sl_t_created_at"><?php echo _('Created At') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if($data['total']): ?>
				<?php foreach($data['list'] as $index=>$value): ?>
				<tr>
					<td class="sl_t_id hidden-sm hidden-xs"><?php echo $value['name'] ?></td>	
					<td class="sl_t_title"><?php echo sl_show_anchor('questions/'.$value['id'],$value['title']) ?></td>
					<td class="sl_t_count"><?php if($value['question_comments_count']): ?><?php echo _('answer') ?><?php else: ?><?php echo _('no_answer') ?><?php endif ?></td>			
					<td class="sl_t_count"><?php echo $value['count'] ?></td>
					<td class="sl_t_created_at"><time datetime="<?php echo sl_date($value['created_at'],'w3c') ?>" pubdate><?php echo sl_date($value['created_at']) ?></time></td>
				</tr>
				<?php endforeach ?>
				<?php else: ?>
				<tr>
					<td colspan="5" class="no_data"><?php echo _('No Article') ?></td>
				</tr>
				<?php endif ?>
		</tbody>
	</table>
	</article>
	<div id="sl_index_bottom_menu">
		<?php echo $this->pagination->create_links(); ?>
		<?php echo $Layout->element('search'); ?>
		<?php echo anchor('/questions/add','<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>'._('new_link'),array('class'=>"btn btn-default col-xs-12 col-md-2")) ?>
	</div>
</div>
