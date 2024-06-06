<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BooksCategory;
use Illuminate\Support\Facades\Auth;

class BooksCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booksCategories = BooksCategory::withCount('books')->paginate(10);

        return view('booksCategory.index', compact('booksCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('booksCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        BooksCategory::create($request->all());

        return redirect()->route('bookCatgories')->with('success', 'Books category added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $booksCategory = BooksCategory::findOrFail($id);

        return view('booksCategory.edit', compact('booksCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        $book = BooksCategory::findOrFail($id);
        $book->category_name = $request->category_name;
        $book->published_volumns = $request->published_volumns;
        $book->published_total_volumns = $request->published_total_volumns;
        $book->save();

        return redirect()->route('bookCatgories')->with('success', 'Books category updated successfully.');
    }

    public function destroy($id)
    {
        // Find the user by ID
        $book = BooksCategory::findOrFail($id);

        $book->deleted_by = Auth::id();
        $book->save();

        // Soft delete the book
        $book->delete();

        return redirect()->route('bookCatgories')->with('success', 'Book category deleted successfully!');
    }
}
