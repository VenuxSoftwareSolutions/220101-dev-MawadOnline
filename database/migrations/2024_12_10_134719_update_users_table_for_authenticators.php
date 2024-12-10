<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('authenticator_id')->nullable()->after('referred_by');

            $table->foreign('authenticator_id')->references('id')->on('account_authenticators')
                ->onDelete('set null');

            $table->dropColumn('provider');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider', 255)->nullable()->after('referred_by');

            $table->dropForeign(['authenticator_id']);
            $table->dropColumn('authenticator_id');
        });
    }
};
