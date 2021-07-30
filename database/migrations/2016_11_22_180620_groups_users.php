<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('groups_users', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('groupid'); // 그룹 고유 아이디
			$table->string('userid'); // 회원 고유 아이디
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
		Schema::drop('groups_users');
    }
}
