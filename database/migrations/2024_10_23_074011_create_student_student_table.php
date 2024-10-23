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
        Schema::create('student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('phone');
            $table->date('enrollment_date');
            $table->enum('status', ['on_leave', 'graduated',  'dismissed','dropout'])->default('on_leave');
            $table->integer('student_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};
