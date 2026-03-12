<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('origin_branch_id')->constrained('branches');
            $table->foreignId('destination_branch_id')->constrained('branches');
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('service_code')->unique(); // e.g. "SRV-2024-00123"
            $table->string('name');                   // Route name, Latin alphabet
            $table->enum('status', [
                'planned', 'loading', 'in_transit', 'arrived', 'completed', 'cancelled'
            ])->default('planned');
            $table->enum('transport_mode', ['road', 'air', 'sea', 'rail'])->default('road');
            $table->datetime('departure_at')->nullable();
            $table->datetime('arrival_at')->nullable();
            $table->datetime('actual_departure_at')->nullable();
            $table->datetime('actual_arrival_at')->nullable();

            // Financial tracking per service (profitability)
            $table->string('currency', 3)->default('EUR');
            $table->decimal('total_revenue', 14, 2)->default(0);
            $table->decimal('total_cost', 14, 2)->default(0);
            $table->decimal('fuel_cost', 10, 2)->default(0);
            $table->decimal('driver_cost', 10, 2)->default(0);
            $table->decimal('toll_cost', 10, 2)->default(0);
            $table->decimal('other_cost', 10, 2)->default(0);

            // Capacity metrics
            $table->decimal('total_weight_kg', 10, 2)->default(0);
            $table->decimal('total_volume_m3', 10, 3)->default(0);
            $table->decimal('total_ldm', 8, 2)->default(0);
            $table->integer('total_packages')->default(0);

            $table->text('notes')->nullable();
            $table->json('route_waypoints')->nullable(); // GPS waypoints
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'departure_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
