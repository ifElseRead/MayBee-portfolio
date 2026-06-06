<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('banner_image');
            $table->text('prompt_topic')->nullable()->after('status');
            $table->dropColumn('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['status', 'prompt_topic']);
            $table->boolean('is_published')->default(true);
        });
    }
};
