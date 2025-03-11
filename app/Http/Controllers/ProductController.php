<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Classes\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::get();
            return Helper::sendResponse($products, "All Product list data");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }

    public function store(ProductStoreRequest $request){
        try {
            $product = new Product();
            $product->name   = $request->name;
            $product->status = $request->status;

            if ($request->hasFile('image')) {
                $image    = $request->file('image');
                $filename = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
                $folder   = 'uploads/products';

                if (!file_exists(public_path($folder))) {
                    mkdir(public_path($folder), 0777, true);
                }

                $image->move(public_path($folder), $filename);
                $product->image = url("$folder/$filename");
            }

            $product->save();

            return Helper::sendResponse($product, "Product Created Successfully");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        try {
            $product = Product::where('id', $id)->first();
            $product->name  = $request->name;
            $product->status = $request->status;
            $product->save();

            return Helper::sendResponse($product, "Product Updated Successfully");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::where('id', $id)->first();
            if ($product->image) {
                $imagePath = public_path(str_replace(url('/'), '', $product->image));
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $product->delete();

            return Helper::sendResponse(null, "Product Deleted Successfully");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }
}
