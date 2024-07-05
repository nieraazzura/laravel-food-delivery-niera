<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use APp\Models\product;

class OrderController extends Controller
{
    //
    public function createOrder(Request $request){
        $request->validate([
            'order_items' =>'required|array',
            'order_items.*.product_id' =>'required|integer|exists:products,id',
            'order_items.*.quantity' =>'required|integer|min:1',
            'restaurant_id'=>'required|integer|exists:users,id',
            'shpping_cost' =>'required|integer',


        ]);
        $user = $request->user();
        $data = $request->all();
        $data['user_id']=$user->id;
        $shippingAddress = $user->address;
        $data['shipping_address'] = $shippingAddress;
        $shippingLatLong = $user->lat_long;
        $data['status'] ='pending';

        $data['shipping_lat_long'] = $shippingLatLong;

        $order = Order::create($data);

        foreach ($request->order_items as $item){
            $product = Product::find($item['product_id']);
            $orderItem = new OrderItem([
                'quantity' =>$item['quantity'],
                'price' =>$product->price,

            ]);
            $order->orderItems()->save($orderItem);

        }
        return response()->json([
            'order' => $order,
           'message' => 'order created successfully',
           'data'=>$order
        ]);



    }

    public function updatePurchaseStatus(Request $request,$id){
        $request->validate([
            'status' =>'required|string|in:pending'
        ]);
        $order = Order::find($id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'order' => $order,
           'message' => 'order status updated successfully',
        ]);
    }

    public function orderHistory(Request $request){
        $user = $request->user();
        $orders = Order::where('user_id',$user->id)->get();
        return response()->json([
            'orders' => $orders,
            'data' => $orders,
           'message' => 'order history',
        ]);
    }

    //cancel order
    public function cancelOrder(Request $request,$id){
    $order = Order::find($id);
    $order->status = 'cancelled';
    $order->save();
    return response()->json([
        'order' => $order,

       'message' => 'order cancelled successfully',
    ]);
}

public function getOrderByStatus(Request $request)
{
    $user = $request->user();
    $status = $request->status;
    $orders = Order::where('user_id',$user->id)->where('status',$status)->get();
    return response()->json([
        'orders' => $orders,
        'data' => $orders,
       'message' => 'order history',

    ]);
}

// update order status for restaurant


public function updateOrderStatus(Request $request,$id){

    $request->validate([
        'status'=> 'required|string|in:pending,proses,complete,cancel',

    ]);

    $order=Order::find($id);
    $order->status = $request->status;
    $order->save();

    return response()->json([
        'order' => $order,
       'message' => 'order status updated successfully',
    ]);
}

public function getOrderByStatusDriver(Request $request){
    $request->validate([
        'status'=> 'required|string|in:pending,proses,complete,cancel',

    ]);
    $user = $request->user();
    $status = $request->status;
    $orders = Order::where('driver_id',$user->id)
              ->where('status',$request->status)
              ->get();
    return response()->json([
        'orders' => $orders,
        'data' => $orders,
       'message' => 'order history',

    ]);
}

public function getOrderStatusReadyForDelivery(Request $request){
$user =$request->user();
$orders= Order::with('restaurant_id')
->where('status','ready for delivery')
->get();


return response()->json([
    'orders' => $orders,
    'data' => $orders,
   'message' => 'order history',
]);
}

public function updateOrderStatusDriver(Request $request,$id){
    $request->validate([
       'status'=> 'required|string|in:pending,proses,complete,cancel',

    ]);

    $order=Order::find($id);
    $order->status = $request->status;
    $order->save();

    return response()->json([
        'status' =>'success',
        'order' => $order,
       'message' => 'order status updated successfully',
    ]);
}
}
