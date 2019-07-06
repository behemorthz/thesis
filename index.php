<?php

$keep = file("test.pml");

$text = '<basecode>';
$j = count($keep);
for($i=0;$i<$j;$i++){
    
    if(preg_match('/if/', $keep[$i]))
    {
        $text .= "<if>";
        $text .= "<line>";
        $text .= $i+1;
        $text .= "</line>";
        $text .= "<code>";
        $text .= htmlspecialchars($keep[$i]);
        $text .= "</code>";
    }
    else if(preg_match('/fi/', $keep[$i])){
        $text .= "<line>";
        $text .= $i+1;
        $text .= "</line>";
        $text .= "<code>";
        $text .= htmlspecialchars($keep[$i]);
        $text .= "</code>";
        $text .= "</if>";
    }
    if(preg_match('/^do/', $keep[$i]))
    {
        $text .= "<do>";
        $text .= "<line>";
        $text .= $i+1;
        $text .= "</line>";
        $text .= "<code>";
        $text .= htmlspecialchars($keep[$i]);
        $text .= "</code>";
    }
    else if(preg_match('/^od/', $keep[$i])){
        $text .= "<line>";
        $text .= $i+1;
        $text .= "</line>";
        $text .= "<code>";
        $text .= htmlspecialchars($keep[$i]);
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
        $text .= htmlspecialchars($keep[$i]);
        $text .= "</code>";
        $text .= "</con>";
    }
}
$text .= '</basecode>';

$string = '<?xml version="1.0"?>' . $text;

$xml = new SimpleXMLElement($string);
header("Content-type: text/xml");
echo $xml->asXML();

?>
