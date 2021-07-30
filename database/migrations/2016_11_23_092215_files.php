<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Files extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('files', function (Blueprint $table) {
			$table->increments('id');
			$table->string('userid');
			$table->string('filename'); // 파일 이름
			$table->string('mime'); // 파일 확장자
			$table->string('original_filename'); // 파일 원래 이름
			$table->string('type'); // file or image
			$table->integer('download'); // 다운로드 수
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
		Schema::drop('files');
    }
}
