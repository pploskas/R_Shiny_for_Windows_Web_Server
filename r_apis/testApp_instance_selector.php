<html>
<head>
	<title>testApp</title>
</head>

<p><strong>Entering testAPP - please wait...</strong></p><br>

<body>
<?php 

function clean_string($protasi) {
	$linos=$protasi;
	for (;;) 
		{
		if (!str_contains($linos, '  ')) 
			break;
		$linos = str_replace("  ", " ",$linos);
		}
    $linos=trim($linos);
	return $linos;
}


function check_port($target_PORT) {
$target_IP="10.38.23.51";
$target_IPPORT=$target_IP.":".$target_PORT;
$target_command="netstat -ano | find \"".$target_IPPORT."\" 2>NUL";
exec($target_command, $task_list);

$target_LISTENING=0;
$target_ESTABLISHED=0;

for ($j=0;$j< count($task_list);$j++) 
	{
	$linos=clean_string($task_list[$j]);
	$pinakas = explode(' ', strval($linos));
	if(($pinakas[1]==$target_IPPORT)&&($pinakas[3]=='LISTENING'))
		{
		$target_LISTENING=1;
		$current_PID=$pinakas[4];
		}
	elseif(($pinakas[1]==$target_IPPORT)&&($pinakas[3]=='ESTABLISHED'))
		$target_ESTABLISHED=1;
	}


if(($target_ESTABLISHED==0)&&($target_LISTENING==1))
	{
	$target_command="taskkill /pid ".$current_PID." /f";
	exec($target_command, $task_list);
	for ($j=0;$j< count($task_list);$j++) 
		{
		if ((str_contains($task_list[$j], 'SUCCESS: The process with PID'))&&(str_contains($task_list[$j], 'has been terminated.')))
			{
			$target_LISTENING=0;
			break;
			}
		}
	}
if($target_ESTABLISHED==1)
	return 2;
elseif($target_LISTENING==1)
	return 1;
else
	return 0;
}



function getRandomWord($len) {
    $word = array_merge(range('a', 'z'), range('A', 'Z'));
    shuffle($word);
    return substr(implode($word), 0, $len);
}

$PORTS=array("50001","50002","50003","50004","50005","50006","50007","50008","50009","50010");

$PORTS_AVAILABLE=array_fill(0, count($PORTS), 0);
for ($i=0;$i<count($PORTS);$i++) 
	$PORTS_AVAILABLE[$i]=check_port($PORTS[$i]);
$target_index=-1;
for ($i=0;$i< count($PORTS);$i++) 
	{
	if($PORTS_AVAILABLE[$i]==0)
		{
		$target_index=$i;
		break;
		}
	}
if($target_index==(-1))
	{
	die("No testApp+ instance avalaible!");
	}

set_time_limit(6000);
$myfile = fopen("Instance".($target_index+1)."\\toexe2.txt", "w") or die("Error in file Process!");
$sentence =  "\"C:\Program Files\R\R-4.1.2\bin\Rscript\" -e .libPaths(c('C:/Users/pploskas/Documents/R/win-library/4.1'));shiny::runApp('C:\\\\Users\\\\pploskas\\\\Documents\\\\testApp\\\\app".($target_index+1).".r')";
fwrite($myfile, $sentence);
fclose($myfile);

$myfile = fopen("Instance".($target_index+1)."\\toexe.txt", "w") or die("Error in file Process!");
$sentence =  "Starter2.exe";
fwrite($myfile, $sentence);
fclose($myfile);



passthru("cd Instance".($target_index+1)." && Starter1.exe >> log_file.log 2>&1 &");
sleep(5);

$zlexi2=getRandomWord(10);

$defile = fopen("C:\\Users\pploskas\\Documents\\testApp\\the_password".($target_index+1).".txt", "w");
fwrite($defile, $zlexi2);
fclose($defile);


$zlexi = "Location: http:/10.38.23.51:5000".($target_index+1)."/?".$zlexi2;
header($zlexi);
?>
</body>
</html>
