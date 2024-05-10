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
            // dd($response);
            $data = json_decode($response->getContent()); // De
            // dd($data);

            $filename = storage_path('app/public/template.jpg');
            list($width, $height) = getimagesize($filename);
            $image = imagecreatefromjpeg($filename);

            // Black color for text
            $fontColor = imagecolorallocate($image, 0, 0, 0);

            // Using imagestring() instead of imagettftext()
            $fontSize = 4;  // GD library font size scale from 1 to 5
            $x = 30;
            $y = 160;
            $lineHeight = 16;  // Line height for the built-in font
            imagestring($image, $fontSize, 180, $y - 5 * $lineHeight -2 ,  $data->tbm->dept_code, $fontColor); //'Department: ' .
            imagestring($image, $fontSize, 390, $y - 5 * $lineHeight -2 ,  $data->tbm->date, $fontColor); //'Date ' .

            imagestring($image, $fontSize, 180, $y - 4 * $lineHeight  +3,  $data->tbm->section, $fontColor); //'Section: ' .
            imagestring($image, $fontSize, 390, $y - 4 * $lineHeight  +3 ,  $data->tbm->time, $fontColor); //'time ' .
            imagestring($image, $fontSize, 80, $y - 2 * $lineHeight + 6,  $data->tbm->title, $fontColor); //'Title: ' .
            imagestring($image, $fontSize, $x, $y + $lineHeight +1 ,  $data->tbm->pot_danger_point, $fontColor); //'Potential Dangerous Point: ' .
            imagestring($image, $fontSize, $x, $y + 11 * $lineHeight + 3,  $data->tbm->most_danger_point, $fontColor); //'Most Dangerous Point: ' .
            imagestring($image, $fontSize, $x, $y + 15 * $lineHeight + 5,  $data->tbm->countermeasure, $fontColor); //'Countermeasure: ' .
            imagestring($image, $fontSize, $x, $y + 20 * $lineHeight + 5,  $data->tbm->key_word, $fontColor); //'Keyword: ' .
            $Newline = 0;
            foreach($data->tbm_instructor as $instructor){
                imagestring($image, 1, $x + 155, $y + (23+$Newline) * $lineHeight + 8,  $instructor->user_name, $fontColor); //Instructor '
                imagestring($image, 1, $x + 260, $y + (23+$Newline) * $lineHeight + 8,  substr($instructor->signed_date,0,10), $fontColor); //Instructor signed-date '
                $Newline++;
            }
            $nCol = 0; //next col val +210
            $Newline = 0; 
            foreach($data->tbm_attendance as $attendant){
                imagestring($image, 1, ($x + 155) + 210*$nCol, $y + (25+$Newline) * $lineHeight + 8,  $attendant->user_name, $fontColor); //attendance ' ..
                imagestring($image, 1, ($x + 260) + 210*$nCol, $y + (25+$Newline) * $lineHeight + 8,  substr($attendant->signed_date,0,10), $fontColor); //attendance signed-date '
                $Newline++;
                if($Newline == 8){//change coloumn
                    $nCol++;
                    $Newline = 0;
                }
            }
    
            
            imagestring($image, 1, $x + 5, $y + 35 * $lineHeight + 9,  $data->prepared_by_name, $fontColor); //Prepared_by ' ..
            imagestring($image, 1, $x + 135, $y + 35 * $lineHeight + 9,  $data->checked_by_name, $fontColor); //Checked_by ' ..
            imagestring($image, 1, $x + 250, $y + 35 * $lineHeight + 9,  $data->reviewed_by_name, $fontColor); //Reviewed_by ' ..
            imagestring($image, 1, $x + 350, $y + 35 * $lineHeight + 9,  $data->approved1_by_name, $fontColor); //APPROVED1_by ' ..
            imagestring($image, 1, $x + 465, $y + 35 * $lineHeight + 9,  $data->approved2_by_name, $fontColor); //APPROVED2_by ' ..

            $outputPath = storage_path('app/public/report_filled.jpg');
            imagejpeg($image, $outputPath);
            imagedestroy($image);

            return response()->json(['message' => 'Report generated successfully', 'path' => $outputPath]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
