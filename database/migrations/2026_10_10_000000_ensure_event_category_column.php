<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            return;
        }

        if (! Schema::hasColumn('events', 'category')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('category', 40)->nullable()->after('city');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('events') && Schema::hasColumn('events', 'category')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }
};
