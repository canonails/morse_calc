<?php
define("FILE","read_in.txt");
define("BASE", getcwd()."\\");

include_once("lib/Morse.class.php");

$mc = new Morse(
	250,			// unit_length
	"alpha.json"	// alphabet file
);

echo "The file: " . FILE . " will take:\n";
echo $mc->Translate(BASE.FILE) . "\n";

?>