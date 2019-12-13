<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
        $table->increments('id');
        $table->string('email')->index();
        $table->string('token');
        $table->timestamps();
    });

        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('login');
            $table->string('password');
            $table->string('email');
            $table->integer("start_day");
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create("hashes",function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer("id_users")->unsigned();
            $table->foreign("id_users")->references("id")->on("users");
            $table->boolean("if_true")->nullable();
            $table->char("hash",10)->nullable();
            $table->timestamps();

            
        });
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->integer('color')->unsigned()->nullable();
            $table->integer("id_users")->unsigned();
            $table->foreign("id_users")->references("id")->on("users");
            $table->timestamps();
        });
        Schema::create('substances', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->integer('id_users')->unsigned();
            $table->foreign("id_users")->references("id")->on("users");
            $table->float("equivalent")->nullable();
            $table->timestamps();
        });

        

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->integer('id_users')->unsigned();
            $table->foreign("id_users")->references("id")->on("users");
            $table->float("how_percent")->nullable();
            $table->integer("type_of_portion")->unsigned();
            $table->float("price")->nullable();
            $table->integer("how_much")->unsigned()->nullable();
            $table->timestamps();
        });
        Schema::create('usees', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->float('portion');
            $table->integer('id_users')->unsigned();
            $table->foreign("id_users")->references("id")->on("users");
            $table->integer("id_products")->unsigned();
            $table->foreign("id_products")->references("id")->on("products");
            $table->datetime("date");
            $table->integer("type_of_portion")->unsigned();
            $table->float("price");
            $table->timestamps();
        });
        
        Schema::create('descriptions', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->datetime('date');
            $table->text('description');
            $table->integer("id_users")->unsigned();
            $table->foreign("id_users")->references("id")->on("users");
            $table->timestamps();
        });
        Schema::create('forwarding_substances', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_substances')->unsigned();
            $table->foreign("id_substances")->references("id")->on("substances");
            $table->integer('id_products')->unsigned();
            $table->foreign("id_products")->references("id")->on("products");
            $table->timestamps();
        });
        Schema::create('forwarding_groups', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_substances')->unsigned();
            $table->foreign("id_substances")->references("id")->on("substances");
            $table->integer('id_groups')->unsigned();
            $table->foreign("id_groups")->references("id")->on("groups");
            $table->timestamps();
        });
        Schema::create('forwarding_descriptions', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_usees')->unsigned();
            $table->foreign("id_usees")->references("id")->on("usees");
            $table->integer('id_descriptions')->unsigned();
            $table->foreign("id_descriptions")->references("id")->on("descriptions");
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('substances');
        Schema::dropIfExists('products');
        Schema::dropIfExists('usees');
        Schema::dropIfExists('descriptions');
        Schema::dropIfExists('forwarding_descriptions');
        Schema::dropIfExists('forwarding_substances');
        Schema::dropIfExists('forwarding_groups');
        
    }
}
