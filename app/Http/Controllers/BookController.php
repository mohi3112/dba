<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\IssuedBook;
use App\Services\LawyerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    protected $lawyerService;

    public function __construct(LawyerService $lawyerService)
    {
        $this->lawyerService = $lawyerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch books with issued books
        $books = Book::with(['issuedBooks' => function ($query) {
            $query->orderBy('issue_date', 'desc');
        }])->paginate(10);

        // Add a flag to each book
        foreach ($books as $book) {
            $lastIssuedBook = $book->issuedBooks->first();
            $book->isLastIssuedBookReturned = $lastIssuedBook ? $lastIssuedBook->return_date !== null : true;
        }

        $activeLawyers = $this->lawyerService->getActiveLawyers();

        return view('books.index', compact('books', 'activeLawyers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('books.create');
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
            'book_name' => 'required',
            'book_author_name' => 'required'
        ]);

        Book::create($request->all());

        return redirect()->route('books')->with('success', 'Book added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);

        return view('books.edit', compact('book'));
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
            'book_name' => 'required',
            'book_author_name' => 'required',
        ]);

        $book = Book::findOrFail($id);
        $book->book_name = $request->book_name;
        $book->book_author_name = $request->book_author_name;
        $book->book_licence = $request->book_licence;
        $book->book_licence_valid_upto = $request->book_licence_valid_upto;
        $book->available = $request->available;
        $book->save();

        return redirect()->route('books')->with('success', 'Book updated successfully.');
    }

    public function destroy($id)
    {
        // Find the user by ID
        $book = Book::findOrFail($id);

        $book->deleted_by = Auth::id();
        $book->save();

        // Soft delete the book
        $book->delete();

        return redirect()->route('books')->with('success', 'Book deleted successfully!');
    }

    public function issueBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'issue_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:issue_date',
        ]);

        $bookId = $request->input('book_id');

        // Check if the book is already issued and not returned
        $isBookIssued = IssuedBook::where('book_id', $bookId)
            ->whereNull('return_date')
            ->exists();

        if ($isBookIssued) {
            return redirect()->route('books')->with('error', 'Book is already issued and not returned.');
        }

        // Issue the book to the user
        IssuedBook::create([
            'book_id' => $request->input('book_id'),
            'user_id' => $request->input('user_id'),
            'issue_date' => $request->input('issue_date'),
            'return_date' => $request->input('return_date'),
        ]);

        return redirect()->route('books')->with('success', 'Book issued successfully.');
    }

    public function returnBook(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date',
        ]);

        $issuedBook = IssuedBook::findOrFail($id);
        $issuedBook->return_date = $request->input('return_date');
        $issuedBook->save();

        return redirect()->route('books.issued-books')->with('success', 'Record updated successfully.');
    }

    public function getAllIssuedBooks()
    {
        // Fetch all issued books with the related book and user details
        $issuedBooks = IssuedBook::with(['book', 'user'])
            // ->whereNull('return_date')
            ->orderBy('return_date', 'asc')->paginate(10);
        return view('books.issuedBooks', compact('issuedBooks'));
    }
}
