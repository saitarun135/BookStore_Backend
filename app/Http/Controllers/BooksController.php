<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
use App\Models\User;
use App\Http\Requests;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Books as BooksResource;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Storage;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function DisplayBooks()
    {
        $books=Books::all();
        return User::find($books->user_id=auth()->id())->books; 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddBooks(Request $request)
    {
        $book=new Books();
        $book->image=$request->input('image'); 
        if($request->hasfile('image'))
            {  
            $file = $request->file('image');
            $imageName=time().$file->getClientOriginalName();
            $filePath = 'images/' . $imageName;
            Storage::disk('s3')->put($filePath, file_get_contents($file));
              $book->image=$imageName;
            }
          
            // if($request->hasFile('image')){
            //     $file = $request->file('image');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = time() . '.' . $extension;
            //     $file->move('uploads/books/', $filename);
            //     $book->image = $filename;
            // } else{
            //     return $request;
            //     $book->image = '';
            // }
        $book->price=$request->input('price');
        $book->title=$request->input('title');
        $book->quantity=$request->input('quantity');
        $book->author=$request->input('author');
        $book->description=$request->input('description');
        $book->user_id = auth()->id();          
        $book->save();
        return new BooksResource($book);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ShowBook($id)
    {
        $book=Books::findOrFail($id);
        if($book->user_id==auth()->id())
            return new BooksResource($book);
        else{
            return response()->json([
                'error' => 'UnAuthorized/invalid id'], 401);
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UpdateBook(Request $request, $id)
    {
        $book=Books::findOrFail($id);
        if($book->user_id==auth()->id()){
            //$book->name=$request->input('name');
            $book->image=$request->input('image'); 
            $book->price=$request->input('price');
            $book->title=$request->input('title');
            $book->quantity=$request->input('quantity');
            //$book->ratings=$request->input('ratings');
            $book->author=$request->input('author');
            $book->description=$request->input('description');
            $book->save();
            return new BooksResource($book);
        }
        else
        {
            return response()->json([
                'error' => ' Book is not available ith id'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function DeleteBook($id)
    {
        $book=Books::findOrFail($id);
        if($book->user_id==auth()->id()){
            if($book->delete()){
                return response()->json(['message'=>'Deleted'],201);
            }
        }
        else{
            return response()->json([
                'error' => ' Method Not Allowed/invalid Book id'], 405);
        }
    }
}
