<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('social_network_id');
            $table->json('data')->default(new Expression('(JSON_ARRAY())'));
            $table->timestamps();

            $table->foreignId('social_network_id')->constrained();
        });

        Schema::create('socials_users', function (Blueprint $table) {
            $table->unsignedBigInteger('social_id');
            $table->unsignedBigInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socials');
        Schema::dropIfExists('socials_users');
    }
}
