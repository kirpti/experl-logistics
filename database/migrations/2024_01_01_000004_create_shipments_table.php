<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete(); // Owning branch
            $table->string('name');           // Latin alphabet ONLY
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('country', 2)->default('DE');
            $table->string('postal_code', 10);
            $table->enum('account_type', ['cash', 'credit'])->default('cash');
            $table->decimal('credit_limit', 14, 2)->default(0);
            $table->decimal('current_balance', 14, 2)->default(0); // Cari bakiye
            $table->string('currency', 3)->default('EUR');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'account_type']);
            $table->index(['tenant_id', 'name']);
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sender_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');

            $table->string('tracking_number')->unique(); // e.g. "EXP-TR-2024-000001"
            $table->string('reference_number')->nullable(); // Customer reference

            // Sender (Latin alphabet enforced)
            $table->string('sender_name');
            $table->string('sender_company')->nullable();
            $table->string('sender_address');
            $table->string('sender_city');
            $table->string('sender_country', 2);
            $table->string('sender_postal_code', 10);
            $table->string('sender_phone')->nullable();

            // Receiver (Latin alphabet enforced)
            $table->string('receiver_name');
            $table->string('receiver_company')->nullable();
            $table->string('receiver_address');
            $table->string('receiver_city');
            $table->string('receiver_country', 2);
            $table->string('receiver_postal_code', 10);
            $table->string('receiver_phone')->nullable();

            // Status flow
            $table->enum('status', [
                'draft', 'accepted', 'in_warehouse', 'loaded',
                'in_transit', 'out_for_delivery', 'delivered',
                'failed_delivery', 'returned', 'cancelled'
            ])->default('draft');

            // Weight & volume (aggregated from packages)
            $table->decimal('total_weight_kg', 10, 3)->default(0);
            $table->decimal('total_volume_m3', 10, 4)->default(0);
            $table->decimal('total_ldm', 8, 3)->default(0);
            $table->decimal('chargeable_weight', 10, 3)->default(0); // max(real, vol, ldm)
            $table->integer('total_packages')->default(1);

            // Pricing
            $table->string('currency', 3)->default('EUR');
            $table->decimal('price_per_kg', 10, 4)->default(0);
            $table->decimal('freight_charge', 10, 2)->default(0);
            $table->decimal('fuel_surcharge', 10, 2)->default(0);
            $table->decimal('customs_fee', 10, 2)->default(0);
            $table->decimal('insurance_fee', 10, 2)->default(0);
            $table->decimal('other_charges', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

            // Payment
            $table->enum('payment_type', ['prepaid', 'credit', 'collect'])->default('prepaid');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->decimal('paid_amount', 10, 2)->default(0);

            // Content description (for packing list)
            $table->string('content_description'); // What's inside
            $table->string('incoterms', 10)->nullable(); // EXW, FOB, CIF etc.
            $table->boolean('is_customs_required')->default(false);
            $table->decimal('declared_value', 10, 2)->nullable();

            // POD (Proof of Delivery)
            $table->timestamp('delivered_at')->nullable();
            $table->string('delivered_to')->nullable(); // Recipient name on delivery
            $table->text('delivery_notes')->nullable();

            // Provider label info
            $table->string('provider', 20)->nullable(); // 'gls', 'dpd', 'own'
            $table->string('provider_tracking_number')->nullable();
            $table->text('provider_label_data')->nullable(); // Base64 label

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'tracking_number']);
            $table->index(['tenant_id', 'payment_status']);
            $table->index(['branch_id', 'created_at']);
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('barcode')->unique()->nullable();
            $table->integer('sequence')->default(1); // Package 1 of N
            $table->string('description')->nullable(); // Latin alphabet

            // Physical dimensions
            $table->decimal('weight_kg', 8, 3);
            $table->decimal('length_cm', 8, 2)->nullable();
            $table->decimal('width_cm', 8, 2)->nullable();
            $table->decimal('height_cm', 8, 2)->nullable();

            // Calculated fields
            $table->decimal('volume_m3', 10, 6)->storedAs(
                'ROUND((length_cm * width_cm * height_cm) / 1000000, 6)'
            )->nullable();
            $table->decimal('volumetric_weight', 8, 3)->nullable(); // vol_m3 * 333
            $table->decimal('ldm', 8, 4)->nullable(); // (length * width) / 2.4 (in meters)
            $table->decimal('ldm_weight', 8, 3)->nullable(); // ldm * 1850
            $table->decimal('chargeable_weight', 8, 3)->nullable(); // max of the three

            $table->enum('status', [
                'pending', 'in_warehouse', 'loaded', 'in_transit',
                'out_for_delivery', 'delivered', 'returned'
            ])->default('pending');

            $table->json('photos')->nullable(); // POD photos array
            $table->text('signature_data')->nullable(); // Base64 signature image
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['shipment_id', 'status']);
            $table->index(['barcode']);
        });

        Schema::create('shipment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type'); // status_change, scan, note, etc.
            $table->string('status')->nullable();
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['shipment_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_events');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('customers');
    }
};
