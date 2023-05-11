<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use Illuminate\Http\Request;
use App\Models\Todo;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $query = Todo::where('user_id', Auth::id());
        if ($request->has('search')) {
            $query->where('title', 'LIKE', '%' . $request->input('search') . '%');
        }

        $todos = $query->paginate(10);

        return response()->json($todos);
    }

    public function store(StoreTodoRequest $request)
    {
        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Todo created successfully.', 'data' => $todo], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($todo);
    }

    public function update(StoreTodoRequest $request, $id)
    {
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->save();

        return response()->json(['message' => 'Todo updated successfully.', 'data' => $todo]);
    }

    public function destroy($id)
    {
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();
        return response()->json(['message' => 'Todo deleted successfully.']);
    }
}
