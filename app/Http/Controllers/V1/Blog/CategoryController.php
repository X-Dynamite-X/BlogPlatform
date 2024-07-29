<?php

namespace App\Http\Controllers\V1\Blog;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $category = Category::all();
        return response()->json(["message" => "success", "category" => $category]);
    }

    /**
     * Show the form for creating a new resource.
     */
      public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json(["message" => "catagory is Success Created ", "category" => $category]);
    }


    public function show(Category $category)
    {
        //
        return response()->json(["message"=>"Update Success Category","category"=>$category]);

    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
        $category->update($request->validated());
        return response()->json(["message"=>"Update Success Category","category"=>$category]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Category $category)
    {
        if(!$category){
            return response()->json(["message"=>"category is not exists"], 404);
        }
        $category->delete();
        return  response()->json(["message"=>"Success Delete Category"]) ;
    }
}
