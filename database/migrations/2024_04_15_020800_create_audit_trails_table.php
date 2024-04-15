<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('action_user_id')->nullable()->comment('PK id from users table')->unsigned();
            $table->tinyInteger('module')->default(0);
            $table->tinyInteger('action_type')->default(0)->comment('0 = create, 1 = update, 2 = delete');
            $table->timestamps();

            $table->foreign('action_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
