<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Dcomfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 싸이월드에서 받은 족보를 담는 DB
		Schema::create('dcomfiles', function (Blueprint $table) {
			$table->increments('id');
			$table->string('filename'); // 서버에 올라가는 랜덤 uniqid
			$table->string('realname'); // 이름
			$table->string('original_filename'); // 원본 파일 이름
			$table->integer('download')->default(0); // 다운로드 횟수
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
		Schema::drop('dcomfiles');
    }
}
