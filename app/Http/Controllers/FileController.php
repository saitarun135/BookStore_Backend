<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\StaticAnalysis\Cache;
use App\Http\Resources\Books as BookResource;

class FileController extends Controller
{
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

    public function upload(Request $request ){
        $book = new Books();
        $book->price=$request->input('price');
        $book->name=$request->input('name');
        $book->quantity=$request->input('quantity');
        $book->author=$request->input('author');
        $book->description=$request->input('description');
        $date = Carbon::now();
        $file=$request->file;
        $filePath = '/images/books/' . $date->year;
        $filename = $date->timestamp . '_' . $file->getClientOriginalName();
        $book->file = $file->storeAs($filePath, $filename, 'public');;
        $book->user_id = auth()->id();
        $book->save();
        return new BookResource($book);
    }
  
    public function destroy($image)
    {
        Storage::disk('s3')->delete('apiDocs/' . $image);
        return ['Image was deleted successfully'];
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
                               'quantity'=>$request->input('quantity')
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
}
