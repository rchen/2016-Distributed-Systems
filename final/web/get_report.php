<?php
if (isset($_POST['jid']) && $_POST['jid']!=''){
  include_once 'log/'.$_POST['jid'].'.php';
  $report = json_decode($output);
  echo '<table class="tftable2"><tr><th>Country</th><th>2016 Ranking</th><th>2016 PageRank</th><th>2015 Ranking</th><th>2015 PageRank</th></tr>';
  foreach($report as $key => $value){
     echo '<tr><td>'.$value->country.'</td><td>'.(intval($value->ranking2016)+1).'</td>
               <td>'.$value->pagerank2016.'</td><td>'.(intval($value->ranking2015)+1).'</td>
               <td>'.$value->pagerank2015.'</td></tr>';
  }
  echo '</table>';
}

?>
