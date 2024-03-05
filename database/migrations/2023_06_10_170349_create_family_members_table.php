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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('family_card_id')->nullable();
            $table->string('family_member_name');
            $table->char('nik', 16);
            $table->string('gender');
            $table->string('birth_place');
            $table->string('birth_date');
            $table->string('job');
            $table->string('religious');
            $table->string('education');
            $table->string('citizenship');
            $table->string('family_status');
            $table->string('marital_status');
            $table->text('address');
            $table->text('avatar')->nullable();
            $table->boolean('verified')->default(0);
            $table->string('status')->default('aktif');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('family_card_id')->references('id')->on('family_cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
