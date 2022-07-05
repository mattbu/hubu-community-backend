<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function index(Request $request, $task_id)
    {
        //
        $comments = Comment::where('task_id', $task_id)->Where('depth', 0)->with('user', 'replies.user')->orderBy('id', 'DESC')->get();

        return response()->json($comments, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function store(Request $request, $task_id){

        $valid = validator($request->only('comment'), [
            'comment' => 'string',
        ]);

        if ($valid->fails()) {
            return response()->json([
                'message' => '댓글을 입력해 주세요.'
            ], 500);
        }

        $createdComment = Comment::create([
            'user_id' => $request->user_id,
            'task_id' => $task_id,
            'comment' => $request->comment,
            'depth' => 0,
        ]);
        return response()->json([
            'data' => $createdComment,
            'message' => '댓글이 등록되었습니다.'
        ], 200);
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function deleteComment($id)
    {
        $userId = Auth::user()->id;
        $commentedUserId = Comment::where('user_id', $userId)->with('user')->first()->user_id;
        if ($userId === $commentedUserId && Comment::where('id', $id)->first()) {
            Comment::where('id', $id)->delete();
            $message = '댓글 삭제가 완료 되었습니다.';
            $statusCode = 200;
        } else {
            $message = '삭제 권한 혹은 게시글이 없습니다.';
            $statusCode = 422;
        }
        return response()->json([
            'message' => $message
        ], $statusCode);
    }

    public function createReply(Request $request, $id) {

        $valid = validator($request->only('comment'), [
            'comment' => 'string',
        ]);

        if ($valid->fails()) {
            return response()->json([
                'message' => '답글을 입력해 주세요.'
            ], 500);
        }

        $task_id = Comment::find($id)->task_id;

        $newReply = Comment::create([
            'user_id' => $request->user_id,
            'task_id' => $task_id,
            'comment' => $request->comment,
            'depth' => 1,
            'parent_id' => $id
        ]);

        return response()->json([
            'data' => $newReply,
            'message' => '답글이 등록되었습니다.'
        ], 200);
    }
}
