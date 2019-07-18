<?php

	function activityController($f, $newArray){
		$filename = $f.date("Ym",strtotime("today")).".json";
		$file = fopen($filename,"a+");
		if($file==false){
			echo "error couldnot open file";
			return false;
		}
		if(filesize($filename)<=5){
			$content_json = "";
			$content_array =array(
				array(
					"date" => date("Y-m-d",strtotime("now")),
					"time" => date("H:i",strtotime("now")),
					"activity" => "Started new session",
					"user" => ""
				)
			);
		}else{
		$content_json = fread($file, filesize($filename));
		$content_array = json_decode($content_json,true);
		echo "<pre>";
		var_dump($content_array);
		echo "</pre>";
		}
		fclose($file);
		array_push($content_array, $newArray);

		$content_json = json_encode($content_array);


		$file = fopen($filename,"w+");
		if($file==false){
			echo "error could not open file";
			return false;
		}
		$write = fwrite($file, $content_json);
		if(!$write){
			echo "error could not write file";
			return false;
		}
		fclose($file);
		return true;

	}

	function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return implode(' ', $words);
}

?>