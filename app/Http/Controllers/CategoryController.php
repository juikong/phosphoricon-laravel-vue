<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhosphorIcon;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::get();
        $categoriesWithIcon = PhosphorIcon::getIcon($categories);

        return response()->json($categoriesWithIcon);
    }

    public function update(Request $request): JsonResponse
    {
        $category = Category::query()->findOrFail($request->integer('id'));
        $category->update([
            'phosphor_icon_id' => $request->integer('phosphor_icon_id'),
        ]);

        return response()->json(['message' => 'Category Updated Successfully!']);
    }
}
