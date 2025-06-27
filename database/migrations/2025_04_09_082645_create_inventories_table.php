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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('item_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_type_id')->constrained('item_types');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        Schema::create('allocate_hardwares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
        
            $table->integer('desk_number')->nullable();
            $table->unique(['location_id', 'desk_number']);

            $table->foreignId('computer_id')->nullable()->constrained('inventories');
            $table->foreignId('disk_drive_1_id')->nullable()->constrained('inventories');
            $table->foreignId('disk_drive_2_id')->nullable()->constrained('inventories');
            $table->foreignId('processor_id')->nullable()->constrained('inventories');
            $table->foreignId('vga_card_id')->nullable()->constrained('inventories');
            $table->foreignId('ram_id')->nullable()->constrained('inventories');
            $table->foreignId('monitor_id')->nullable()->constrained('inventories');
        
            $table->year('year_approx')->nullable(); // Tahun (approx)
            $table->enum('ups_status', ['Active', 'Inactive'])->nullable()->default(null);

            $table->string('qr_code')->nullable();
            
            $table->timestamps();
        });

        Schema::create('allocate_others', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->foreignId('others_id')->constrained('inventories');
            $table->string('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });

        Schema::create('transfer_logs', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('from_location');
            $table->string('to_location');
            $table->integer('quantity')->default(1);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
        Schema::dropIfExists('item_types');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('allocate_hardwares');
        Schema::dropIfExists('allocate_others');
        Schema::dropIfExists('transfer_logs');
    }
};
