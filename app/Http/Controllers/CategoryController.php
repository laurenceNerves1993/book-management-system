<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
      $categories = Category::orderBy("id", "desc")->get();
      return view("categories.index", compact("categories"));
    }

    public function store(Request $request) {
      $request->validate([ 'newCategory' => 'required|string|max:255', ]); // For form validation
      $category = Category::create([ 'name' => $request->newCategory, ]);
      return response()->json(['alertMessage' => 'Category added successfully!'], 201);
    }

    public function destroy($id) {
        $category = Category::find($id); // to get the category data
        if (!$category) {
            return response()->json(['alertMessage' => 'Category not found'], 404);
        } else {
            $category->delete(); // Database deletion
            return response()->json(['alertMessage' => 'Category deleted successfully!']);
        }
    }
  
    public function update(Request $request, $id) {
        $request->validate(['updateCategory' => 'required|string|max:255']);
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['alertMessage' => 'Category not found'], 404);
        } else {
            $category->name = $request->updateCategory;
            $category->save();

            return response()->json(['alertMessage' => 'Category updated successfully!']);
        }
    }

    public function getCategories() {
        $categories = Category::orderBy("name", "asc")->get();
        return response()->json($categories);
    }
}
