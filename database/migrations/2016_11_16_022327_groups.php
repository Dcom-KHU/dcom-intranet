<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Groups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 그룹은 프로젝트 또는 스터디를 포함한다.
		Schema::create('groups', function (Blueprint $table) {
			$table->increments('id');
			$table->string('boardid'); 
			$table->string('userid'); // 그룹을 만든 회원 아이디 (회원 아이디는 유니크하다.)
			$table->string('type'); // project or study
			$table->string('title'); // 그룹의 제목
			$table->string('description'); // 그룹의 설명
			$table->integer('participation'); // 참여자수
			$table->string('git'); // git
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
		Schema::drop('groups');
    }
}
