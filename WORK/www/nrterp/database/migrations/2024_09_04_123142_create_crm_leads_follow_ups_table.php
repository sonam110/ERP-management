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
        Schema::create('crm_leads_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');

            $table->string('title');
            $table->string('description')->nullable();
            $table->date('date');
            $table->string('time');
            $table->string('sources')->nullable();
            $table->integer('pipeline_id')->nullable();
            $table->integer('stage_id')->nullable();
            $table->boolean('status')->default(0)->nullable()->comment('0:Not Done,1:Done');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_leads_follow_ups');
    }
};
