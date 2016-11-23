<?php if(isset($data['tags'])): ?>
<?php if(count($data['tags'])): ?>
<div class="sl_content_tags">
	<h4 class=""><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> <?php echo _('Tag') ?></h4>	
	<ul>
		<?php foreach($data['tags'] as $index=>$value): ?>
		<li><?php echo anchor('/tags/'.urlencode($value['name']),$value['name']) ?></li>
		<?php endforeach ?>
	</ul>
</div>
<?php endif ?>
<?php endif ?>