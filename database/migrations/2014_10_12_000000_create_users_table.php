<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id'); // 회원 인덱스
            $table->string('userid')->unique(); // 회원 아이디
            $table->string('email')->unique(); // 회원 이메일
            $table->string('password'); // 회원 패스워드
            $table->string('realname'); // 회원 성명
            $table->string('phone'); // 회원 전화번호
            $table->integer('admissionyear'); // 입학년도 2자리 숫자
            $table->integer('confirm')->default(0); // 0 미승락 1 승락
            $table->integer('admin')->default(0); // 0 일반회원 1 관리자
			$table->timestamp('logintime')->nullable(); // 최근 로그인 시간
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
