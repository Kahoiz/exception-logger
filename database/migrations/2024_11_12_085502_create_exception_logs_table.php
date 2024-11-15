<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exception_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('message');
            $table->string('file');
            $table->integer('line');
            $table->text('trace');
            $table->string('sessionuid');
            $table->string('environment');
            $table->timestamp('thrown_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exception_logs');
    }
};
