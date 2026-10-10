<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->unique();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('role', 20)->default('organizer'); // organizer | admin
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('venue');
            $table->string('city', 80);
            $table->dateTime('starts_at')->index();
            $table->unsignedInteger('price');      // FCFA, prix reçu par l'organisateur
            $table->unsignedInteger('capacity');
            $table->string('poster_path')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique();   // lien secret de l'acheteur
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('buyer_name');
            $table->string('buyer_phone', 20);
            $table->string('buyer_email');
            $table->unsignedSmallInteger('quantity');
            $table->unsignedInteger('unit_price');
            $table->unsignedInteger('subtotal');    // prix x quantité (part de l'organisateur)
            $table->unsignedInteger('commission');  // 7 %
            $table->unsignedInteger('total');       // payé par l'acheteur
            $table->unsignedInteger('gateway_fee'); // 3 % PayDunya
            $table->integer('admin_net');           // commission - frais PayDunya
            $table->string('status', 20)->default('pending')->index(); // pending | paid | failed
            $table->string('paydunya_token')->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('code', 64)->unique();
            $table->string('holder_name');
            $table->string('status', 20)->default('valid')->index(); // valid | used
            $table->timestamp('used_at')->nullable();
            $table->foreignId('used_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('events');
        Schema::dropIfExists('users');
    }
};
