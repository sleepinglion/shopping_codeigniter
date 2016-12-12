<section id="sl_gene_category_view">
	<article class="row">
		<div class="col-xs-12 col-sm-4 col-md-5 ">
			<h2><?php echo $data['content']['title'] ?></h2>
			<p><?php echo $data['content']['sub_title'] ?></p>
		</div> 		
		<div class="col-xs-12 col-sm-8 col-md-7">
			<?php printf(_('%s is analysised total %s, %s analysised %s needs %s'),$data['content']['title'],$data['genes']['total'],$this->session->userdata('name'),$data['content']['title'],$data['content']['report_category_sub_title']) ?>			
		</div>
	</article>
	<article class="row">
		<div class="col-xs-12 col-sm-4">
			<h3><?php echo $data['content']['title'] ?></h3>
		</div>
		<div class="col-xs-12 col-sm-8">
			<p>개개인은 각각의 유전자에 관해 2개의 유전형을 가지고 있습니다.<br />유전형에 <span>주의 유전형</span>이 발견되었을시 관리가 필요하게 됩니다.</p>
		</div>		
	</article>
	<article class="row">
		<h4><?php printf(_('%s related gene analysis is'),$data['content']['title']) ?></h4>
		<ul>
			<?php if($data['genes']['total']): ?>
			<?php foreach($data['genes']['list'] as $index=>$value): ?>
			<li><?php echo $value['title'] ?> : </li>
			<?php endforeach ?>
			<?php endif ?>
		</ul>
		<p><?php printf(_('So %s`s %s result is %s'),$this->session->userdata('name'),$data['content']['title'],$data['content']['report_category_sub_title']) ?></p>
	</article>
	<p style="text-align:center">* 해당 검사의 결과는 질병의 진단과는 무관하며, 진단 및 치료결정을 위해서는 반드시 의사의 상담이 필요합니다.</p>	
	<article class="row">
		<div class="col-xs-12">
			<div  id="tip">
		<h4 class="col-xs-12 col-sm-4 col-md-3 col-lg-2"><?php echo $data['content']['title'] ?><span><?php echo $data['content']['sub_title'] ?></span></h4>		
		<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
			<?php echo $data['content']['tip'] ?>
		</div>
		</div>
		</div>
	</article>
	<?php if($data['genes']['total']): ?>
	<?php foreach($data['genes']['list'] as $index=>$value): ?>
	<article class="gene row">
		<div class="col-xs-12 col-sm-4 col-md-3">
			<div class="gene_title">
				<h4><?php echo $value['title'] ?><span><?php echo _('Gene Relation') ?></span></h4>
				<p><span><?php echo _('Gene Title') ?></span><?php echo $value['sub_title'] ?></p>
			</div>
			<?php if($value['gene_relations']['total']): ?>
			<div class="relation">
				<h5>관련대사</h5>
				<ul class="list-unstyled list-inline">				
			<?php foreach($value['gene_relations']['list'] as $gene_relation): ?>
					<li><?php echo $gene_relation['title'] ?></li>						
			<?php endforeach ?>
				</ul>
			</div>
			<?php endif ?>
		</div>
		<div class="gene_description col-xs-12 col-sm-8 col-md-9">
			<p>
				:: 주의유전형발견시 ::&nbsp;&nbsp;&nbsp;<?php echo $value['description'] ?>
			</p>
			<div class="row">
			<div class="col-xs-12 col-sm-8">
				<div class="gene_description_section description_section_left">
				<p><?php echo printf(_('%s Relation %s Default Gene is %s,Warning Gene is %s'),$value['title'],$value['sub_title'],'TT','A') ?></p>
				<div class="gene_d_bottom">
					<?php echo printf(_('%s Your have Gene %s'),$this->session->userdata('name'),'TT') ?>
				</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">
				<div class="gene_description_section description_section_right">
				<p><?php echo _('Asian Have This Gene ') ?></p>
				<div class="gene_d_bottom">
					<?php echo printf(_('%s Asia Gene Percentage %s'),$this->session->userdata('name'),'36%') ?>	
				</div>
				</div>
			</div>
		</div>
	</article>
	<?php endforeach ?>
	<?php endif ?>
	<div style="clear:both">&nbsp;</div>
	
	
</section>