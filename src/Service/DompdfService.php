<?php 

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class DompdfService
{
    private $pdfOptions;

    public function __construct(array $pdfOptions = [])
    {
        $this->pdfOptions = $pdfOptions;
    }

    public function generatePdf(string $html): string
    {
        $options = new Options();

                // Appliquer les options spécifiées
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isPhpEnabled', true);
                $options->set('isJavascriptEnabled', true);
        
// Appliquer les options passées lors de la construction
foreach ($this->pdfOptions as $key => $value) {
    $options->set($key, $value);
}

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

return $dompdf->output();
    }
}
// class dompdfService
// {
//     private $domPdf;

//     public function __construct() {
//         $this->domPdf = new DomPdf();

//         $options = new Options();

//         $options->set('isHtml5ParserEnabled', true);
//         $dompdf->setPaper('A4', 'portrait');



// }

//     public function showPdfFile($html)
//     {
//         $this->domPdf->loadHtml($html);
//         $this->domPdf->render();
//         $this->domPdf->stream(filename: "facture.pdf", [
//             'Attachement' => false
//         ]);
//     }
// }