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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('list_id');
            $table->string('title', 255);
            $table->longText('description')->nullable()->default(null);
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable()->default(null);
            $table->string('background_color', 7)->nullable()->default(null);
            $table->unsignedBigInteger('background_id')->nullable()->default(null);

            $table->foreign('list_id')->references('id')->on('lists')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
