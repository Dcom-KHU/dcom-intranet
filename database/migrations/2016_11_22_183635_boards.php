<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Boards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('boards', function (Blueprint $table) {
			$table->increments('id');
			$table->string('boardid'); // 게시판 고유 아이디
			$table->string('userid'); // 게시판을 만든 회원 고유 아이디
			$table->string('title'); // 게시판 제목
			$table->longText('content'); // 게시판 내용
			$table->integer('anonymous')->nullable();
			$table->integer('viewer')->nullable(); // 조회수
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
		Schema::drop('boards');
    }
}
