<?php

namespace App\Http\Controllers;

use Exception;
use App\Classes\Helper;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

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
            DB::beginTransaction();

            $category = new Category();
            $category->name   = $request->name;
            $category->status = $request->status;
            $category->save();

            DB::commit();

            return Helper::sendResponse($category, "Category Created Successfully");

        } catch (Exception $th) {
            DB::rollBack();
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
