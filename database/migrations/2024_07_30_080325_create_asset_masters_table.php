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
        Schema::create('asset_masters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unique_id');
            $table->decimal('price',15,2)->default(0.00);
            $table->integer('available_quantity')->default(0);
            $table->date('purchase_date')->nullable();
            $table->date('supported_date')->nullable();
            $table->text('description')->nullable();
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_masters');
    }
};
