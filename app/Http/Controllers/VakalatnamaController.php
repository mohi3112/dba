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

        $lawyerAllow = true;
        if (auth()->user()->hasRole('president') || auth()->user()->hasRole('finance_secretary')) {
            $lawyerAllow = false;
        }

        if ($lawyerAllow) {
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
            'number_of_issue_vakalatnamas' => 'nullable|numeric',
        ]);

        $uniqueId = Vakalatnama::generateUniqueId();
        $request->merge(['unique_id' => $uniqueId]);

        if ($request->input('bulk_issue') && $request->input('bulk_issue') == '1') {
            $request->merge([
                'bulk_issue' => 1,
                'number_of_issue_vakalatnamas' => $request->input('number_of_issue_vakalatnamas'),
                'last_unique_id' => Vakalatnama::generateUniqueId($uniqueId, $request->input('number_of_issue_vakalatnamas'))
            ]);
        } else {
            $request->merge(['number_of_issue_vakalatnamas' => NULL]);
        }

        Vakalatnama::create($request->all());

        return redirect()->route('vakalatnamas')->with('success', 'Vakalatnama issued successfully.');
    }

    public function printVakalatnama($uniqueId)
    {
        try {
            $vakalatnamaQuery = Vakalatnama::where('unique_id', $uniqueId);

            $lawyerAllow = true;
            if (auth()->user()->hasRole('president') || auth()->user()->hasRole('finance_secretary')) {
                $lawyerAllow = false;
            }

            if ($lawyerAllow) {
                $vakalatnamaQuery->where('user_id', auth()->user()->id);
            }

            $vakalatnama = $vakalatnamaQuery->first();

            $totalVakalatnamas = ($vakalatnama->bulk_issue == 0) ? 1 : $vakalatnama->number_of_issue_vakalatnamas;

            // original file path
            $filePath = storage_path('attorney.pdf');

            // temp/modified file path
            $outputPath = storage_path('attorney-copy.pdf');
            $payloads = [];
            $tempUniqueId = '';
            for ($i = 0; $i < $totalVakalatnamas; $i++) {
                if ($tempUniqueId == '') {
                    $tempUniqueId = $uniqueId;
                } else {
                    $tempUniqueId = Vakalatnama::generateUniqueId($tempUniqueId);
                }
                // payload for pdf generation
                $payload = [];
                $payload['uniqueId'] = $tempUniqueId;
                $payload['date'] = Carbon::now()->format('Y-m-d H:i:s');

                $uniqueString = '';
                if (auth()->user()->hasRole('president')) {
                    $uniqueString = 'Precured by president';
                } elseif (auth()->user()->hasRole('finance_secretary')) {
                    $uniqueString = 'Digitaly signed by finance secretary';
                }
                $payload['uniqueString'] = $uniqueString;
                $payloads[] = $payload;
            }

            // add the print date and unique ID
            $this->pdfService->addPrintDateAndUniqueId($filePath, $outputPath, $payloads);

            return response()->stream(function () use ($outputPath) {
                readfile($outputPath);
                unlink($outputPath); // Delete the temporary file after sending
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="modified.pdf"',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
