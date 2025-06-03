<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryDetailsToCartsTable extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->date('delivery_date')->after('quantity')->nullable();
            $table->string('delivery_method')->after('delivery_date')->nullable(); // 'pickup' or 'delivery'
            $table->text('address')->after('delivery_method')->nullable();
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['delivery_date', 'delivery_method', 'address']);
        });
    }
}