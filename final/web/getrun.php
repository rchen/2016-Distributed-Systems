<html>
<head>
<meta charset="UTF-8">
<title>Title of the document</title>
</head>

<body>
<?php
$ipstr=$_SERVER['HTTP_HOST'];
$host=exec('hostname');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://'.$ipstr.':8088/cluster');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$html=curl_exec($ch);
curl_close($ch);
$lindex=strpos($html,'var appsTableData=')+21;
$rindex=strpos(substr($html,$lindex,-1),'</script>')-16;
$rows=explode('["<a',substr(substr($html,$lindex,-1),0,$rindex));
foreach ($rows as &$value) {
  if (strpos($value,'RUNNING')>0){
    $lindex=strpos($value,"<a href='http:");
    $rindex=strpos($value,'>ApplicationMaster');
    echo str_replace($host,$ipstr,substr($value,$lindex,$rindex-$lindex+1)).'go</a>';
  }
}
//echo passthru("/var/www/html/exeproject.sh");
?>
</body>
</html>
