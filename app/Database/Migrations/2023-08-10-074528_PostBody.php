<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PostBody extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'post_body_id' => [
                'type' => 'BIGINT',
                'constraint' => '16',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'post_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'default' => null,
            ],
            'post_body_content' => [
                'type' => 'TEXT',
                'default' => NULL,
            ],
            'post_body_categori' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => null,
            ],
            'post_body_order' => [
                'type' => 'INT',
                'constraint' => '11',
                'default' => 0,
            ],
            'post_body_created_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'post_body_updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'post_body_deleted_at' => [
                'type' => 'datetime',
                'null' => true
            ],
        ]);

        $this->forge->addKey('post_body_id', true);
        $this->forge->createTable('post_body');
    }

    public function down()
    {
        $this->forge->dropTable('post_body');
    }
}
