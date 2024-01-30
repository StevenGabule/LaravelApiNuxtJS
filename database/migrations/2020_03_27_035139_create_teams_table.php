<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(): void
  {
    Schema::create('teams', static function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name');
      $table->string('slug')->unique()->index();
      $table->unsignedBigInteger('owner_id');
      $table->timestamps();
      $table->foreign('owner_id')->references('id')->on('users');
    });

    Schema::create('team_user', static function (Blueprint $table) {
      $table->unsignedBigInteger('team_id');
      $table->unsignedBigInteger('user_id');
      $table->timestamps();
      $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down(): void
  {
    Schema::dropIfExists('teams');
    Schema::dropIfExists('team_user');
  }
}
