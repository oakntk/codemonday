<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function storeStandalone(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string',
        ]);

        $category = Category::create([
            'category_name' => $validated['category_name'],
            'category_id' => Str::uuid(),
        ]);

        return response()->json($category, 201);
    }

    public function storeLeaf(Request $request, $parentId)
    {
        $validated = $request->validate([
            'category_name' => 'required|string',
        ]);

        $parentCategory = Category::findOrFail($parentId);

        $category = $parentCategory->children()->create([
            'category_name' => $validated['category_name'],
            'category_id' => Str::uuid(),
        ]);

        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::where('category_id', $id)->firstOrFail();
        return response()->json($category);
    }

    public function showTree($id)
    {
        $category = Category::where('category_id', $id)->firstOrFail();
        $tree = $this->buildTree($category);
        return response()->json($tree);
    }

    private function buildTree($category)
    {
        $children = $category->children;
        $categoryArray = $category->toArray();
        $categoryArray['children'] = $children->map(function ($child) {
            return $this->buildTree($child);
        });
        return $categoryArray;
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function destroy($id)
    {
        $category = Category::where('category_id', $id)->firstOrFail();
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
