<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('company_id')->constrained()->onDelete('cascade');
        $table->decimal('price', 8, 2);
        $table->integer('stock');
        $table->text('comment')->nullable();
        $table->string('image')->nullable();
        $table->timestamps();
    });
}
};

