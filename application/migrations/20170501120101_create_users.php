<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_users extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'auto_increment' => true
          ),
          'email' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => false
          ),
          'name' => array(
            'type' => 'VARCHAR',
            'constraint' => '40',
            'null' => false
          ),
          'encrypted_password' => array(
            'type' => 'VARCHAR',
            'constraint' => '40',
            'null' => false
          ),
          'phone' => array(
            'type' => 'VARCHAR',
            'constraint' => 20,
            'default'=>false,
            'null' => false
          ),
          'birthday' => array(
            'type' => 'DATE',
            'null' => false
          ),
          'sex' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'default'=>false,
            'null' => false
          ),
          'phone' => array(
            'type' => 'VARCHAR',
            'constraint' => '20',
            'null' => false
          ),
          'height' => array(
            'type' => 'INT',
            'constraint' => '11',
            'null' => true
          ),
          'weight' => array(
            'type' => 'INT',
            'constraint' => '11',
            'null' => true
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
        $this->dbforge->create_table('users');
    }

    public function down()
    {
        $this->dbforge->drop_table('users');
    }
}
