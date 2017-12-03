<section id="product_show">

	<?php echo form_open('orders/add', array('method'=>'get','class' => 'order_form form-horizontal')) ?>


	<article class="panel panel-default product">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $product['title'] ?></h3>
		</div>
		<div class="panel-body">

		</div>
	</article>
	<div class="section_bottom">
		<input type="submit" class="btn btn-primary btn-lg" value="<?php echo _('Order Form Submit') ?>" />
	</div>
	<?php echo form_close() ?>
</section>
