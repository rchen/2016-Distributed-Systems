<?php
echo '<table class="tftable" border="1"><tr><th>job_id</th><th>input table</th><th>output table</th><th>execution time(s)</th><th> </th></tr>';
$files = scan_dir('log/');
$i=0;
$tbstr='';
//print_r($files);
foreach ($files as $file){
  if ($i==0 && strpos($file,'.log')){
    $exestr = get_exec() . '<img src="./loading.gif" />' ;
    $tbstr = $tbstr . '<tr><td>' . explode('.',$file)[0] . '</td><td>' .file_get_contents('log/'. $file) . '</td><td></td><td></td><td> ' .$exestr . ' </td></tr>';
  }else if($i>0 && strpos($file,'.log') && $files[$i-1]== explode('.',$file)[0].'.php'){
    $pstr = explode("\n",file_get_contents('log/'. $file));
    $tbstr = $tbstr . '<tr style="cursor:pointer" onclick="getreport(\''.explode('.',$file)[0].'\');"><td>' .  explode('.',$file)[0] . '</td>
                       <td>' . $pstr[0] . '</td><td>' .$pstr[1].  '</td><td>' . $pstr[2] . '</td><td>end</td></tr>';
  }
  $i=$i+1;
}
echo $tbstr . '</table>';

function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();    
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}

function get_exec(){
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
        return str_replace($host,$ipstr,substr($value,$lindex,$rindex-$lindex+1)).'正在執行Spark中</a>';
      }
    }
    return '';
}

?>
