<section id="product_index">
	<?php if(count($data['list'])): ?>
	<?php foreach($data['list'] as $index=>$product): ?>
	<?php echo form_open('orders/add', array('method'=>'get','class' => 'order_form form-horizontal')) ?>
	<article class="panel panel-default product">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $product['title'] ?></h3>
		</div>
		<div class="panel-body">
			<input type="hidden" name="product[id]"  value="<?php echo $product['id'] ?>" />		
			<div class="media">
				<div class="media-left media-middle">
					<a href="#">
						<?php if(isset($product['photo_list'][0])): ?>
						<figure>
							<img src="<?php echo base_url() ?>uploads/product_picture/<?php echo $product['photo_list'][0]['id'] ?>/medium_thumb_<?php echo $product['photo_list'][0]['photo'] ?>" width="" height="" class="img-rounded" />
							<figcaption><?php echo $product['title'] ?></figcaption>
						</figure>
						<?php else: ?>
						<figure>
							<img src="" width="" height="" class="img-rounded" />
							<figcaption><?php echo _('No Image') ?></figcaption>
						</figure>
						<?php endif ?>
					</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading"><?php echo $product['title'] ?></h4>
					<div class="product_price">
						<label for="product_price_input_<?php echo $index ?>"><?php echo _('Quantity') ?></label>
						<input type="text" id="product_price_input_<?php echo $index ?>" name="product[quantity]" value="<?php echo set_value('product['.$index.'][quantity]',1) ?>" min="1" max="100" />
					</div>
					<div class="product_description">
						<?php echo $product['description'] ?>
					</div>
				</div>
			</div>
		</div>
	</article>
	<div class="section_bottom">
		<input type="submit" class="btn btn-primary btn-lg" value="<?php echo _('Order Form Submit') ?>" />
	</div>	
	<?php echo form_close() ?>
	<?php endforeach ?>	
	<?php else: ?>
	<h4><?php echo _('Not Exists Product') ?></h4>
	<?php endif ?>
</section>