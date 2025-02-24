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
    Schema::create('configurations', function (Blueprint $table) {
      $table->id();
      $table->string('correo1');
      $table->string('correo2');
      $table->string('celular1');
      $table->string('celular2');
      $table->string('whatsapp');
      $table->string('facebook');
      $table->string('twitter');
      $table->string('instagram');
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
    Schema::dropIfExists('configurations');
  }
};
