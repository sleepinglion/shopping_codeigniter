<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_user_login_logs extends CI_Migration
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
          'client_ip' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => false
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
        $this->dbforge->create_table('user_login_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('user_login_logs');
    }
}
