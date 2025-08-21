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
        // =============================
        // Locations
        // =============================
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // =============================
        // Master Inventories (Jenis Barang)
        // =============================
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->enum('category', [
                'Computer',
                'Disk Drive',
                'Processor',
                'VGA',
                'RAM',
                'Monitor',
                'Other'
            ]);
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedInteger('total_quantity')->default(0);
            $table->timestamps();
        });

        // =============================
        // Inventory Items (Per Unit Barang)
        // =============================
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->string('serial_number')->nullable();
            $table->enum('condition_status', ['Baik', 'Perlu Perbaikan', 'Rusak'])->default('Baik');
            $table->date('received_date')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->foreignId('last_checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status_allocate', ['available', 'allocated'])->default('available');
            $table->timestamps();
        });

        // =============================
        // Allocate Hardwares
        // =============================
        Schema::create('allocate_hardwares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            
            $table->integer('desk_number')->nullable();
            $table->unique(['location_id', 'desk_number']);

            // Semua mengacu ke per unit (inventory_items)
            $table->foreignId('computer_id')->nullable()->constrained('inventory_items');
            $table->foreignId('disk_drive_1_id')->nullable()->constrained('inventory_items');
            $table->foreignId('disk_drive_2_id')->nullable()->constrained('inventory_items');
            $table->foreignId('processor_id')->nullable()->constrained('inventory_items');
            $table->foreignId('vga_card_id')->nullable()->constrained('inventory_items');
            $table->foreignId('ram_id')->nullable()->constrained('inventory_items');
            $table->foreignId('monitor_id')->nullable()->constrained('inventory_items');
            
            $table->year('year_approx')->nullable();
            $table->enum('ups_status', ['Active', 'Inactive'])->nullable()->default(null);

            $table->string('qr_code')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });

        // =============================
        // Allocate Other Items
        // =============================
        Schema::create('allocate_others', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained('inventory_items');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // =============================
        // Transfer Logs
        // =============================
        Schema::create('transfer_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items');
            $table->string('from_location');
            $table->string('to_location');
            $table->text('note')->nullable();
            $table->foreignId('transferred_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // =============================
        // Audit Logs
        // =============================
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('action'); // CREATE, UPDATE, DELETE
            $table->string('table_name');
            $table->unsignedBigInteger('record_id');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('transfer_logs');
        Schema::dropIfExists('allocate_others');
        Schema::dropIfExists('allocate_hardwares');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('locations');
    }
};
