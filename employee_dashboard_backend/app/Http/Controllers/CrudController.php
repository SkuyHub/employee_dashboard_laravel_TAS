<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CrudController extends Controller
{
    protected function resolveModel(string $model): string
    {
        $class = 'App\\Models\\' . Str::studly(Str::singular($model));
        if (!class_exists($class)) {
            abort(404, "Model [{$class}] not found");
        }
        return $class;
    }

    public function index(Request $request, string $model)
    {
        $class = $this->resolveModel($model);
        return response()->json($class::paginate(10));
    }

    public function store(Request $request, string $model)
    {
        $class = $this->resolveModel($model);
        $instance = $class::create($request->all());
        return response()->json($instance, 201);
    }

    public function show(string $model, int $id)
    {
        $class = $this->resolveModel($model);
        return response()->json($class::findOrFail($id));
    }

    public function update(Request $request, string $model, int $id)
    {
        $class = $this->resolveModel($model);
        $instance = $class::findOrFail($id);
        $instance->update($request->all());
        return response()->json($instance);
    }

    public function destroy(string $model, int $id)
    {
        $class = $this->resolveModel($model);
        $instance = $class::findOrFail($id)->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}
