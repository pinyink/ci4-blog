<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CategoriesTrans extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'unique' => true,
            ],
            'categories_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'default' => 0,
            ],
            'post_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'default' => 0
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categories_trans');
    }

    public function down()
    {
        $this->forge->dropTable('categories_trans');
    }
}
