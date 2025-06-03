<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class AddAddressToOrdersTable extends Migration
  {
      public function up()
      {
          Schema::table('orders', function (Blueprint $table) {
              $table->string('address')->nullable()->after('delivery_date');
          });
      }

      public function down()
      {
          Schema::table('orders', function (Blueprint $table) {
              $table->dropColumn('address');
          });
      }
  }