<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameDeliveryMethodToDeliveryOptionInCartsTable extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->renameColumn('delivery_method', 'delivery_option');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->renameColumn('delivery_option', 'delivery_method');
        });
    }
}