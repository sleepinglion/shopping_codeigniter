<section id="product_index">
	<?php if (empty($data['total'])): ?>
	<h4><?php echo _('Not Exists Product'); ?></h4>
	<?php else: ?>	
	<?php foreach ($data['list'] as $index => $product): ?>
	<a href="products/<?php echo $product['id']; ?>" class="col-xs-12 col-md-6 col-lg-4 col-xl-3">
	<article class="panel panel-default product">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $product['title']; ?></h3>
		</div>
		<div class="panel-body">
			<div class="media">
				<div class="media-left media-middle">
						<?php if (isset($product['photo_list'][0])): ?>
						<figure>
							<img src="<?php echo base_url(); ?>uploads/product_picture/<?php echo $product['photo_list'][0]['id']; ?>/medium_thumb_<?php echo $product['photo_list'][0]['photo']; ?>" width="400" height="400" class="img-rounded img-responsive" />
							<figcaption><?php echo $product['title']; ?></figcaption>
						</figure>
						<?php else: ?>
						<figure>
							<img src="" width="250" height="250" class="img-rounded" />
							<figcaption><?php echo _('No Image'); ?></figcaption>
						</figure>
						<?php endif; ?>
				</div>
				<div class="media-body">
					<h4 class="media-heading"><?php echo $product['title']; ?></h4>
					<div class="product_description">
						<?php echo $product['description']; ?>
					</div>
				</div>
			</div>
		</div>
	</article>
	</a>
	<?php endforeach; ?>
	<?php endif; ?>
</section>
