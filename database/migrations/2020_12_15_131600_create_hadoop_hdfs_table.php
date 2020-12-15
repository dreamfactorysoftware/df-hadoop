<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateHadoopHDFSTable extends Migration
{
    /**
     * Run the migrations. To create example database table.
     *
     * @return void
     */
    public function up()
    {
        // AWS Service Configuration
        Schema::create(
            'hdfs_config',
            function (Blueprint $t){
                $t->integer('service_id')->unsigned()->primary();
                $t->foreign('service_id')->references('id')->on('service')->onDelete('cascade');

                $t->string('host')->nullable();
                $t->boolean('use_ssl')->nullable();
                $t->integer('port')->nullable();
                $t->string('user')->nullable();
                $t->string('namenode_rpc_host')->nullable();
                $t->integer('namenode_rpc_port')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop created tables in reverse order

        // Database Extras
        Schema::dropIfExists('hdfs_config');
    }
}
