<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Files extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'files_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'files_name' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'files_desc' => [
                'type' => 'VARCHAR',
                'constraint' => '256',
                'default' => null
            ],
            'files_path' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
                'default' => null
            ],
            'files_size' => [
                'type' => 'INT',
                'constraint' => '16',
                'unsigned' => true,
            ],
            'files_mime' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'files_ext' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'files_created_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'files_updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'files_deleted_at' => [
                'type' => 'datetime',
                'null' => true
            ],
        ]);

        $this->forge->addKey('files_id', true);
        $this->forge->createTable('files');
    }

    public function down()
    {
        $this->forge->dropTable('files');
    }
}
