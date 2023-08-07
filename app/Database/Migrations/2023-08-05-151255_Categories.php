<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Categories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'categories_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'categories_desc' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'categories_created_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'categories_updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'categories_deleted_at' => [
                'type' => 'datetime',
                'null' => true
            ],
        ]);

        $this->forge->addKey('categories_id', true);
        $this->forge->createTable('categories');
    }

    public function down()
    {
        $this->forge->dropTable('categories');
    }
}
