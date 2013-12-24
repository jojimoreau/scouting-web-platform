<?php

use Illuminate\Database\Migrations\Migration;

class CreateDatabase extends Migration {
  
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
    Schema::create('sections', function($table) {
      $table->increments('id');
      $table->string('name');
      $table->string('section_type');
      $table->string('section_type_number');
      $table->string('color');
      $table->string('email');
      $table->string('de_la_section');
      $table->string('la_section');
      $table->text('last_email_content');
    });
    DB::table('sections')->insert(array(
        'id' => 1,
        'name' => 'Unité',
        'section_type' => 'U',
        'section_type_number' => 0,
        'color' => "#000000",
        'email' => '',
        'de_la_section' => "de l'unité",
        'la_section' => "l'unité",
        'last_email_content' => "",
    ));
		Schema::create('pages', function($table) {
      $table->increments('id');
      $table->string('type');
      $table->integer('section_id')->unsigned();
      $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
      $table->text('content');
    });
    DB::table('pages')->insert(array(
        'type' => 'accueil',
        'section_id' => 1,
        'content' => "<h1>Page d'accueil de l'unité</h1><p>Bienvenue.</p>",
    ));
	}
  
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('pages');
    Schema::drop('sections');
	}
  
}