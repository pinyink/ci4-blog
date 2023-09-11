<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Mpdf;

class MpdfController extends BaseController
{
    public function index()
    {
        $content = view('mpdf', []);

        $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            // 'format' => ['381', '677'],
            'format' => ['219', '330'],
            'orientation' => 'P',
            'fontDir' => array_merge($fontDirs, [
                PUBLICPATH.'/assets/font',
            ]),
            'fontdata' => $fontData + [
                'times' => [
                    'R' => 'times.ttf',
                    'B' => 'timesb.ttf',
                    'I' => 'timesi.ttf',
                    'BI' => 'timesbi.ttf'
                ],
                // 'montserratsemibold' => [
                //     'B' => 'Montserrat-SemiBold.ttf',
                //     'I' => 'Montserrat-SemiBoldItalic.ttf'
                // ],
                // 'montserratmediumitalic' => [
                //     'R' => 'Montserrat-Medium.ttf',
                //     'I' => 'Montserrat-MediumItalic.ttf',
                //     'B' => 'Montserrat-Black.ttf'
                // ],
                // 'montserratlight' => [
                //     'R' => 'Montserrat-Light.ttf'
                // ],
                // 'montserratextrabold' => [
                //     'R' => 'Montserrat-ExtraBold.ttf'
                // ],
            ],
            'default_font' => 'verdana',
            'allow_charset_conversion' => true,
            'list_auto_mode ' => 'browser',
            'list_marker_offset' => '15pt',
            'list_symbol_size' => '4pt'
        ]);
        $mpdf->WriteHTML($content);
        $mpdf->Output('print', 'I');
        exit();
    }
}
