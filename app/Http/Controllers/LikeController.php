<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle_likes(Request $request)
    {
        $exist_like = Like::where('task_id', $request->id)->where('user_id', Auth::id())->withTrashed()->first();

        if ($exist_like) {
            if ($exist_like->deleted_at) {
                $exist_like->update([
                    'is_like' => true
                ]);
                $exist_like->restore();
                $message = '좋아요가 등록 되었습니다.';
            } else {
                $exist_like->update([
                    'is_like' => false
                ]);
                $exist_like->delete();
                $message = '좋아요를 취소 하셨습니다.';
            }
        } else {
            Like::create([
                'task_id' => $request->id,
                'user_id' => Auth::id(),
                'is_like' => true
            ]);
            $message = '';
        }

        return response()->json([
            'message' => $message
        ],200);
    }
}
