<?php

namespace App\Http\Controllers;

use App\Classes\Helper;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::get();
            return Helper::sendResponse($categories, "All Category list data");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }

    public function store(CategoryStoreRequest $request){
        try {
            $category = new Category();
            $category->name   = $request->name;
            $category->status = $request->status;
            $category->save();

            return Helper::sendResponse($category, "Category Created Successfully");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $category = Category::where('id', $id)->first();
            $category->name  = $request->name;
            $category->status = $request->status;
            $category->save();

            return Helper::sendResponse($category, "Category Updated Successfully");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::where('id', $id)->first();
            $category->delete();

            return Helper::sendResponse(null, "Category Deleted Successfully");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }
}
