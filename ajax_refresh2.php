<?php

include("configajax.php");


$pdo = connect();
$keyword = '%'.$_POST['keyword'].'%';


$sql = "SELECT DISTINCT  client FROM caisse  WHERE client LIKE (:keyword) and type='entree' LIMIT 0, 10";


$query = $pdo->prepare($sql);
$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
$query->execute();
$list = $query->fetchAll();
foreach ($list as $rs) {
	// put in bold the written text
	$country_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['client']);
	// add new option
    echo '<li onclick="set_item2(\''.$rs['client'].'\')">'.$country_name.'</li>';
    


}
?>