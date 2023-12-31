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
        Schema::create('voided_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registered_member_id');
            $table->foreignId('member_id');
            $table->foreignId('user_id');
            $table->text('note')->nullable();
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voided_members');
    }
};
