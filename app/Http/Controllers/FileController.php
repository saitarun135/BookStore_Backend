<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
use App\Models\User;
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



   public function upload(Request $request){
        $book = new Books();
        $book->price=$request->input('price');
        $book->name=$request->input('name');
        $book->quantity=$request->input('quantity');
        $book->author=$request->input('author');
        $book->description=$request->input('description');
        // if ($request->hasFile('file')) {
        //     $file = $request->file('file');
        //     $name = time() . $file->getClientOriginalName();
        //     $filePath = 'apiDocs/' . $name;
        //     Storage::disk('s3')->put($filePath, file_get_contents($file));
        //     }
             
        //     return ['Image uploaded successfully'];
       $book->file=$request->file('file')->store('apiDocs');
        // $path = $request->file('file')->store('apiDocs', 's3');
        //     $book =Books::create([
        //     'filename' => basename($path),
        //     'url' => Storage::disk('s3')->url($path)
        // ]);
        // $book->file=Storage::disk('s3')->url($path);
      
        //           $imageName=time().$file->getClientOriginalName();
        //           $filePath = '/public/images/' . $imageName;
        //           // Storage::disk('s3')->put($filePath, file_get_contents($file));

        
        $book->user_id = auth()->id();
        $book->save();
        return ["result"=>$book];
        // return [$book];        
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
            return response()->json([
                'error' => 'UnAuthorized/invalid id'], 401);
                
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
          
            $book->price=$request->input('price');
            $book->name=$request->input('name');
            $book->quantity=$request->input('quantity');
            $book->author=$request->input('author');
            $book->description=$request->input('description');
            // $book->file=dd($request->file('file'))->store('apiDocs');
            if($request->hasFile('file') && $request->file('file')->isValid()) {
                $book->file=$request->file('file')->store('apiDocs');
            }
            $book->save();
             return response()->json(['message'=>'success',200]);
                // DB::table('books')->where('id',$request->id)->update([
                //     'name' => $request->input('name'),
                //     'file' => $request->input('file'),
                //     'price' => $request->input('price'),
                //     'author' => $request->input('author'),
                //     'description'=>$request->input('description'),
                //     'quantity'=>$request->input('quantity')
                // ]);
        }
        else
        {
            return response()->json([
                'error' => ' Book is not available ith id'], 404);
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
