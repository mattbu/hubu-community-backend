<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = User::get()->random(5)->pluck('id')->toArray();

        foreach ($user_ids as $user_id) {
            $how_many_task = rand(0,3);
            $task_ids = Task::get()->random($how_many_task);
            foreach ($task_ids as $task_id){
                $is_like = rand(0,1);

                $is_like === 1 ? $deleted_at = null : $deleted_at = Carbon::now();


                    Like::create([
                    'user_id'       => $user_id,
                    'task_id'       => $task_id->id,
                    'is_like'       => (integer)$is_like,
                        'deleted_at' => $deleted_at
            ]);

            }
        }



//        Like::factory()->count(10)->create();
    }
}
