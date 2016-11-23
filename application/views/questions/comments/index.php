<?php if($data['comments']['total']): ?>
<section id="sl_question_comment_index" class="box">
	<div class="box_header">
		<h2>댓글</h2>
		<div class="box_icon">
			<a class="btn_minimize" href="#"><i class="glyphicon glyphicon-chevron-up"></i></a>
			<a class="btn_close" href="#"><i class="glyphicon glyphicon-remove"></i></a>
		</div>
	</div>
	<div class="box_content">
		<ul class="sl_comment_list_layer media-list">
			<?php foreach ($data['comments']['list'] as $value): ?>
			<li class="media">
				<?php if(isset($value['photo_url'])): ?>
				<?php echo anchor('/users/'.$value['id'],img(array('src'=>'/images/ajax-loader.gif','data-original'=>sl_get_thumb($value['photo_url'],'small'),'width'=>50,'height'=>50,'alt'=>$value['name'],'class'=>'lazy')),array('class'=>'pull-left')) ?>				
				<noscript>
					<?php echo anchor('/users/'.$value['id'],img(array(sl_get_thumb($value['photo_url'],'small'),'width'=>100,'height'=>100,'alt'=>$value['name']))) ?>
				</noscript>
				<?php endif ?>
				<div class="media-body">
					<h4 class="media-heading"><?php if($value['user_id']): ?><?php echo $value['nickname'] ?><?php else: ?><?php echo $value['name'] ?><?php endif ?><span class="sl_created_at"><?php echo $value['created_at'] ?></span></h4>
					<div itemprop="description"><?php echo nl2br($value['content']) ?></div>
				</div>
			</li>
			<?php endforeach ?>
			<?php unset($value) ?>
			<?php unset($data['comments']) ?>
		</ul>
	</div>
</section>
<?php endif ?>