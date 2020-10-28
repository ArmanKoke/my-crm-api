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
            $table->foreignId('social_network_id')->constrained();
            $table->string('email')->unique();
            $table->json('data')->default(new Expression('\'[]\'::JSON')); //in case of mysql (JSON_ARRAY()) type
            $table->timestamps();
        });

        Schema::create('socials_users', function (Blueprint $table) { //add foreign keys
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
