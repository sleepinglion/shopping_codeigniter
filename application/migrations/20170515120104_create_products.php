<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_products extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'auto_increment' => true
          ),
          'product_category_id' => array(
            'type' => 'INT',
            'constraint' => 11
          ),
          'title' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => false
          ),
          'enable' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'null' => false
          ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('products');
    }

    public function down()
    {
        $this->dbforge->drop_table('products');
    }
}
