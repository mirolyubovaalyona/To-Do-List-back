<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubtaskOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $task = $request->attributes->get('task');
        $subtask = $task->subtasks()->find($request->route('subtask_id'));

        if (is_null($subtask)) {
            return response()->json('Subtasknot found subtask', 404);;
        }

        $request->attributes->set('subtask', $subtask);

        return $next($request);
    }
}
