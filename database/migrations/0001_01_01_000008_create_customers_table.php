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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // Tên khách hàng
            $table->string('email')->unique()->nullable();     // Email duy nhất
            $table->string('phone')->nullable();   // SĐT
            $table->date('dob')->nullable(); 
            $table->string('note')->nullable();   // SĐT      // Ngày sinh
            $table->enum('gender', ['male','female','other'])->nullable(); // Giới tính
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái 
            $table->foreignId('customer_type_id')->nullable()->constrained()->onDelete('set null'); // Loại khách hàng
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
