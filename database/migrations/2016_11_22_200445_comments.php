<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('comments', function (Blueprint $table) {
			$table->increments('id');
			$table->string('boardid'); // 게시판 고유 아이디
			$table->string('userid'); // 댓글을 쓴 회원 고유 아이디
			$table->longText('content'); // 댓글 내용
			$table->integer('frontcomment'); // 윗 댓글id (없으면 0)
			$table->integer('anonymous'); // 0 default, 1 anonymous
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
		Schema::drop('comments');
    }
}
