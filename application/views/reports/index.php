<section id="sl_report_index">
	<h3>주문내역</h3>
	<?php if($data['total']): ?>
	<?php foreach($data['list'] as $index=>$value): ?>
	<?php echo anchor('reports/'.$value['id'],$value['id']) ?></td>
	<?php endforeach ?>	
	<?php endif ?>
</section>