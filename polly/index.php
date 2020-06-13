<?php
$row=1;

if (($handle = fopen("Master.tsv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 2000, "\t")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
	
    /*   
    for ($c=0; $c < $num; $c++) {
        echo $data[$c] . "<br />\n";
    }
	
    echo "--------- <br />\n"; // Phrase1
	
	echo "Phrase1:".$data[0]. "<br />\n"; // Phrase1
	echo "voz1:".$data[1]. "<br />\n"; // voz
	echo "name1:".$data[19]. "<br />\n"; // name

	echo "Phrase2:".$data[6]. "<br />\n"; //Phrase2
	echo "voz2:".$data[7]. "<br />\n"; //voz
	echo "name2:".$data[20]. "<br />\n"; // name
	
	*/
    
	if ($data[1] =="Ana") {
        $data[1] = "Joanna";
    } else if ($data[1] =="Eva") {
        $data[1] ="Ivy";
    } else if ($data[1] =="John") {
        $data[1] ="Joey";
    }   
 	
	if ($data[7] =="Ana") {
        $data[7] = "Joanna";
    } else if ($data[7] =="Eva") {
        $data[7] ="Ivy";
    } else if ($data[7] =="John") {
        $data[7] ="Joey";
    }   

    for ($z=0; $z < 2; $z++) {
 	$folder="Audio_Exportado";   
	
	if($z==0){
		$name=$data[19];
		$expression=$data[0];
		$voice=$data[1]; 
	}
	else if ($z==1){
		$name=$data[20];
		$expression=$data[6];
		$voice=$data[7]; 
	
	}
	

// criar folder se não existe, gera endero onde será salvo, informa variáveis faltantes 
if($folder!=Null and $name!=Null and $expression!=Null){
    if (!is_dir($folder)) {
            mkdir($folder);         
        }
    $filename=htmlspecialchars($folder) . '/'. $name;    
}
else{
    if($name==Null){
        echo "falta a variavel name";
    }
    if($expression==Null){
        echo "falta a variavel expression";
    }
    return;
}

//echo $filename;

// polly
require_once 'aws/aws-autoloader.php';
$awsAccessKeyId = 'AKIAJ5NA5QAFGP7THXNA';//'AKIAJVD6EZ5YLKNERTUA';
$awsSecretKey   = '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';//'67iSdgHxKzTmy+e2QnqiXkKME8q6KvDPHAOyFTmx';
$credentials    = new \Aws\Credentials\Credentials($awsAccessKeyId, $awsSecretKey);
$client         = new \Aws\Polly\PollyClient([
    'version'     => '2016-06-10',
    'credentials' => $credentials,
    'region'      => 'us-east-1',
]);
$result         = $client->synthesizeSpeech([
    'OutputFormat' => 'mp3',
    'Text'         => $expression,
    'TextType'     => 'text',
    'VoiceId'      => $voice,
]);
$resultData     = $result->get('AudioStream')->getContents();
//echo $resultData;

$myfile = fopen($filename, "w") or die("Unable to open file!");
fwrite($myfile, $resultData);
fclose($myfile);



//echo myfile;


}



  }
  fclose($handle);
}
?>
