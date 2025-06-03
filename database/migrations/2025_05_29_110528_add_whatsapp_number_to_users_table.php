<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   class AddWhatsappNumberToUsersTable extends Migration
   {
       public function up()
       {
           Schema::table('users', function (Blueprint $table) {
               $table->string('whatsapp_number')->after('email')->nullable();
           });
       }

       public function down()
       {
           Schema::table('users', function (Blueprint $table) {
               $table->dropColumn('whatsapp_number');
           });
       }
   }