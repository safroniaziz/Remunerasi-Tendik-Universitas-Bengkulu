<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenggunaRubriksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengguna_rubriks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id')->constrained('units');
            $table->unsignedBigInteger('rubrik_id')->constrained('rubriks');
            $table->enum('status',['aktif','nonaktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengguna_rubriks');
    }
}
