<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use App\Models\SourcePost;
use Illuminate\Http\Request;

class SourcePostController extends Controller
{
    public function index(
        Source $source,
        string $status
    ) {
        $query = SourcePost::query()
            ->where(
                'source_id',
                $source->id
            );


        if ($status === 'unread') {

            $query->where(
                'is_read',
                false
            );
        } else {

            $query->where(
                'is_read',
                true
            );
        }


        $posts = $query
            ->orderByDesc('published_at')
            ->get();


        return view(
            'admin.source-posts.index',
            compact(
                'source',
                'status',
                'posts'
            )
        );
    }
    public function markRead(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => false,
            ]);
        }

        SourcePost::whereIn('id', $ids)
            ->update([
                'is_read' => true,
            ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
