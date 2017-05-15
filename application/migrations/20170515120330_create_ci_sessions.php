<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_ci_sessions extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'VARCHAR',
            'constraint' => 40,
            'null'=>false
          ),
          'ip_address' => array(
            'type' => 'VARCHAR',
            'constraint' => 45,
            'null'=>false
          ),
          'timestamp' => array(
            'type' => 'INT',
            'default'=>0,
            'constraint' => 10,
            'unsigned' => true,
            'null'=>false
          ),
          'data' => array(
            'type' => 'BLOB',
            'null'=>false
          )
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('timestamp', true);
        $this->dbforge->create_table('ci_sessions');
    }

    public function down()
    {
        $this->dbforge->drop_table('ci_sessions');
    }
}
