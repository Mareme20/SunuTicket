<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->json('ticket_types')->nullable()->after('price');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('ticket_type')->nullable()->after('buyer_email');
            $table->string('promo_code')->nullable()->after('ticket_type');
            $table->unsignedInteger('discount_amount')->default(0)->after('promo_code');
        });

        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('code', 80)->unique();
            $table->string('type', 20)->default('percent');
            $table->unsignedInteger('value');
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['ticket_type', 'promo_code', 'discount_amount']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('ticket_types');
        });
    }
};
