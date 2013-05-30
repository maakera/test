<?php

session_start();
mysql_connect("db_server", "db_user", "db_pass");
mysql_select_db("ul3");
mysql_query("CREATE TABLE IF NOT EXISTS ul3 (id int NOT NULL AUTO_INCREMENT, kumnendarv, rooma_number, PRIMARY KEY(id))");
if (isset($_GET['submitForm']))
{
if ($_SESSION['user'] == "")
{
 if ($_GET['user'] != "")
 {
   $_SESSION['user'] = $_GET['user'];
 }
}

if (isset($_GET['nr']) && is_numeric($_GET['nr']) && $_GET['nr'] > 0){
	echo "<br>Vastus: ".ConverdiRoomaks($_GET['nr']);
	$nr = (int)$_GET['nr'];
	  $q = mysql_query("SELECT * FROM roman WHERE kumnendarv=$nr");
	  $r = mysql_fetch_array($q);
	  if (sizeof($r) == 0)
		$res = ConverdiRoomaks($num);
	  else
		$res = $r['rooma_number'];
	}
}


if (isset($_GET['ajax']))
{
	if (isset($_GET['search']))
	{
	  $q = mysql_query("SELECT * FROM roman ORDER BY id DESC LIMIT 10");
	  if ($_GET['type'] == "XML")
		echo "<NUMBERS>";
		$i = 0;
	  while ($r = mysql_fetch_array($q))
	  {
	  $i++;
	  if ($_GET['type'] == "none")
		echo $r['kumnendarv']."=".$r['rooma_number']."\n";
		if ($_GET['type'] == "XML")
		  echo "<entry><integer>".$r['kumnendarv']."</integer><roman>".$r['rooma_number']."</roman></entry>";
		else
		  $json[] = array('arv'=>$r['kumnendarv'],"rooma"=>$r['rooma_number']);
	  }
	  if ($_GET['type'] == "XML")
		echo "<total>".$i."</total></NUMBERS>";
	  if ($_GET['type'] == "json"){
		$json['total'] = $i;
		echo json_encode($json);
		}
	}

die();
}


function ConverdiRoomaks($num){ 
    $n = intval($num); 
    $rooma_nr = array('M'  => 1000, 'CM' => 900, 'D'  => 500, 'CD' => 400, 
	'C' => 100, 'XC' => 90, 'L'  => 50, 'XL' => 40, 'X'  => 10, 'IX' => 9, 
	'V'  => 5, 'IV' => 4, 'I'  => 1); 
	$tulemus = '';
    foreach ($rooma_nr as $rm => $nr){ 
        $tul = intval($n / $nr); 
        $res .= str_repeat($rm, $tul); 
        $n = $n % $nr; 
    } 
    return $res; 
} 

if ($_SESSION['user'] != "")
	echo "Kasutaja: ".$_SESSION['user'];
?>

<script src="http://code.jquery.com/jquery-2.0.1.min.js"></script>
<form method="get">
<?php
if ($_SESSION['user'] == ""){
?>
<input type="text" name="user" placeholder="nimi (valikuline)" title="valikuline"><br>
<?php
}
?>
<input type="text" name="nr" placeholder="number (kohustuslik)" id="selectedNr" title="kohustuslik"><br>
<input type="submit" name="submitForm" value="Valmis"><br>
</form>
<button onclick="$.ajax({url:'ul3.php?ajax=1&type=none&search=1&nr='+$('#selectedNr').val(), success:function(data){
alert(data);
}});">Kuva</button>
<a href="ul3.php?ajax=1&type=json&search=1">JSON</a>
<a href="ul3.php?ajax=1&type=XML&search=1">XML</a>