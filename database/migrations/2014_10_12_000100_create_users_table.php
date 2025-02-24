<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('photo_driver')->nullable();
      $table->string('photo_vehicle')->nullable();
      $table->string('ruc')->nullable()->unique();
      $table->string('phone')->nullable();
      $table->string('address')->nullable();
      $table->string('dni')->nullable();
      $table->string('date_afiliate')->nullable();
      $table->string('email')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->string('account_bank')->nullable();
      $table->enum('role', ['admin', 'conductor'])->default('conductor');
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
};
