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
            $data = json_decode($response->getContent());

            $filename = public_path('template.jpg');
            $image = imagecreatefromjpeg($filename);

            // Setup the text color
            $fontColor = imagecolorallocate($image, 0, 0, 0);
            $fontSize = 4;  // GD library font size scale from 1 to 5
            $x = 30;
            $y = 160;
            $lineHeight = 16;  // Line height for built-in fonts

            // Process and display each section of text
            

            imagestring($image, $fontSize, 180, $y - 5 * $lineHeight -2 ,  $data->tbm->dept_code, $fontColor); //'Department: ' .
            imagestring($image, $fontSize, 390, $y - 5 * $lineHeight -2 ,  $data->tbm->date, $fontColor); //'Date ' .

            imagestring($image, $fontSize, 180, $y - 4 * $lineHeight  +3,  $data->tbm->section, $fontColor); //'Section: ' .
            imagestring($image, $fontSize, 390, $y - 4 * $lineHeight  +3 ,  $data->tbm->time, $fontColor); //'time ' .
            imagestring($image, $fontSize, 80, $y - 2 * $lineHeight + 6,  $data->tbm->title, $fontColor); //'Title: ' .
            $this->imageStringWrapped($image, $fontSize, $x, $y + $lineHeight +1, $data->tbm->pot_danger_point , $fontColor, 550); //'Potential Dangerous Point: ' .
            // imagestring($image, $fontSize, $x, $y + $lineHeight +1 ,  $data->tbm->pot_danger_point, $fontColor); //'Potential Dangerous Point: ' .
            $this->imageStringWrapped($image, $fontSize, $x, $y + 11 * $lineHeight + 3,  $data->tbm->most_danger_point , $fontColor, 550); //'Most Dangerous Point: ' .
            // imagestring($image, $fontSize, $x, $y + 11 * $lineHeight + 3,  $data->tbm->most_danger_point, $fontColor); //'Most Dangerous Point: ' .
            $this->imageStringWrapped($image, $fontSize, $x, $y + 15 * $lineHeight + 5,  $data->tbm->countermeasure , $fontColor, 550); //'Countermeasure: ' .
            // imagestring($image, $fontSize, $x, $y + 15 * $lineHeight + 5,  $data->tbm->countermeasure, $fontColor); //'Countermeasure: ' .
            $this->imageStringWrapped($image, $fontSize, $x, $y + 20 * $lineHeight + 5,  $data->tbm->key_word , $fontColor, 550); //'Countermeasure: ' .
            // imagestring($image, $fontSize, $x, $y + 20 * $lineHeight + 5,  $data->tbm->key_word, $fontColor); //'Keyword: ' .
            
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

            $outputPath = public_path('/tbm'.$request->tbm_id.'.jpg');
            imagejpeg($image, $outputPath);
            imagedestroy($image);

            return response()->json(['message' => 'Report generated successfully', 'path' => $outputPath,'url'=>'/tbm'.$request->tbm_id.'.jpg']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }

       
        
    }
    private function imageStringWrapped($image, $fontSize, $x, $startY, $text, $color, $maxWidth) {
        $lines = explode("\n", $text); // Split the text into lines on explicit newlines
        $y = $startY;
    
        foreach ($lines as $line) {
            $words = explode(' ', $line);
            $string = '';
    
            foreach ($words as $word) {
                // Check if adding the new word would exceed the line width
                $testLine = $string . ($string ? ' ' : '') . $word;
                $testLineLength = imagefontwidth($fontSize) * strlen($testLine);
                if ($testLineLength > $maxWidth) {
                    // If it does, write the current line and start a new one
                    imagestring($image, $fontSize, $x, $y, trim($string), $color);
                    $string = $word;
                    $y += imagefontheight($fontSize);  // Move to the next line
                } else {
                    // Otherwise, add the word to the current line
                    $string .= ($string ? ' ' : '') . $word;
                }
            }
            // Write the remaining text of the line and move to the next line
            if (trim($string)) {
                imagestring($image, $fontSize, $x, $y, trim($string), $color);
                $y += imagefontheight($fontSize);  // Prepare y position for the next line
            }
        }
    }
    

}
