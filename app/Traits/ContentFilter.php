<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait ContentFilter
{
    public function filterContent($request){
        $inputText = $request->extracted_content; 
        $cleanedText = preg_replace('/[^a-zA-Z0-9\s.]/', '', $inputText); 
        $output = '';
        $removableWords = array(
           'a', 'an', 'the', 'is', 'are', 'am', 'was', 'were', 'and', 'with', 
            'some', 'has', 'have', 'had', 'to', 'of', 'in', 'on', 'at', 'by',
            'for', 'as', 'if', 'or', 'but', 'not', 'you', 'we', 'they', 'he',
            'she', 'it', 'i', 'me', 'my', 'mine', 'your', 'yours', 'his', 'her',
            'its', 'our', 'ours', 'their', 'theirs', 'this', 'that', 'these',
            'those', 'there', 'here', 'when', 'where', 'why', 'how', 'all', 
            'any', 'each', 'both', 'every', 'other', 'such', 'no', 'yes',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z','who','also','won',
            'from','within','to','up','down','again','before','start','stop','sad','through',
            'since','for','used','actually','could','been','which','make',
            'until','known','him','later','then','than','after','minimum','following','follow',
            'able','becomes','because','undo','redo','would','called','few','us','many','made','using',
            'support','allow','reform','early','include','exclude','making','whereBy','waiting','line',
            'getting','cast','contact','ids','document','id','created','primary','maintain by',
            'reporting','quality','info','contents','contact','should','nor','fax','phone','email',
            'date','end','and'

        );
        $cleanedLines = explode("\r\n", $cleanedText);
        foreach($cleanedLines as $cleanedLine) {
            $cleanWords = explode(' ', $cleanedLine);
            foreach($cleanWords as $cleanWord) {
                if (!in_array(strtolower($cleanWord), $removableWords)) {
                    $output .= $cleanWord . ' ';
                }
            }
            $output .= "\n";
        }
        $cleanedString = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $output);
        $cleanedString = trim($cleanedString, "\n");
        return $cleanedString;
    }
}
