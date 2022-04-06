<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->text('achievement');
            $table->string('type');
            $table->integer('next')->nullable(true);
            $table->integer('required');
        });

        DB::table('achievements')->insert([
            [
                'id' => 1,
                'achievement' => 'First Lesson Watched',
                'type' => 'lesson',
                'next' => 2,
                'required' => 1
            ],
            [
                'id' => 2,
                'achievement' => '5 Lessons Watched',
                'type' => 'lesson',
                'next' => 3,
                'required' => 5
            ],
            [
                'id' => 3,
                'achievement' => '10 Lessons Watched',
                'type' => 'lesson',
                'next' => 4,
                'required' => 10
            ],
            [
                'id' => 4,
                'achievement' => '25 Lessons Watched',
                'type' => 'lesson',
                'next' => 5,
                'required' => 25
            ],
            [
                'id' => 5,
                'achievement' => '50 Lessons Watched',
                'type' => 'lesson',
                'next' => null,
                'required' => 50
            ],
            [
                'id' => 6,
                'achievement' => 'First Comment Written',
                'type' => 'comments',
                'next' => 7,
                'required' => 1
            ],
            [
                'id' => 7,
                'achievement' => '3 Comments Written',
                'type' => 'comments',
                'next' => 8,
                'required' => 3
            ],
            [
                'id' => 8,
                'achievement' => '5 Comments Written',
                'type' => 'comments',
                'next' => 9,
                'required' => 5
            ],
            [
                'id' => 9,
                'achievement' => '10 Comments Written',
                'type' => 'comments',
                'next' => 10,
                'required' => 10
            ],
            [
                'id' => 10,
                'achievement' => '20 Comments Written',
                'type' => 'comments',
                'next' => null,
                'required' => 20
            ],
            [
                'id' => 11,
                'achievement' => 'Beginner: 0 Achievements',
                'type' => 'badge',
                'next' => 12,
                'required' => 0
            ],
            [
                'id' => 12,
                'achievement' => 'Intermediate: 4 Achievements',
                'type' => 'badge',
                'next' => 13,
                'required' => 4
            ],
            [
                'id' => 13,
                'achievement' => 'Advanced: 8 Achievements',
                'type' => 'badge',
                'next' => 14,
                'required' => 8
            ],
            [
                'id' => 14,
                'achievement' => 'Master: 10 Achievements',
                'type' => 'badge',
                'next' => null,
                'required' => 10
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('achievements');
    }
}
