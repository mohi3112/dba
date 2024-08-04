<?php

namespace App\Http\Controllers;

use App\Models\Vakalatnama;
use Illuminate\Http\Request;
use App\Services\PdfService;
use App\Services\LawyerService;
use Carbon\Carbon;

class VakalatnamaController extends Controller
{
    protected $pdfService;
    protected $lawyerService;

    public function __construct(PdfService $pdfService, LawyerService $lawyerService)
    {
        $this->pdfService = $pdfService;
        $this->lawyerService = $lawyerService;
    }

    public function index(Request $request)
    {
        $vakalatnamaQuery = Vakalatnama::query();

        if (!auth()->user()->hasRole('president')) {
            $vakalatnamaQuery->where('user_id', auth()->user()->id);
        }

        if ($request->filled('userId')) {
            $vakalatnamaQuery->where('user_id', $request->userId);
        }

        if ($request->filled('issueDate')) {
            $vakalatnamaQuery->where('created_at', $request->issueDate);
        }

        $vakalatnamas = $vakalatnamaQuery->orderBy('created_at', 'desc')->paginate(10);

        $activeLawyers = $this->lawyerService->getActiveLawyers();

        return view('vakalatnama.index', compact('vakalatnamas', 'activeLawyers'));
    }

    public function vakalatnamaForm()
    {
        $activeLawyers = $this->lawyerService->getActiveLawyers();
        $uniqueId = Vakalatnama::generateUniqueId();

        return view('vakalatnama.form', compact('activeLawyers', 'uniqueId'));
    }

    public function issueVakalatnama(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
        ]);

        $request->merge(['unique_id' => Vakalatnama::generateUniqueId()]);

        Vakalatnama::create($request->all());

        return redirect()->route('vakalatnamas')->with('success', 'Vakalatnama issued successfully.');
    }

    public function printVakalatnama($uniqueId)
    {
        $vakalatnamaQuery = Vakalatnama::where('unique_id', $uniqueId)->first();

        if (!auth()->user()->hasRole('president')) {
            $vakalatnamaQuery->where('user_id', auth()->user()->id);
        }

        $vakalatnama = $vakalatnamaQuery->first();

        // original file path
        $filePath = storage_path('attorney.pdf');

        // temp/modified file path
        $outputPath = storage_path('attorney-copy.pdf');

        // payload for pdf generation
        $payload = [];
        $payload['uniqueId'] = $vakalatnama->unique_id;
        $payload['date'] = $vakalatnama->created_at;

        // add the print date and unique ID
        $this->pdfService->addPrintDateAndUniqueId($filePath, $outputPath, $payload);

        return response()->stream(function () use ($outputPath) {
            readfile($outputPath);
            unlink($outputPath); // Delete the temporary file after sending
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="modified.pdf"',
        ]);

        // return response()->download($outputPath);
    }
}
