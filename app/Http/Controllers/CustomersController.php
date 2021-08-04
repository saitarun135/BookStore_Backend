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
use PhpParser\Node\Expr\AssignOp\Mod;

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
                'orderNumber' => $order->orderNumber=rand(11111111,99999999),
                'customer_id'=>$order->id,
                'order_date'=>$order->order_date=Carbon::now(),      
            ]
        );
        $bookgetter1 = DB::table("Books")->select('name')->where('cart',['1'])->get();
        $bookgetter2 = DB::table("Books")->select('price')->where('cart',['1'])->get();
        // $bookgetter3 = DB::table("Books")->select('author')->where('cart',['1'])->get();
        if($order && $ord){
            $order->notify(new orderSuccessfullNotification($ord->orderNumber,$bookgetter1,$bookgetter2));
            ModelsBooks::Where('cart','1')->update((['cart'=>'0']));
        }
        return response()->json(['message'=>'order created successfully','orderID'=>$ord->orderNumber]);
    }

    public function getOrderID(Request $request,$customer_id){
        $order_number=Orders::where('id',$customer_id)->value('orderNumber');
        return response()->json(['orderNumber'=>$order_number]);
    }

  }
