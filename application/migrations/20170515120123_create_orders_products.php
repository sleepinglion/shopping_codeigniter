<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_orders_products extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'auto_increment' => true
          ),
          'order_id' => array(
            'type' => 'INT',
            'constraint' => 11,
          ),
          'product_id' => array(
            'type' => 'INT',
            'constraint' => 11,
          ),
            'total_price' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
          'quantity' => array(
            'type' => 'INT',
            'constraint' => 11,
            'default'=>1,
          )
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('orders_products');
    }

    public function down()
    {
        $this->dbforge->drop_table('orders_products');
    }
}
