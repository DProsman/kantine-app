<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producten', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->unsignedInteger('prijs'); // in centen!
            $table->string('categorie')->default('overig');
            $table->boolean('beschikbaar')->default(true);
            $table->timestamps();
        });

        Schema::create('tijdsloten', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start'); // altijd in UTC opslaan, tonen via kantineTijd()
            $table->unsignedSmallInteger('capaciteit')->default(20);
            $table->timestamps();
        });

        Schema::create('bestellingen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('tijdslot_id')->constrained('tijdsloten');
            $table->string('status')->default('open'); // open, klaar, afgehaald
            $table->unsignedInteger('subtotaal')->default(0); // centen
            $table->unsignedInteger('korting')->default(0);   // centen
            $table->integer('totaal')->default(0);            // centen
            $table->string('opmerking')->nullable();
            $table->timestamps();
        });

        Schema::create('bestelregels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bestelling_id')->constrained('bestellingen')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('producten');
            $table->unsignedSmallInteger('aantal');
            $table->unsignedInteger('stukprijs'); // centen, prijs op moment van bestellen
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bestelregels');
        Schema::dropIfExists('bestellingen');
        Schema::dropIfExists('tijdsloten');
        Schema::dropIfExists('producten');
    }
};
