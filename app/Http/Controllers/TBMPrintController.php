<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TBMController;

class TBMPrintController extends Controller
{
    public function generateReport(Request $request)
    {
        try {
            $request->validate(['tbm_id' => 'required']);
            $tbmController = new TBMController();
            $response = $tbmController->show($request->tbm_id);
            $data = json_decode($response->getContent()); // De
            // dd($data);

            $filename = storage_path('app/public/template.jpg');
            list($width, $height) = getimagesize($filename);
            $image = imagecreatefromjpeg($filename);

            // Black color for text
            $fontColor = imagecolorallocate($image, 0, 0, 0);

            // Using imagestring() instead of imagettftext()
            $fontSize = 4;  // GD library font size scale from 1 to 5
            $x = 220;
            $y = 160;
            $lineHeight = 15;  // Line height for the built-in font

            imagestring($image, $fontSize, $x, $y, 'Title: ' . $data->tbm->title, $fontColor);
            imagestring($image, $fontSize, $x, $y + $lineHeight, 'Potential Dangerous Point: ' . $data->tbm->pot_danger_point, $fontColor);
            imagestring($image, $fontSize, $x, $y + 2 * $lineHeight, 'Most Dangerous Point: ' . $data->tbm->most_danger_point, $fontColor);
            imagestring($image, $fontSize, $x, $y + 3 * $lineHeight, 'Countermeasure: ' . $data->tbm->countermeasure, $fontColor);
            imagestring($image, $fontSize, $x, $y + 4 * $lineHeight, 'Keyword: ' . $data->tbm->key_word, $fontColor);

            $outputPath = storage_path('app/public/report_filled.jpg');
            imagejpeg($image, $outputPath);
            imagedestroy($image);

            return response()->json(['message' => 'Report generated successfully', 'path' => $outputPath]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
