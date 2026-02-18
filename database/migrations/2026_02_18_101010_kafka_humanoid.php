<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kafka_humanoid', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable(false);
            $table->boolean('sex')->nullable(false);
            $table->unsignedInteger('born')->nullable(false);
            $table->unsignedInteger('child_at')->nullable(false);
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->unsignedBigInteger('father_id')->nullable();

            $table->index('name', 'kh_name');
            $table->index(['born', 'sex'], 'kh_born');

            $table->foreign('mother_id', 'fk_mother')->references('id')->on('kafka_humanoid')->nullOnDelete();
            $table->foreign('father_id', 'fk_father')->references('id')->on('kafka_humanoid')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kafka_humanoid');
    }
};
