<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers;

class CustomersController extends Controller
{
    public function customerRegistration(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|string|between:3,15',
            'phoneNumber'=>'required|digits:10',
            'pincode'=>'required|digits_between:5,8',
            'locality'=>'required|string|between:3,15', 
            'city'=>'required|string|between:3,15',
            'address'=>'required',
            'landmark'=>'required|between:3,15',
            'type'=>'required|in:Home,Work,Other'
            ]);
        $customer = new Customers([
            'name'=>$request->name,
            'phoneNumber'=>$request->phoneNumber,
            'pincode'=>$request->pincode,
            'locality'=>$request->locality, 
            'city'=>$request->city,
            'address'=>$request->address,
            'landmark'=>$request->landmark,
            'type'=>$request->type
                     
        ]);
        $customer->save();
        return response()->json(['message'=>'Successfully customer registered'],201);
    }

    public function DeleteCustomer($id){
        $customer=Customers::findOrFail($id);
        $customer->delete();
        return['Deleted'];
    }
}
