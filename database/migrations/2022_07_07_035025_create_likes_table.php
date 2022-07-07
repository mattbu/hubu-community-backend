<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            // 누가 댓글을 달았는지
            $table->unsignedBigInteger('user_id');
            // 어디에 댓글이 달렸는지
            $table->unsignedBigInteger('task_id');
            // 좋아요 여부
            $table->boolean('is_like')->default(false)->nullable();
            // deleted_at 만들어줌, DB에서 지우지 않고 지운 시간을 저장, eloquent로 부를 때는 제외
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->on('users')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('task_id')->on('tasks')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
