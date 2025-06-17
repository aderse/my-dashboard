<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToDo;

class ToDoController extends Controller
{

    public function create() {

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        
        $todo = $request->user()->todos()->create($validated);

        return response()->json($todo, 201);
    }

    public function destroy(Request $request, Todo $todo)
    {
        // Belt-and-suspenders: make sure the Todo belongs to this user
        abort_if($todo->user_id !== $request->user()->id, 403);

        $todo->delete();

        return response()->noContent();      // 204
    }
}
