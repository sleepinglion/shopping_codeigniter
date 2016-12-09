<section id="sl_report_view">
	<h3>보고서</h3>
	<?php if($data['total']): ?>
	<?php foreach($data['list'] as $index=>$value): ?>
	<article class="col-xs-12 col-sm-6">
		<h4><?php echo anchor('gene_categories/'.$value['gene_category_id'],$value['gene_category_title'].'-'.$value['report_category_title']) ?></h4>
	</article>
	<?php endforeach ?>
	<?php endif ?>
	<article style="clear:both;padding:50px 0 0">
		<h3><?php echo _('Total Result') ?></h3>
		<p>
			<?php echo $this->session->userdata('name') ?>님은 
	<?php if($data['total']): ?>
	<?php foreach($data['list'] as $index=>$value): ?>
		<?php if($value['report_category_id']!=1) continue ?>					
		<?php echo anchor('gene_categories/'.$value['gene_category_id'],$value['gene_category_title']) ?>
	<?php endforeach ?>
	<?php endif ?>
	을(를) 집중관리 하시고
	
	<?php if($data['total']): ?>
	<?php foreach($data['list'] as $index=>$value): ?>
		<?php if($value['report_category_id']!=2) continue ?>					
		<?php echo anchor('gene_categories/'.$value['gene_category_id'],$value['gene_category_title']) ?>
	<?php endforeach ?>
	<?php endif ?>
	을(를) 관심을 갖고 관리하시길 바랍니다.
	
		</p>
	</article>
</section>