<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\Books as BookResource;

class FileController extends Controller
{
    
    public function upload(Request $request ){
        $book = new Books();
        $book->price=$request->input('price');
        $book->name=$request->input('name');
        $book->quantity=$request->input('quantity');
        $book->author=$request->input('author');
        $book->description=$request->input('description');
        $book->file=$request->input('file');
        $book->user_id = auth()->id();        
        $book->save();
        return response()->json(['books'=>$book]);
    }

    public function display_Book($id)
    {
        $book=Books::findOrFail($id);
        if($book->user_id==auth()->id())
            return new BookResource($book);
        else{
            return response()->json(['error' => 'UnAuthorized/invalid id'], 401);    
            }
    }

    public function searchBooksByAuthor($author){
        $books=Books::all();
        if(User::find($books->user_id=auth()->id())->books){
             $searchBooks=  Books::where("author","like","%".$author."%")->get();
             return response()->json(['books' => $searchBooks], 200);
        }
        else{
            return response()->json(['error'=>'no books '],404);
        }
    }
    
    public function searchbooks($name)
    {
        $books=Books::all();
        if(User::find($books->user_id=auth()->id())->books){
            return  Books::where("name","like","%".$name."%")->get();
        }
        else{
            return response()->json(['error'=>'no books '],404);
        }
    }

    public function searchBooksbyPrice($price){
        $books=Books::all();
        if(User::find($books->user_id=auth()->id())->books){
            $searchBooks=Books::where("price",$price)->get();
            return response()->json(['books'=>$searchBooks],200);
        }
    }

    public function sortBooksHighToLow(){
        $books=Books::all();
        if(User::find($books->user_id=auth()->id())->books){
            return Books::orderBy('price','DESC')->get();
        }
        else{
            return response()->json(['error'=>'error'],401);
        }
    }

    public function sortBooksLowToHigh(){
        $books=Books::all();
        if(User::find($books->user_id=auth()->id())->books){
           return Books::orderBy('price','ASC')->get();
        }
    }

    public function AddToCart(Request $request,$id){
        $book=Books::findOrFail($id);
        if($book->user_id==auth()->id()){
            $book=Books::where('id',$id)
                ->update(array('cart'=>'1',
            ));
            return['updated successfully'];
        }
    }
    
    public function RemoveFromCart(Request $request,$id){
        $book=Books::findOrFail($id);
        if($book->user_id=auth()->id()){
            $book=Books::where('id',$id)->update(array('cart'=>'0'));
            return['removed from cart'];
        }
    }
    
    public function cartItem(){
        $books=Books::all();
        if(User::find($books->user_id=auth()->id())->books){
           return  Books::whereIn('cart', ['1'])->get();
        }
    }
    
    public function displayBooks()
    {
        $books=Books::all();
        return User::find($books->user_id=auth()->id())->books; 
    }
    
    public function updateBook(Request $request, $id){
        $book=Books::findOrFail($id);
        if($book->user_id==auth()->id()){
            $book=Books::where('id',$id)
                ->update(array('name'=>$request->input('name'),
                               'price' => $request->input('price'),
                               'author' => $request->input('author'),
                               'description'=>$request->input('description'),
                               'quantity'=>$request->input('quantity'),
                               'file'=>$request->input('file')
            ));
            return['updated successfully'];
        }
    }

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
 
    public function index()
    {
        $url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
        $images = [];
        $files = Storage::disk('s3')->files('file');
            foreach ($files as $file) {
                $images[] = [
                        'name' => str_replace('apiDocs/', '', $file),
                        'src' => $url . $file
                    ];
                    }
            return ['welcome'];
    }

    public function destroy($image)
    {
        Storage::disk('s3')->delete('apiDocs/' . $image);
        return ['Image was deleted successfully'];
    }

}
