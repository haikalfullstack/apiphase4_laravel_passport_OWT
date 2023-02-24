<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //create book - POST
    public function createBook(Request $request){

        // validation
        $request->validate([
            "title" => "required",
            "book_cost" => "required",
        ]);

        // create book data
        $book = new Book();
        $book->author_id = auth()->user()->id;
        $book->title = $request->title;
        $book->description = $request->description;
        $book->book_cost =$request->book_cost;

        // save
        $book->save();

        // send response
        return response()->json([
            'status' => true,
            'message' => 'Book created successfully'
        ]);

    }

    // list book - GET
    public function listBook(){
        $books = Book::get();
        return response()->json([
            "status" => true,
            "message" => "All Books",
            "data" => $books
        ]);
    }

    
    // author book - GET
    public function authorBook(){
        $author_id = auth()->user()->id;
        $books = Author::find($author_id)->books;

        return response()->json([
            "status" => true,
            "message" => "Author Books",
            "data" => $books
        ]);
    }

    // single book - GET
    public function singleBook($book_id){
        $author_id = auth()->user()->id;

        if(Book::where([
            "author_id" => $author_id,
            "id" => $book_id
        ])->exists()){
        

            $book = Book::find($book_id);

            return response()->json([
                "status" => true,
                "message" => "Book data found",
                "data" => $book
            ]);

        }else{
            return response()->json([
                "status" => false,
                "message" => "Author book doesn't exists"
            ]);
        }
    }

    // update book - POST
    public function updateBook(Request $request, $book_id){
          // validation
          $request->validate([
            "title" => "required",
            "book_cost" => "required",
        ]);

        $author_id = auth()->user()->id;

        if(Book::where([
            "author_id" => $author_id,
            "id" => $book_id
        ])->exists()){

            
              dd($request->all());

            $book = Book::find($book_id);
          

            $book->title = isset($request->title) ? $request->title : $book->title;
            $book->description = isset($request->description) ? $request->description : $book->description;
            $book->book_cost = isset($request->book_cost) ? $request->book_cost : $book->book_cost;

            $book->save();

            return response()->json([
                "status" => true,
                "message" => "Book data has been updated"
            ]);

        }else{
            return response()->json([
                "status" => false,
                "message" => "Author book doesn't exist"
            ]);
        }
    }

    // delete book- GET
    public function deleteBook($book_id){

        $author_id = auth()->user()->id;

        if(Book::where(["author_id" => $author_id, "id" => $book_id])->exists()){

            $book = Book::find($book_id);
            $book->delete();

            return response()->json([
                "status" => true,
                "message" => "Book has been deleted"

            ]);
        }else{
            return response()->json([
                "status" => false,
                "message" => "Author book doesn't exists"
            ]);
        }

    }


}
