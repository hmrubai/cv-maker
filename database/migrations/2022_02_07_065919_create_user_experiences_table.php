<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_experiences', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->string('organization')->nullable();
            $table->string('designation')->nullable();
            $table->timestamp('from_date');

            $table->tinyInteger('is_left_job',false,1)->default(0);
            $table->timestamp('to_date')->nullable();
            $table->tinyInteger('is_still_active',false,1)->default(0);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
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
        Schema::table('user_experiences',function (Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('user_experiences');
    }
}
