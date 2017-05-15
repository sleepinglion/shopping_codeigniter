<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_shippings extends CI_Migration
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
            'constraint' => 11,
          ),
          'same_order' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'default'=>false,
            'null' => false
          ),
          'name' => array(
            'type' => 'VARCHAR',
            'constraint' => '60',
            'null' => false
          ),
          'zip_code' => array(
            'type' => 'VARCHAR',
            'constraint' => '60',
            'null' => false
          ),
          'address_default' => array(
            'type' => 'VARCHAR',
            'constraint' => '60',
            'null' => false
          ),
          'address_detail' => array(
            'type' => 'VARCHAR',
            'constraint' => '60',
            'null' => false
          ),
          'phone' => array(
            'type' => 'VARCHAR',
            'constraint' => '60',
            'null' => false
          ),
          'email' => array(
            'type' => 'VARCHAR',
            'constraint' => '60',
            'null' => false
          ),
          'message' => array(
            'type' => 'TEXT',
            'null' => true
          ),
          'enable' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'null' => false
          ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('shippings');
    }

    public function down()
    {
        $this->dbforge->drop_table('shippings');
    }
}
