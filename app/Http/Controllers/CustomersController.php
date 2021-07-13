<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers;
use App\Models\Orders;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Resources\Books;
use App\Models\Books as ModelsBooks;
use Illuminate\Support\Facades\Auth;
use App\Notifications\orderSuccessfullNotification;


class CustomersController extends Controller
{
    public function customerRegistration(Request $request)
    {
        $customer =new Customers();
        $customer->name=$request->input('name');
        $customer->phoneNumber=$request->input('phoneNumber');
        $customer->pincode=$request->input('pincode');
        $customer->locality=$request->input('locality');
        $customer->city=$request->input('city');
        $customer->address=$request->input('address');
        $customer->landmark=$request->input('landmark');
        $customer->type=$request->input('type');
        $customer->user_id = auth()->id();
        $customer->save();
        return ['successfully customer registered'];
    }

  public function orderSuccessfull(Request $request){
      $cust=new Customers();
      $cust->user_id = auth()->id();
      $cust_id=Customers::where('user_id',$cust->user_id)->value('user_id');
      
  
      

      $user_email=User::where('id',$cust_id)->value('email');
      
      $order = User::where('email', $user_email)->first();
      $ord = Orders::create(        
        [
            'orderNumber' => $order->orderNumber=Str::random(6),
            'customer_id'=>$order->id,
            'order_date'=>$order->order_date=Carbon::now(),
            
        ]
    );
    //  return response()->json(['status'=>$ord]);
    if($order && $ord){
    $order->notify(new orderSuccessfullNotification($ord->orderNumber));
    }
      return response()->json(['message'=>'order created successfully']);
  }

 

}
