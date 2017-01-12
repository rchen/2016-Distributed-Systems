<html>
<head>
<meta charset="UTF-8">
<title>Title of the document</title>
</head>

<body>
<a href="./">回首頁</>
<br>
<br>
<br>
<?php
if (isset($_POST['event']) && $_POST['event']!='-------' && $_POST['event']!=''){
  shell_exec("/var/www/html/exeproject.sh " . $_POST['event'] . "> /dev/null 2>/dev/null &");
  sleep(3);
  /*while(1){
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
        echo str_replace($host,$ipstr,substr($value,$lindex,$rindex-$lindex+1)).'正在執行Spark中</a>';
        exit;
      }
    }
    sleep(1);
  }*/
  header('Location: ./');
}
?>
</body>
</html>
