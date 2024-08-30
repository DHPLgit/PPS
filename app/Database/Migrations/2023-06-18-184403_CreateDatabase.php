<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDatabase extends Migration
{
    public function up()
    {
        $this->forge->createDatabase('my_db');
    }

    public function down()
    {
        //
    }
}
