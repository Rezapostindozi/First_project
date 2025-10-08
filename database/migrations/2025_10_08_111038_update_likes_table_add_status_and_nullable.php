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
        Schema::table('likes', function (Blueprint $table) {

            Schema::table('likes', function (Blueprint $table) {
                $table->unsignedBigInteger('post_id')->nullable()->change();
                $table->unsignedBigInteger('comment_id')->nullable()->change();

                if (!Schema::hasColumn('likes', 'like_status')) {
                    $table->enum('like_status', ['like', 'dislike'])->default('like')->after('comment_id');
                }
            });

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {

            Schema::table('likes', function (Blueprint $table) {

                $table->unsignedBigInteger('post_id')->nullable(false)->change();
                $table->unsignedBigInteger('comment_id')->nullable(false)->change();

                $table->dropColumn('like_status');
            });

        });
    }
};
