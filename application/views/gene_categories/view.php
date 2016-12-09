<section class="sl_gene_category_view" style="background:#fff">



	<p style="text-align:center">* 해당 검사의 결과는 질병의 진단과는 무관하며, 진단 및 치료결정을 위해서는 반드시 의사의 상담이 필요합니다.</p>
	<article style="float:left;width:100%;background:#eee">
		<h4 class="col-xs-12 col-sm-4 col-md-3 col-lg-2"><?php echo $data['content']['title'] ?><br /><?php echo $data['content']['sub_title'] ?></h4>
		<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
			<?php echo $data['content']['tip'] ?>
		</div>
	</article>
	<?php if($data['genes']['total']): ?>
	<?php foreach($data['genes']['list'] as $index=>$value): ?>
	<article  style="float:left;width:100%">
		<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
			<h4><?php echo $value['title'] ?><br /><?php echo $value['sub_title'] ?></h4>
			<?php if($value['gene_relations']['total']): ?>
			<div>
				<h5>관련대사</h5>
				<ul>				
			<?php foreach($value['gene_relations']['list'] as $gene_relation): ?>
					<li><?php echo $gene_relation['title'] ?></li>						
			<?php endforeach ?>
				</ul>
			</div>
			<?php endif ?>
		</div>
		<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
			<p style="border-top:1px solid #99ccff;padding:10px;background:#eee">
				::주의유전형발견시::&nbsp;&nbsp;&nbsp;<?php echo $value['description'] ?>
			</p>
		</div>
	</article>
	<?php endforeach ?>
	<?php endif ?>
	<div style="clear:both">&nbsp;</div>
	
	
</section>