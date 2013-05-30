<?php 
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

if (isset($_GET['roomaNr']) && is_numeric($_GET['roomaNr']) && $_GET['roomaNr'] > 0)
	echo "Converdin ".$_GET['roomaNr']." -> ".ConverdiRoomaks($_GET['roomaNr']);
?>
<form method="get">
<input type="text" placeholder="Sisesta tavaline number" title="Sisesta suvaline number" name="roomaNr" value=""><br>
<input type="submit" value="Sisesta">
</form>