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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('category');
            $table->float('price');
            $table->string('shopee_url')->nullable();
            $table->text('main_description');
            $table->text('product_information')->nullable();
            $table->text('material_used')->nullable();
            $table->string('main_photo');
            $table->string('thumbnail_main_photo');
            $table->string('additional_photo_1')->nullable();
            $table->string('thumbnail_additional_photo_1')->nullable();
            $table->string('additional_photo_2')->nullable();
            $table->string('thumbnail_additional_photo_2')->nullable();
            $table->string('additional_photo_3')->nullable();
            $table->string('thumbnail_additional_photo_3')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
