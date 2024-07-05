<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products=Product::with('user')->get();
        return response()->json([
            'data'=>$products,
           'status'=>'Success',
           'message'=>'Products retrieved successfully'
        ]);


    }

    public function getProductByUserId(Request $request){
        $user_id=$request->user_id;
        $products=Product::where('user_id',$user_id)->get();
        return response()->json([
            'data'=>$products,
           'status'=>'Success',
           'message'=>'Products retrieved successfully'
        ]);
    }

    //store
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string',
            'price'=>'required',
            'description'=>'required',
            'user_id'=>'required',
        ]);
        $user=$request->user();
        $request->merge(['user_id'=>$user->id]);
        $data =$request->all();
        $data['image']=$request->file('image')->store('product-images','public');
        $product=Product::create($data);


        if ($request->hasFile('image')){
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $image_name= time() . '.' . $image->getClientOriginalExtension();
            $image->move('uploads/products',$image_name);
            $product->image=$image_name;
            $product->save();


        }
        return response()->json([
            'data'=>$product,
           'status'=>'Success',
           'message'=>'Product created successfully'
        ]);
    }
//update

public function update(Request $request,$id){
    $request->validate([
        'name'=>'required|string',
        'price'=>'required',
        'description'=>'required',
        'user_id'=>'required',
    ]);

    $user=$request->user();
    $request->merge(['user_id'=>$user->id]);
    $data =$request->all();
    $product=Product::find($id);

    if(!$product){
        return response()->json([
            'data'=>null,
           'status'=>'Error',
           'message'=>'Product not found'
        ]);
    }
    if ($request->hasFile('image')){
        $image = $request->file('image');
        $name = time().'.'.$image->getClientOriginalExtension();
        $image_name= time() . '.' . $image->getClientOriginalExtension();
        $image->move('uploads/products',$image_name);
        $product->image=$image_name;
        $product->save();


    }

    $data = $request->all();
    $product = $product::find($id);
    if(!$product){
        return response()->json([
            'data'=>null,
           'status'=>'Error',
           'message'=>'Product not found'
        ]);
    }
    $data = $request->all();
    $product->update($data);
    return response()->json([
        'data'=>$product,
       'status'=>'Success',
       'message'=>'Product updated successfully'
    ]);




}

public function destroy($id){
    $product=Product::find($id);
    if(!$product){
        return response()->json([
            'data'=>null,
           'status'=>'Error',
           'message'=>'Product not found'
        ]);
    }
    $product->delete();
    return response()->json([
        'data'=>null,
       'status'=>'Success',
       'message'=>'Product deleted successfully'
    ]);
}
}
