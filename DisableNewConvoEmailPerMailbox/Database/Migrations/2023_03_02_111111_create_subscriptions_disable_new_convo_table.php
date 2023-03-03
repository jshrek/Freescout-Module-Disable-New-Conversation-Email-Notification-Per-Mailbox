<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
  
class CreateSubscriptionsDisableNewConvoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('subscriptions_disable_new_convo')) {
            // table exists already
        } else {
            // table does not exist // ?? does bigIncrements include $table->primary('id'); ??
            Schema::create('subscriptions_disable_new_convo', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->unsigned();
                $table->integer('mailbox_id')->unsigned();
                $table->text('note');
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions_disable_new_convo');
    }
}