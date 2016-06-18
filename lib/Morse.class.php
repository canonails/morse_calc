<?php
class Morse{
	private $unit_length;
	private $unit_dash;
	private $unit_in_letter_space;
	private $unit_letter_space;
	private $unit_word_space;
	private $alpha_arr;
	
	public function Morse($ul, $abc){
		$this->unit_length = $ul;
		$this->unit_dash = $ul * 3;
		$this->unit_in_letter_space = $ul;
		$this->unit_letter_space = $ul * 3;
		$this->unit_word_space = $ul * 7;
		$this->load_alpha($abc);
	}
	
	public function Translate($file){
		$text = preg_replace('@[^A-Za-z0-9\s]@',"",trim(file_get_contents($file)));
		$text = explode(" ",preg_replace('@\s+@'," ",$text));
		$total = 0;
		foreach($text as $t){
			$total += $this->word_length($t);
		}
		$total += (count($text) - 1) * $this->unit_word_space;
		return $this->time_format($total / 1000);
	}
	
	private function time_format($secs){
		$time = explode(".",$secs);
		$mins = 0;
		$s = $time[0];
		for($i = $time[0]; $i >= 60; $i -= 60){
			$mins += 1;
			$s -= 60;
		}
		return $mins.":".$s.":".$time[1];
	}
	
	///The following are testing public functions
	public function GetChr($chr,$len_only = false){
		if($len_only){
			return $this->alpha_arr[strtoupper($chr)]["l"];
		}else{
			return $this->alpha_arr[strtoupper($chr)];
		}
	}
	
	public function GetWord($str){
		return $this->word_length($str);
	}
	
	public function SpaceTotal($num_spaces){
		return $num_spaces * $this->unit_word_space;
	}
	///End of testing functions
	
	private function word_length($str){
		$chrs = str_split($str);
		$chrs_len = 0;
		foreach($chrs as $c){
			$chrs_len += $this->GetChr($c,true);
		}
		$total = ((count($chrs) - 1) * $this->unit_letter_space) + $chrs_len;
		return $total;
		
	}
	
	private function load_alpha($abc){
		$abc_in = file_get_contents(BASE . $abc);
		$alpha_arr = json_decode($abc_in,true);
		$this->alpha_arr = array();
		foreach($alpha_arr as $k => $v){
			$dots = (strlen(preg_replace('@-@',"",$v))) * $this->unit_length;
			$dashes = (strlen(preg_replace('@\.@',"",$v))) * $this->unit_dash;
			$spaces = (strlen($v) - 1) * $this->unit_in_letter_space;
			$this->alpha_arr[strtoupper($k)] = array(
				"a" => strtoupper($k),
				"m" => $v,
				"l" => $dots + $dashes + $spaces
			);
		}
	}
}
?>