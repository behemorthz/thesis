<?php
$keep = file("test1.pml");
$j = count($keep);
// echo "<script>console.log('.$j.')</script>";
// echo "<script>console.log('.$keep[$j].')</script>";
$que = new SplQueue();
$proc = array();
$answer = array();

for($k=0;$k<$j;$k++){
  if(preg_match('/init/',$keep[$k])){
    $que->enqueue($keep[$k]);
  }
  else if(preg_match('/proctype/',$keep[$k])){
    array_push($proc,$keep[$k]);
  }
  else if(preg_match('/GOoperation/',$keep[$k])){
    array_push($proc,$keep[$k]);
  }
  else if(preg_match('/atomic/',$keep[$k])){
    $que->enqueue(trim($keep[$k]));
  }
  else if(preg_match('/run/',$keep[$k]) && preg_match('/    /',$keep[$k])){
    $que->enqueue(trim($keep[$k]));
  }
  else if(preg_match('/  /',$keep[$k])){
    array_push($proc,$keep[$k]);
  }
  else if(preg_match('/byte/',$keep[$k])){
    $que->enqueue($keep[$k]);
  }
  else if(preg_match('/}/',$keep[$k])){
    array_push($proc,$keep[$k]);
  }
  else{
    $que->enqueue($keep[$k]);
  }
}

// echo '<pre>';
// print_r($que);
// echo  '</pre>';
// echo '<pre>';
// print_r($proc);
// echo  '</pre>';
// echo '<br>';

while(!($que ->isEmpty())){
  $que ->rewind();
  $oper=$que ->current();
  // print($oper);
  $que ->dequeue();
  if(preg_match('/run/',$oper)){
    array_push($answer,$oper);
    $nproc = cutprocname($oper);
    // echo '<p>nproc</p>';
    // print($nproc);
    getProc($nproc);
  }
  else{
    array_push($answer,$oper);
  }

}
genxml($answer);

function cutprocname($procname){
  $lpos = strpos($procname, '(');
  $nproc = substr($procname,3,$lpos-2);
  return $nproc;
}

function getProc($nproc){
  $put = false;
  global $answer;
  global $proc;
  for($i=0;$i<count($proc);$i++)
  {
    if (strpos($proc[$i],$nproc ) !== false) {
      $put=true;
    }
    if(strpos($proc[$i],'}')!==false){
      $put=false;
    }
    if($put==true){
      array_push($answer,$proc[$i]);
    }
  }
}
// echo '<pre>';
// print_r($answer);
// echo  '</pre>';

function genxml($answer){
  $j=count($answer);
  // echo "<h1>answer</h1>";
  // print($j);
  $text = '<basecode>';

  for($i=0;$i<$j;$i++){

      if(preg_match('/if/', $answer[$i]))
      {
          $text .= "<if>";
          $text .= "<line>";
          $text .= $i+1;
          $text .= "</line>";
          $text .= "<code>";
          $text .= htmlspecialchars($answer[$i]);
          $text .= "</code>";
      }
      else if(preg_match('/fi/', $answer[$i])){
          $text .= "<line>";
          $text .= $i+1;
          $text .= "</line>";
          $text .= "<code>";
          $text .= htmlspecialchars($answer[$i]);
          $text .= "</code>";
          $text .= "</if>";
      }
      if(preg_match('/^do/', $answer[$i]))
      {
          $text .= "<do>";
          $text .= "<line>";
          $text .= $i+1;
          $text .= "</line>";
          $text .= "<code>";
          $text .= htmlspecialchars($answer[$i]);
          $text .= "</code>";
      }
      else if(preg_match('/^od/', $answer[$i])){
          $text .= "<line>";
          $text .= $i+1;
          $text .= "</line>";
          $text .= "<code>";
          $text .= htmlspecialchars($answer[$i]);
          $text .= "</code>";
          $text .= "</do>";
      }
      else
      {
          $text .= "<con>";
          $text .= "<line>";
          $text .= $i+1;
          $text .= "</line>";
          $text .= "<code>";
          $text .= htmlspecialchars($answer[$i]);
          $text .= "</code>";
          $text .= "</con>";
      }
  }
  $text .= '</basecode>';

  $str = '<?xml version="1.0"?>' . $text;

  $xml = new SimpleXMLElement($str);
  header("Content-type: text/xml");
  echo $xml->asXML();
}
?>
