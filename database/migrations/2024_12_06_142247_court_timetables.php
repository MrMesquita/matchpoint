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
        Schema::create('court_timelables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_court')->constrained('courts')->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['AVAILABLE', 'BUSY'])->default('AVAILABLE');
            $table->timestamps();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('court_timelables');
    }
};
