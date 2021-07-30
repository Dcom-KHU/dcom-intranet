<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Loggers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('loggers', function (Blueprint $table) {
			$table->increments('id');
			$table->string('command'); 

			/* 
				댓글 작성 : comment
				답글 작성 : reply
				참여 : participation
				그룹 생성: makegroup
				게시픈 글 쓰기: write
			*/

			$table->string('target'); // 그룹id나 게시판id
			$table->string('type'); // ex) project, study, free, photo, notice
			$table->string('who'); // 누가?
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
		Schema::drop('loggers');
    }
}
