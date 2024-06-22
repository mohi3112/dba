<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BooksCategory;
use App\Models\ModificationRequest;
use Illuminate\Support\Facades\Auth;

class BooksCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $booksCategoriesQuery = BooksCategory::withCount('availableBooks');

        if ($request->filled('categoryName')) {
            $booksCategoriesQuery->where('category_name', 'like', '%' . $request->categoryName . '%');
        }

        if ($request->filled('publishedVolumes')) {
            $booksCategoriesQuery->where('published_volumes', 'like', '%' . $request->publishedVolumes . '%');
        }

        if ($request->filled('publishedTotalVolumes')) {
            $booksCategoriesQuery->where('published_total_volumes', $request->publishedTotalVolumes);
        }

        $booksCategories = $booksCategoriesQuery->orderBy('id', 'desc')->paginate(10);

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

        return redirect()->route('bookCategories')->with('success', 'Books category added successfully.');
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
        if ($book) {
            if (auth()->user()->hasRole('president')) {

                $book->category_name = $request->category_name;
                $book->published_volumes = $request->published_volumes;
                $book->published_total_volumes = $request->published_total_volumes;

                $book->save();
                return redirect()->route('bookCategories')->with('success', 'Books category updated successfully.');
            } else {

                $changes = $request->except(['_token', '_method']);
                $this->submitChangeRequest([
                    "table_name" => 'books_categories',
                    "record_id" => $book->id,
                    "changes" => $changes,
                    "action" => ModificationRequest::REQUEST_TYPE_UPDATE,
                    "requested_by" => Auth::id(),
                ]);
            }
            return redirect()->route('bookCategories')->with('success', 'Books category update request submitted successfully.');
        }

        return redirect()->route('bookCategories')->with('error', 'Something went wrong.');
    }

    public function destroy($id)
    {
        // Find the user by ID
        $book = BooksCategory::findOrFail($id);

        if ($book) {
            if (auth()->user()->hasRole('president')) {
                $book->deleted_by = Auth::id();
                $book->save();

                // Soft delete the book
                $book->delete();

                return redirect()->route('bookCategories')->with('success', 'Book category deleted successfully!');
            } else {
                $this->submitChangeRequest([
                    "table_name" => 'books_categories',
                    "record_id" => $book->id,
                    "action" => ModificationRequest::REQUEST_TYPE_DELETE,
                    "requested_by" => Auth::id(),
                ]);
                return redirect()->route('locations')->with('success', 'Location delete request submitted successfully!');
            }
            return redirect()->route('bookCategories')->with('error', 'Something went wrong.');
        }
    }
}
