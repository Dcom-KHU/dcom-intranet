<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Viewers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 조회수를 관리하는 테이블
		Schema::create('viewers', function (Blueprint $table) {
			$table->increments('id');
			$table->string('boardid'); // 그룹의 숫자 id
			$table->string('userid'); // 다녀간 userid
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
		Schema::drop('viewers');
    }
}
