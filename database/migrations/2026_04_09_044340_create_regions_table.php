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
        Schema::create('stunting_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('kab/kota', 100);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            $table->decimal('stunting', 5, 2)->nullable();
            $table->decimal('hamil', 5, 2)->nullable();
            $table->decimal('tambah_darah', 5, 2)->nullable();
            $table->decimal('persalinan_nakes', 5, 2)->nullable();
            $table->decimal('vit_a', 5, 2)->nullable();
            $table->decimal('bblr', 5, 2)->nullable();
            $table->decimal('imd', 5, 2)->nullable();
            $table->decimal('asi_baduta', 5, 2)->nullable();
            $table->decimal('lama_asi', 5, 2)->nullable();
            $table->decimal('asi_pendamping', 5, 2)->nullable();
            $table->decimal('imunisasi', 5, 2)->nullable();
            
            $table->decimal('vit_a_6_11', 5, 2)->nullable();
            $table->decimal('vit_a_12_59', 5, 2)->nullable();
            $table->decimal('vit_a_6_59', 5, 2)->nullable();
            
            $table->decimal('cakupan', 5, 2)->nullable();
            $table->decimal('jiwa_rt', 5, 2)->nullable();
            $table->decimal('sanitasi', 5, 2)->nullable();
            $table->decimal('air_minum', 5, 2)->nullable();
            $table->decimal('ipm', 5, 2)->nullable();
            $table->decimal('miskin', 5, 2)->nullable();
            
            $table->integer('lisa_cluster')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stunting_indicators');
    }
};
