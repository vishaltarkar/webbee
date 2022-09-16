<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('about');
            $table->string('poster');
            $table->string('trailer_url');
            $table->number('duration')->comment('In minutes');
            $table->string('languages')->comment('Example: ["english", "hindi"]'); // Reference ids can be used or separate table to manage
            $table->string('genre')->comment('Example: ["action", "horror"]'); // Reference ids can be used or separate table to manage
            $table->string('screen_type')->comment('Example: ["3D", "2D"]'); // Reference ids can be used or separate table to manage
            $table->date('release_on');
            $table->string('certificate');
            $table->timestamps();
        });

        Schema::create('casts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo');
            $table->timestamps();
        });

        Schema::create('crews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo');
            $table->timestamps();
        });

        // Movie-Cast Mapping Table
        Schema::create('movie-casts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('movie_id');
            $table->foreign('movie_id')->references('id')->on('moveis')->onDelete('restrict');

            $table->unsignedBigInteger('cast_id');
            $table->foreign('cast_id')->references('id')->on('casts')->onDelete('restrict');

            $table->timestamps();
        });

        // Movie-Crew Mapping Table
        Schema::create('movie-crews', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('movie_id');
            $table->foreign('movie_id')->references('id')->on('moveis')->onDelete('restrict');

            $table->unsignedBigInteger('crew_id');
            $table->foreign('crew_id')->references('id')->on('crews')->onDelete('restrict');

            $table->timestamps();
        });


        // Show Tables
        Schema::create('movie-shows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id');
            $table->foreign('movie_id')->references('id')->on('moveis')->onDelete('restrict');
            $table->date('show_date');
            $table->time('show_time');
            $table->decimal('ticket_price', 8, 2);
            $table->number('total_seats');
            $table->number('booked_seats');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('show_id');
            $table->foreign('show_id')->references('id')->on('movie-shows')->onDelete('restrict');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            $table->integer('total_tickets');

            $table->timestamps();
        });

        // I haven't put much thought about seat booking and their different type

        // throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
