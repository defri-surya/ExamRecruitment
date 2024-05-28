<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Product::paginate(5);

        return response()->json($data);
    }

    public function search($name)
    {
        $data = Product::where('name', 'like', "%$name%")
            ->orWhere('description', 'like', "%$name%")
            ->orWhere('price', 'like', "%$name%")
            ->paginate(5);

        if ($data->isEmpty()) {
            return response()->json(['message' => 'No results found'], 404);
        }

        return response()->json([
            'message' => 'Search results',
            'data' => $data,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:products|max:255',
            'description' => 'required|unique:products|string',
            'price' => 'required|integer|unique:products',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $product = Product::create($validator->validated());
        return response()->json([
            'message' => 'Product successfully created',
            'data' => $product,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|unique:products|max:255',
            'description' => 'unique:products|string',
            'price' => 'integer|unique:products',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $product->update($validator->validated());
        return response()->json([
            'message' => 'Product successfully updated',
            'data' => $product,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Product successfully deleted'], 200);
    }
}
