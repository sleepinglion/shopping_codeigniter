<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_product_pictures extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'auto_increment' => true
          ),
          'product_id' => array(
            'type' => 'INT',
            'constraint' => 11,
          ),
          'photo' => array(
            'type' => 'VARCHAR',
            'constraint' => 200,
          ),
          'enable' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'null' => false
          ),
          'created_at' => array(
            'type' => 'DATETIME',
            'null' => false
          ),
          'updated_at' => array(
            'type' => 'DATETIME',
            'null' => false
          )
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('product_pictures');
    }

    public function down()
    {
        $this->dbforge->drop_table('product_pictures');
    }
}
