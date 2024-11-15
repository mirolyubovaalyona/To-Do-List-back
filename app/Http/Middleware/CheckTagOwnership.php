<?php

namespace App\Http\Middleware;

use App\Models\Tag;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTagOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tag = Tag::find($request->route('tag_id'));

        if (!$tag || $tag->user_id !== auth()->id()) {
            return response()->json(['error' => 'Tag found subtask'], 403);
        }

        $request->attributes->set('tag', $tag);

        return $next($request);
    }
}
