<?php

$name="Level-A1-1990393516.mp3";
$expression="Hello, I'm Ana. What's your name?";
$folder="Audio_Exportado";
$voice="Joanna"; 


// criar folder se não existe, gera endero onde será salvo, informa variáveis faltantes 
if($folder!=Null and $name!=Null and $expression!=Null){
    if (!is_dir($folder)) {
            mkdir($folder);         
        }
    $filename=htmlspecialchars($folder) . '/'. $name;    
}
else{
    echo "falta a variavel name, expression ou folder";
    return;
}

echo $filename;

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
echo $resultData;

$myfile = fopen($filename, "w") or die("Unable to open file!");
fwrite($myfile, $resultData);
fclose($myfile);

echo myfile;
?>
