<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');           // Latin alphabet enforced at app layer
            $table->string('code', 10)->unique();
            $table->string('address');
            $table->string('city');
            $table->string('country', 2)->default('DE'); // ISO country code
            $table->string('postal_code', 10);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('type', ['branch', 'warehouse', 'hub'])->default('branch');
            $table->boolean('is_active')->default(true);
            $table->json('operating_hours')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'type']);
            $table->index(['tenant_id', 'is_active']);
        });

        // Add foreign key for users.branch_id after branches table is created
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('plate')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->enum('type', ['van', 'truck', 'semi', 'container'])->default('van');
            $table->decimal('max_weight_kg', 10, 2)->default(1000);
            $table->decimal('max_volume_m3', 10, 3)->default(10);
            $table->decimal('max_ldm', 8, 2)->default(13.6);
            $table->boolean('is_active')->default(true);
            $table->date('inspection_due')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
        });
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('branches');
    }
};
