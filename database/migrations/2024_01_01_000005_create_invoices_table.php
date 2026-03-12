<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');

            $table->string('invoice_number')->unique(); // e.g. "INV-2024-00001"
            $table->enum('type', ['invoice', 'credit_note', 'proforma', 'packing_list'])
                  ->default('invoice');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])
                  ->default('draft');

            $table->date('issue_date');
            $table->date('due_date');
            $table->date('paid_date')->nullable();

            $table->string('currency', 3)->default('EUR');
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0); // VAT %
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('balance_due', 14, 2)->default(0);

            $table->string('payment_reference')->nullable();
            $table->string('payment_method')->nullable(); // bank_transfer, cash, card
            $table->text('notes')->nullable();
            $table->json('bank_details')->nullable();

            // PDF storage path
            $table->string('pdf_path')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['customer_id', 'status']);
            $table->index(['tenant_id', 'issue_date']);
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 10, 3)->default(1);
            $table->string('unit', 20)->default('kg'); // kg, pcs, m3, ldm
            $table->decimal('unit_price', 10, 4)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        /**
         * CARI_MOVEMENTS: Double-entry ledger for customer accounts
         * Tracks all financial transactions per customer
         */
        Schema::create('cari_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');

            $table->enum('type', [
                'invoice',      // Borç: Yeni fatura
                'payment',      // Alacak: Ödeme alındı
                'credit_note',  // Alacak: İade/İndirim
                'adjustment',   // Düzeltme
                'opening_balance' // Açılış bakiyesi
            ]);

            $table->string('reference_number')->nullable();
            $table->string('description');
            $table->string('currency', 3)->default('EUR');
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);

            // Debit = Borç (customer owes us), Credit = Alacak (we owe customer)
            $table->decimal('debit_amount', 14, 2)->default(0);
            $table->decimal('credit_amount', 14, 2)->default(0);
            $table->decimal('balance_after', 14, 2)->default(0); // Running balance

            $table->date('transaction_date');
            $table->date('value_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_reconciled')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'customer_id', 'transaction_date']);
            $table->index(['customer_id', 'is_reconciled']);
        });

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('from_currency', 3);
            $table->string('to_currency', 3);
            $table->decimal('rate', 12, 6);
            $table->date('rate_date');
            $table->timestamps();

            $table->unique(['tenant_id', 'from_currency', 'to_currency', 'rate_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
        Schema::dropIfExists('cari_movements');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
