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
        Schema::create('academic_intakes', function (Blueprint $table) {
            $table->id();
            $table->string('intake_type');
            $table->string('intake_name');
            $table->string('academic_year');
            $table->date('admission_start');
            $table->date('admission_deadline');
            $table->decimal('post_fees', 10, 2)->nullable();
            $table->decimal('other_fees', 10, 2)->nullable();
            $table->boolean('academic_status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_intakes');
    }
};