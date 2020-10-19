<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_orders extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'auto_increment' => true
          ),
          'user_id' => array(
            'type' => 'INT',
            'constraint' => 11
          ),
          'shipping_id' => array(
            'type' => 'INT',
            'constraint' => 11
          ),
          'payment_id' => array(
            'type' => 'INT',
            'constraint' => 11
          ),
          'total_price' => array(
            'type' => 'INT',
            'constraint' => 11
          ),
            're_order' => array(
                'type' => 'INT',
                'constraint' => 1
            ),
            'stopped' => array(
                'type' => 'INT',
                'constraint' => 1
            ),
          'enable' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'null' => false
          )
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('orders');
    }

    public function down()
    {
        $this->dbforge->drop_table('orders');
    }
}
