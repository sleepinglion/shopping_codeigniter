<section id="product_index">
	<?php if(count($data['list'])): ?>
	<?php foreach($data['list'] as $index=>$product): ?>
	<?php echo form_open('orders/add', array('class' => 'form-horizontal','id'=>'order_form')) ?>
	<div class="product">
		<input type="hidden" name="product[id]"  value="<?php echo $product['id'] ?>" />
		<div class="product_image">
			<?php if(isset($product['photo_list'][0])): ?>
			<figure>
				<img src="<?php echo base_url() ?>uploads/product_picture/<?php echo $product['photo_list'][0]['id'] ?>/medium_thumb_<?php echo $product['photo_list'][0]['photo'] ?>" width="" height="" />
				<figcaption><?php echo $product['title'] ?></figcaption>
			</figure>
			<?php else: ?>
				<?php echo _('No Image') ?>
			<?php endif ?>
		</div>
		<div class="product_text">
			<h4><?php echo $product['title'] ?></h4>
			<div class="product_price">
				<label for="product_price_input_<?php echo $index ?>"><?php echo _('Quantity') ?></label>
				<input type="text" id="product_price_input_<?php echo $index ?>" name="product[quantity]" value="<?php echo set_value('product['.$index.'][quantity]',1) ?>" min="1" max="100" />
			</div>
			<div class="product_description">
				<?php echo $product['description'] ?>
			</div>
		</div>
	</div>
	<input type="submit" class="btn btn-primary btn-lg" value="<?php echo _('Order Form Submit') ?>" />	
	<?php echo form_close() ?>
	<?php endforeach ?>	
	<?php else: ?>
	<h4><?php echo _('Not Exists Product') ?></h4>
	<?php endif ?>
</section>