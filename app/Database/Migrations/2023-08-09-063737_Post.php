<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Post extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'post_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'post_desc' => [
                'type' => 'VARCHAR',
                'constaint' => '256',
                'default' => NULL
            ],
            'post_url' => [
                'type' => 'VARCHAR',
                'constaint' => '128',
                'unique' => true
            ],
            'post_created_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'post_updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'post_deleted_at' => [
                'type' => 'datetime',
                'null' => true
            ],
        ]);
        $this->forge->addKey('post_id', true);
        $this->forge->createTable('post');
    }

    public function down()
    {
        $this->forge->dropTable('post');
    }
}
