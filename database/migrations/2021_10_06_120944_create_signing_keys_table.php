<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSigningKeysTable extends Migration
{
    public function up()
    {
        Schema::create('signing_keys', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table
                ->unsignedInteger('revision')
                ->unique();

            $table->text('key'); // binary, base64

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('signing_keys');
    }
}
