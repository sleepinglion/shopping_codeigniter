<article id="sl_main_guest_book" class="sl_main_list">
	<h3><?php echo _('Notice') ?></h3>
	<?php if(isset($data['notices'])): ?>
	<ul>
		<?php foreach ($data['notices']['list'] as $index => $value): ?>
		<li>
			<?php echo anchor('/notices/'.$value['id'],$value['title']) ?>
			<span class="sl_created_at hidden-xs"><?php echo sl_date($value['created_at']) ?></span>
		</li>		
		<?php endforeach ?>
		<?php unset($value) ?>		
		<?php unset($data['notices']) ?>		
  </ul>
  <?php else: ?>
  <p><?php echo _('No Article') ?></p>
  <?php endif ?>
  <?php echo anchor('notices',_('more'),array('class'=>'more','title'=>_('more'))) ?>
</article>