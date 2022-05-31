<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use DB;
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $allTasks = Task::orderBy('id', 'DESC')->get();
        return $allTasks;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
//        $task = new Task;
//        $task->title = $request->title;

        $newTask = Task::create([
            'title' => $request->title,
            'description' => $request->description,

        ]);
        return $newTask;

//        $userInputData = $request->all();
//
//        $newTask = Task::create($userInputData);
//        return $newTask;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $task = Task::where('id', $id)->first();
        return $task;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
//        $validator = $request->validate([
//            'title' => 'required',
//            'description' => 'required',
//        ]);

//        if ($validator->fails()) {
//            return response()->json(['data'=>$validator],200,[],JSON_PRETTY_PRINT);
//        }

        $findTask =  Task::where('id', $id)->update([
            'title'=>     $request->title,
            'description'=>$request->description
        ]);
        return response()->json(['data' => $findTask],200,[],JSON_PRETTY_PRINT);;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (Task::where('id',$id)->first()) { // 게시글이 있을 때만
            $findTask = Task::where('id', $id)->delete();
        }
        return $findTask;
    }
}
