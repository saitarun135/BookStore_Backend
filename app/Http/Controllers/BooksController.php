<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
use App\Models\User;
use App\Http\Requests;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Books as BooksResource;
use App\Http\Middleware\Authenticate;
use App\Models\Doccuments;
use Illuminate\Support\Facades\Storage;

class BooksController extends Controller
{
  public function addBooks(Request $request){
  
  //   // $book->image=$request->input('image'); 
  //   // Storage::disk('s3')->put('images/originals', $request->file);
  //   $request->validate([
  //     'doccument' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
  // ]);
  //   if($request->hasfile('doccument'))
  //   {  
  //           $file = $request->file('image');
  //           $imageName=time().$file->getClientOriginalName();
  //           $filePath = '/public/images/' . $imageName;
  //           // Storage::disk('s3')->put($filePath, file_get_contents($file));
  //           $book->image=$imageName;
  //   }
  //   $book->price=$request->input('price');
  //   $book->title=$request->input('title');
  //   $book->quantity=$request->input('quantity');
  //   $book->author=$request->input('author');
  //   $book->description=$request->input('description');
  //   $book->user_id = auth()->id();          
  //   $book->save();
  //   return response()->json(['message' => 'book added'], 201);
  // }
  $validator = Validator::make($request->all(),[ 
    'file' => 'required|mimes:doc,docx,pdf,txt,csv|max:2048',
]);   

if($validator->fails()) {          
   
  return response()->json(['error'=>$validator->errors()], 401);                        
}  


if ($file = $request->file('file')) {
  $path = $file->store('public/files');
  $name = $file->getClientOriginalName();

  //store your file into directory and db
  $save = new File();
  $save->name = $file;
  $save->store_path= $path;
  $save->save();
     
  return response()->json([
      "success" => true,
      "message" => "File successfully uploaded",
      "file" => $file
  ]);

}
