<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrustAnchorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trust_anchors', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('certificate_type');
            $table->string('country');
            $table->string('kid');
            $table->text('certificate');
            $table->text('signature');
            $table->string('thumbprint');
            $table->dateTime('timestamp');

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
        Schema::dropIfExists('trust_anchors');
    }
}
