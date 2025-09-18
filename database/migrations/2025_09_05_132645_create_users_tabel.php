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
        Schema::create('users', function (Blueprint $table) {

            $table->bigIncrements("id");
            $table->string('username' , 50)->unique();
            $table->string('first_name' , 50);
            $table->string('last_name', 100);
            $table->string('country',50);
            $table->string('email', 100)->unique();
            $table->string('phone',20)->nullable();
            $table->string('password' , 255);
            $table->string('bio', 60)->nullable();
            $table->string('avatar_url',255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('last_login_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
