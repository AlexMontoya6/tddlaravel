<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Esta tabla es la tabla intermedia entre users y skills
 * Al ser una relacion n:m tiene que haber una tabla intermedia
 * para poder hacer la relación
 */
class CreateSkillUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skill_user', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedInteger('skill_id');
            $table->foreign('skill_id')
                ->references('id')
                ->on('skills');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skill_user');
    }
}
