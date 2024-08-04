<?php

namespace App\Services;

class PdfService
{
    public function addPrintDateAndUniqueId($filePath, $outputPath, array $payload)
    {
        // Create new CustomFpdi instance
        $pdf = new CustomFpdi();

        // Set the source file
        $pageCount = $pdf->setSourceFile($filePath);

        // Iterate over all pages
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // Import a page
            $templateId = $pdf->importPage($pageNo);

            // Get the size of the imported page
            $size = $pdf->getTemplateSize($templateId);

            // Add a page to the new document
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

            // Use the imported page
            $pdf->useTemplate($templateId);

            // Set font for the print date and unique ID
            $pdf->SetFont('Arial', '', 12);

            // Add the print date at the bottom
            $printDate =  \Carbon\Carbon::parse($payload['date'])->format('d-M-Y H:i:s');
            $pdf->SetXY(10, $size['height'] - 31);
            $pdf->Cell(0, 10, 'Print Date: ' . $printDate, 0, 0, 'C');

            // Add the unique ID in vertical position on the left
            $x = 12;
            // $y = ($size['height'] / 1.5);
            $y = ($size['height'] / 1.695) + ($pdf->GetStringWidth($payload['uniqueId']) / 2);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Rotate(270, $x, $y);
            $pdf->Text($x, $y, $payload['uniqueId']);
            $pdf->Rotate(0);
        }

        // Output the new PDF
        $pdf->Output('F', $outputPath);
    }
}
