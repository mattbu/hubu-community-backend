<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
//use DB;
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function index()
    {
        //
        $allTasks = Task::with('user')->orderBy('id', 'DESC')->get();

        return response()->json($allTasks,200,[],JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function store(Request $request) {
       $user = Auth::user()->id;
        $newTask = Task::create([
            'user_id' => $user,
            'title' => $request->title,
            'description' => $request->description,

        ]);
        return response()->json($newTask,200,[],JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function show($id)
    {
        //
        $task = Task::where('id', $id)->first();
        return response()->json($task,200,[],JSON_PRETTY_PRINT);
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
        return response()->json($findTask,200,[],JSON_PRETTY_PRINT);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function destroy($id)
    {
        //
        if (Task::where('id',$id)->first()) { // 게시글이 있을 때만
            $findTask = Task::where('id', $id)->delete();
        }
        return response()->json($findTask,200,[],JSON_PRETTY_PRINT);
    }
}
