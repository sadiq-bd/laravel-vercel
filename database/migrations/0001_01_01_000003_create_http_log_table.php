<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('http_log', function (Blueprint $table) {
            $table->id();
            $table->string('id_token', 100);
            $table->ipAddress('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('request_method', 10);
            $table->text('request_uri');
            $table->text('request_body')->nullable();
            $table->json('request_headers')->nullable();
            $table->json('response_headers')->nullable();
            $table->integer('status_code')->nullable();
            $table->integer('response_size')->nullable();
            $table->float('response_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('http_log');
    }
};
