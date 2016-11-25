<section id="sl_order_add">
	<?php echo form_open('orders/add', array('class' => 'form-horizontal','id'=>'order_form')) ?>
	<article>
		<h4><?php echo _('Product') ?></h4>
		<div class="inner_section">
			<?php foreach($data['list'] as $index=>$product): ?>
			<div class="product">
				<?php echo form_hidden('product['.$index.'][id]',$product['id']) ?>
				<div class="product_image">
					<?php if(isset($product['photo_list'][0])): ?>
					<figure>
						<img src="<?php echo base_url() ?>uploads/product_picture/<?php echo $product['photo_list'][0]['id'] ?>/medium_thumb_<?php echo $product['photo_list'][0]['photo'] ?>" width="" height="" />
						<figcaption><?php echo $product['title'] ?></figcaption>
					</figure>
					<?php else: ?>
					이미지 없음
					<?php endif ?>
				</div>
				<div class="product_text">
					<h4><?php echo $product['title'] ?></h4>
					<div class="product_price">
						<label for="product_price_input_<?php echo $index ?>"><?php echo _('Quantity') ?></label>
						<input type="text" id="product_price_input_<?php echo $index ?>" name="product[<?php echo $index ?>][quantity]" value="<?php echo set_value('product['.$index.'][quantity]',$data['product_quantity']) ?>" min="1" max="100" />
					</div>
					<div class="product_description">
						<?php echo $product['description'] ?>
					</div>
				</div>
			</div>
			<?php endforeach ?>
			<div id="calculator_table">
				<table class="table table-striped table-bordered"">
					<caption><?php echo _('Calculator Table') ?></caption>
					<colgroup>
						<col />
						<col />
						<col />
						<col />
						<col />
					</colgroup>
					<thead>
						<tr>
							<th class="active"><?php echo _('Product Name') ?></th>							
							<th class="active"><?php echo _('Product Price') ?></th>
							<th class="active"><?php echo _('Quantity') ?></th>							
							<th class="active"><?php echo _('Shipping Cost') ?></th>
							<th class="active"><?php echo _('Total Cost') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $total_cost=0 ?>
						<?php foreach($data['list'] as $index=>$product): ?>
						<?php 
							$total_cost=$total_cost+($product['price']+$product['shipping_price'])*$data['product_quantity'];
						?>	
						<tr>
							<th scope="row"><?php echo $product['title'] ?></th>
							<td><input type="hidden" value="<?php echo $product['price']?>"><span class="price"><?php echo product_price($product['price']) ?></span><?php if(!empty($product['price'])): ?><span class="current"><?php echo _('Currency') ?></span><?php endif ?></td>
							<td><span class="quantity"><?php echo $data['product_quantity'] ?></span></td>							
							<td><input type="hidden" value="<?php echo $product['shipping_price']?>"><span class="price"><?php echo product_price($product['shipping_price']) ?></span><?php if(!empty($product['shipping_price'])): ?><span class="current"><?php echo _('Currency') ?></span><?php endif ?></td>
							<td><span class="price"><?php echo product_price($product['price']+$product['shipping_price']) ?></span><?php if(!empty($total_cost)): ?><span class="current"><?php echo _('Currency') ?></span><?php endif ?></td>
						</tr>
						<?php endforeach ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="4"><?php echo _('All Total Cost') ?></td>
							<td><span class="price total_price"><?php echo product_price($total_cost) ?></span><?php if(!empty($total_cost)): ?><span class="current"><?php echo _('Currency') ?></span><?php endif ?></td>
						</tr>
					</tfoot>
				</table>
			</div>			
		</div>
	</article>
	<?php echo $Layout->element('form_error_message') ?>
	<?php echo $this->session->flashdata('error_message'); ?>		
		<article <?php if($this -> session -> userdata('user_id')): ?>style="display:none"<?php endif ?>>
			<h4><?php echo _('Order Info') ?></h4>
			<div class="inner_section">							
			<div class="form-group<?php if(form_error('order[name]')){echo ' has-error';} ?>">
				<label class="control-label col-sm-2" for="sl_order_name"><?php echo _('Name') ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="sl_order_name" name="order[name]" value="<?php echo set_value('order[name]',$data['user']['name']) ?>" <?php if($this -> session -> userdata('user_id')): ?>readonly="readonly"<?php endif ?> <?php /* required="required" */ ?> />
				</div>
			</div>		
			<div class="form-group<?php if(form_error('order[email]')){echo ' has-error';} ?>">
				<label class="control-label col-sm-2" for="sl_order_email"><?php echo _('Email') ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="sl_order_email" name="order[email]" value="<?php echo set_value('order[email]',$data['user']['email']) ?>" <?php if($this -> session -> userdata('user_id')): ?>readonly="readonly"<?php endif ?> <?php /* required="required" */ ?> />
				</div>
			</div>
			<div class="form-group<?php if(form_error('order[phone]')){echo ' has-error';} ?>">
				<label class="control-label col-sm-2" for="sl_order_phone"><?php echo _('Phone') ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="sl_order_phone" name="order[phone]" value="<?php echo set_value('order[phone]',$data['user']['phone']) ?>" <?php if($this -> session -> userdata('user_id')): ?>readonly="readonly"<?php endif ?> <?php /* required="required" */ ?> />
				</div>
			</div>
			</div>
		</article>
		<article>
			<h4><?php echo _('Shipping Info') ?></h4>
			<div class="inner_section">
			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<label class="radio-inline">
						<input type="radio" name="shipping[same_order]" value="1" <?php echo set_radio('shipping[same_order]', 1,TRUE) ?> /> <?php if($this -> session -> userdata('user_id')): ?><?php echo _('Same Member') ?><?php else: ?><?php echo _('Same Order') ?><?php endif ?>
					</label>
					<label class="radio-inline">
						<input type="radio" name="shipping[same_order]" value="0" <?php echo set_radio('shipping[same_order]', 0) ?> /> <?php if($this -> session -> userdata('user_id')): ?><?php echo _('Not Same Member') ?><?php else: ?><?php echo _('Not Same Order') ?><?php endif ?>
					</label>
 				</div>
			</div>
			<div class="form-group<?php if(form_error('shipping[name]')){echo ' has-error';} ?>">
				<label class="control-label col-sm-2" for="sl_shipping_name"><?php echo _('Name') ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="sl_shipping_name" name="shipping[name]" value="<?php echo set_value('shipping[name]',$data['user']['name']) ?>" readonly="readonly" <?php /* required="required" */ ?> />
				</div>
			</div>
			<div class="form-group<?php if(form_error('shipping[zip_code]') OR form_error('shipping[address_default]') OR form_error('shipping[address_detail]')){echo ' has-error';} ?>">
				<label class="control-label col-xs-12  col-sm-2" for="sl_shipping_address"><?php echo _('label_address') ?></label>
				<div class="col-xs-6 col-sm-3">
					<input type="text" class="form-control" id="sl_shipping_zip" name="shipping[zip_code]" placeholder="<?php echo _('Zip Code') ?>" value="<?php echo set_value('shipping[zip_code]') ?>" <?php /* required="required" */ ?> />
				</div>
				<div class="col-xs-6 col-sm-3" id="find_zip">
					<input type="button" class="btn btn-default" value="<?php echo _('Find Zip') ?>" />					
				</div>
				<div class="col-sm-10 col-sm-offset-2">
					<div id="wrap" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
						<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" alt="접기 버튼">
					</div>					
				</div>
				<div class="col-sm-10 col-sm-offset-2" style="clear:both;padding-top:10px">
					<input type="text" class="form-control" id="sl_shipping_address_default" name="shipping[address_default]" placeholder="<?php echo _('Default Address') ?>" value="<?php echo set_value('shipping[address_default]') ?>" <?php /* required="required" */ ?> />
				</div>
				<div class="col-sm-10 col-sm-offset-2" style="margin-top:10px">
					<input type="text" class="form-control" id="sl_shipping_address_detail" name="shipping[address_detail]" placeholder="<?php echo _('Detail Address') ?>" value="<?php echo set_value('shipping[address_detail]') ?>" <?php /* required="required" */ ?> />
				</div>								
			</div>
			<div class="form-group<?php if(form_error('shipping[email]')){echo ' has-error';} ?>">
				<label class="control-label col-sm-2" for="sl_shipping_email"><?php echo _('Email') ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="sl_shipping_email" name="shipping[email]" value="<?php echo set_value('shipping[email]',$data['user']['email']) ?>" readonly="readonly" <?php /* required="required" */ ?> />
				</div>
			</div>
			<div class="form-group<?php if(form_error('shipping[phone]')){echo ' has-error';} ?>">
				<label class="control-label col-sm-2" for="sl_shipping_phone"><?php echo _('Phone') ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="sl_shipping_phone" name="shipping[phone]" value="<?php echo set_value('shipping[phone]',$data['user']['phone']) ?>" readonly="readonly" <?php /* required="required" */ ?> />
				</div>
			</div>
			<div class="form-group<?php if(form_error('shipping[message]')){echo ' has-error';} ?>">
				<label class="control-label col-sm-2" for="sl_shipping_message"><?php echo _('label_shipping_message') ?></label>
				<div class="col-sm-10">
					<textarea class="form-control" id="sl_shipping_message" name="shipping[message]" <?php /* required="required" */ ?>><?php echo set_value('shipping[message]') ?></textarea>
				</div>
			</div>
			</div>	
		</article>
		<article>
			<h4><?php echo _('Agree Order') ?></h4>
			<div class="inner_section">
			<?php if(!$this -> session -> userdata('user_id')): ?>				
			<div class="col-md-offset-2<?php if(form_error('agree[no_user]')){echo ' has-error';} ?>">
				<div class="checkbox">			
					<label>
						<input type="checkbox" name="agree[no_user]" value="1" <?php echo set_checkbox('agree[no_user]', '1', FALSE); ?> /> <span><?php echo _('No User Agreement') ?></span>
					</label>
				</div>
			</div>
			<?php endif ?>
			<div class="checkbox col-md-offset-2<?php if(form_error('agree[order]')){echo ' has-error';} ?>">
				<div class="checkbox">				
					<label>
						<input type="checkbox" name="agree[order]" value="1" <?php echo set_checkbox('agree[order]', '1', FALSE); ?> /> <span><?php echo _('Order Agreement') ?></span>
					</label>
				</div>
			</div>	
		</article>
		<article>
			<h4><?php echo _('Payment') ?></h4>
			<div class="inner_section">			
			<div class="col-md-offset-2<?php if(form_error('order[payment_id]')){echo ' has-error';} ?>">
				<div class="radio">
				<?php foreach($data['payments'] as $index=>$payment): ?>				
				<label class="radio-inline">
					<input type="radio" name="order[payment_id]" value="<?php echo $payment['id'] ?>" <?php if(empty($index)): ?>checked="checked"<?php endif ?> /> <?php echo $payment['title']; ?>
				</label>
				<?php endforeach ?>
				</div>
			</div>
			</div>
		</article>
	<div class="section_bottom">
		<input type="submit" class="btn btn-primary btn-lg" value="<?php echo _('Order Form Submit') ?>" />
	</div>
	<?php echo form_close() ?>	
</section>