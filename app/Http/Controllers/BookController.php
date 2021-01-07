<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// untuk menggunakan resource dengan data json
use App\Book;
use App\Http\Resources\Book as BookResource;
// resource dengan data collection
use App\Http\Resources\Books as BookCollectionResource;

class BookController extends Controller
{
    public function index()
    {
        // $books = \App\Book::all();
        // $books = \App\Book::where('status', 'PUBLISH')
        //         ->orderBy('title', 'asc')
        //         ->limit(3)
        //         ->get();
        // $published_books = $books->reject(function ($book)
        // {
        //     return $book->status=='DRAFT';
        // });
        // foreach ($books as $book) {
        //     echo $book->title.'<br>';
        // }
        
        // $books = new BookCollectionResource(Book::get());
        $books = new BookCollectionResource(Book::paginate(5));
        return $books;
    }

    public function view($id)
    {
        // $book = DB::select('select * from books where id = :id', ['id' => $id]);
        $book = new BookResource(Book::find($id));
        return $book;
    }
}
