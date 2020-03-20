<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->timestamps();
        });

        Schema::create('faq_feedback', function(Blueprint $table) {
            $table->id();
            $table->foreignId('faq_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('type');
            $table->text('feedback')->nullable();
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
        Schema::table('faq_feedback', function (Blueprint $table) {
            $table->dropForeign(['faq_id']);
        });

        Schema::dropIfExists('faq_feedback');
        Schema::dropIfExists('faqs');
    }
}
