<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function index(Request $request)
    {
        $allTasks = Task::with('user')->with('comments')
            ->orderBy('created_at', $request->order_by ?? 'desc')->orderBy('id', 'desc')->paginate(3);

        $allTasks->map(function ($e) {
            $e['likes'] = Task::find($e->id)->likes()->where('is_like', 1)->count();
            return $e;
        });

        return response()->json($allTasks,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function store(Request $request) {
        $user = Auth::user()->id;

        $valid = validator($request->only('title', 'description'), [
            'title' => 'string',
            'description' => 'string',
        ]);

        if ($valid->fails()) {
            return response()->json([
                'message' => '제목과 내용을 입력해 주세요.'
            ], 500);
        } else {
            $newTask = Task::create([
                'user_id' => $user,
                'title' => $request->title,
                'description' => $request->description,
            ]);
            return response()->json([
                'message' => '등록이 완료 되었습니다.',
                'data' => $newTask
            ], 200);
        }
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
        $task = Task::where('id', $id)->with('user', 'comments.replies')->first();
        $is_liked_by_me = Like::where('user_id', Auth::id())->where('task_id', $id)->first();

        $likes = Task::find($id)->likes()->where('is_like', 1)->count();

        if ($is_liked_by_me) {
            $task['is_liked_by_me'] = $is_liked_by_me->is_like;
        }

        return response()->json([
            'data' => $task,
            'likes' => $likes
        ],200,[],JSON_PRETTY_PRINT);
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
        $userId = Auth::user()->id;
        $writtenUserId = Task::where('id', $id)->with('user')->first()->user_id;

        $valid = validator($request->only('title', 'description'), [
            'title' => 'string',
            'description' => 'string',
        ]);

        if ($valid->fails()) {
            return response()->json([
                'message' => '제목과 내용을 입력해 주세요.'
            ], 500);
        }

        if ($userId === $writtenUserId) {
            $findTask = Task::where('id', $id)->update([
                'title' => $request->title,
                'description' => $request->description
            ]);
            return response()->json([
                'data' => $findTask,
                'message' => '수정이 완료 되었습니다.'
            ], 200);
        } else {
            return response()->json([
                'message' => '수정 권한이 없습니다.'
            ], 500);
        };
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
        $userId = Auth::user()->id;
        $writtenUserId = Task::where('id', $id)->with('user')->first()->user_id;

        if ($userId === $writtenUserId && Task::where('id', $id)->first()) { // 게시글이 있을 때만
            $findTask = Task::where('id', $id)->delete();
            return response()->json([
                'message' => '삭제가 완료 되었습니다.'
            ], 200);
        } else {
            return response()->json([
                'message' => '삭제 권한 혹은 게시글이 없습니다.'
            ], 422);
        }
    }

    public function get_my_likes(Request $request)
    {
        $liked_lists = User::find(Auth::id())->likes()->where('is_like', 1)
            ->with('user')->with('comments')->orderBy('created_at', $request->order_by)->orderBy('id', 'desc')->paginate(3);

        $liked_lists->map(function ($e) {
            $e['likes'] = Task::find($e->id)->likes()->where('is_like', 1)->count();
            return $e;
        });

        if (count($liked_lists) > 0) {
            $message = '좋아요를 누른 목록 조회가 성공 하였습니다.';
        } else {
            $message = '조회 가능한 좋아요 목록이 없습니다.';
        }

        return response()->json([
            'data' => $liked_lists,
            'message' => $message
        ], 200);
    }
}
