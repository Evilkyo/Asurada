<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleUsersTable extends Migration
{
    public function up()
    {
        $this->schema->create('role_users', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('role_id')->unsigned()->index();
            $table->nullableTimestamps();

            $table->primary(['user_id', 'role_id']);
        });
    }

    public function down()
    {
        $this->schema->dropIfExists('role_users');
    }
}
