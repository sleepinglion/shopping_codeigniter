<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'SL.php';

class Orders extends SL_Controller {
	protected $model = 'Order';

	public function add() {
		$this -> load -> library('form_validation');
		$this -> set_message();

		if ($this -> input -> post('product[id]')) {
			$product_quantity=$this->input->post('product[quantity]');
		} else {
			$this -> form_validation -> set_rules('order[name]', _('Order Name'), 'required|min_length[2]|max_length[60]');
			$this -> form_validation -> set_rules('order[email]', _('Order Email'), 'required|valid_email');
			$this -> form_validation -> set_rules('order[phone]', _('Order Phone'), 'required|min_length[5]');
			$this -> form_validation -> set_rules('order[payment_id]', _('Order Payment'), 'required');
			
			$this -> form_validation -> set_rules('shipping[same_order]', _('Shipping Same'), 'required');
			$this -> form_validation -> set_rules('shipping[name]', _('Shipping Name'), 'required|min_length[2]|max_length[60]');
			$this -> form_validation -> set_rules('shipping[zip_code]', _('Shipping Zip'), 'required|min_length[5]|max_length[5]');
			$this -> form_validation -> set_rules('shipping[address_default]', _('Shipping Address Default'), 'required');
			$this -> form_validation -> set_rules('shipping[address_detail]', _('Shipping Address Detail'), 'required');
			$this -> form_validation -> set_rules('shipping[email]', _('Shipping Email'), 'required|valid_email');
			$this -> form_validation -> set_rules('shipping[phone]', _('Shipping Phone'), 'required|min_length[5]');

			$products = $this -> input -> post('product');

			if (count($products) > 1) {
				$product_quantity = 0;
				foreach ($products as $index => $product) {
					$product_quantity = $product['quantity'];
					if ($index) {
						//	$this -> form_validation -> set_rules('product['.$index.'][id]', _('Product'), 'required|numeric');
						//	$this -> form_validation -> set_rules('product['.$index.'][quantity]', _('Quantity'), 'required|numeric|greater_than[0]|less_than[100]');
					}
				}
			} else {
				$this -> form_validation -> set_rules('product[0][id]', _('Product'), 'required|numeric');
				$this -> form_validation -> set_rules('product[0][quantity]', _('Quantity'), 'required|numeric|greater_than[0]|less_than[100]');
			}

			if (!$this -> session -> userdata('user_id')) {
				$this -> form_validation -> set_rules('agree[no_user]', _('No User Agreement'), 'required', array('required' => _('Please Agree %s')));
			}
			$this -> form_validation -> set_rules('agree[order]', _('Order Agreement'), 'required', array('required' => _('Please Agree %s')));
			
			$product_quantity=1;
		}

		if ($this -> form_validation -> run() == FALSE) {
			$this -> load -> model('Product');

			if ($this -> input -> post('product[id]')) {
				$product = $this -> Product -> get_content($this -> input -> post('product[id]'));
				$products = array('list' => array($product));
			} else {
				$products = $this -> Product -> get_index();
			}

			$this -> load -> model('Payment');
			$payments = $this -> Payment -> get_index();

			$products['payments'] = $payments['list'];

			$this -> return_data['data'] = $products;
			$this -> return_data['data']['product_quantity']=$product_quantity;

			if ($this -> session -> userdata('user_id')) {
				$this -> load -> model('User');
				$this -> return_data['data']['user'] = $this -> User -> get_content($this -> session -> userdata('user_id'));

				$this -> load -> model('Shipping');
				$this -> return_data['data']['shipping'] = $this -> Shipping -> get_index(10, 0, $this -> return_data['data']['user']['id']);
			} else {
				$user = array('name' => '', 'phone' => '', 'email' => '');
				$this -> return_data['data']['user'] = $user;
			}

			$this -> layout -> add_js(base_url() . 'js/validate.min.js');
			$this -> layout -> add_js(base_url() . 'js/plugin/jquery.form.js');
			$this -> layout -> add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js?autoload=false');
			$this -> layout -> add_js(base_url() . 'js/orders/add.js');
			$this -> layout -> render('orders/add', $this -> return_data);
		} else {
			$data = $this -> input -> post(NULL, TRUE);

			if (empty($data['order']['shipping_id'])) {
				$this -> load -> model('Shipping');
				$data['order']['shipping_id'] = $this -> Shipping -> insert($data['shipping']);
			}

			$this -> load -> model('Order');
			$order_id = $this -> Order -> insert($data['order']);

			if (!$this -> session -> userdata('user_id')) {
				$data['order']['order_id'] = $order_id;
				$this -> load -> model('Guest');
				$this -> Guest -> insert($data['order']);
			}

			$this -> load -> model('OrdersProduct');
			$this -> OrdersProduct -> insert($order_id, $data['product']);

			redirect('orders/complete');

			/*} else {
			 $this -> session -> set_flashdata('error', array('type' => 'alert', 'message' => 'gg'));
			 $this -> layout -> render('products/index',$this->return_data);

			 } */
		}
	}

	public function complete() {
		$this -> layout -> render('orders/complete', $this -> return_data);
	}

}
