<?php

include("configajax.php");


$pdo = connect();
$keyword = '%'.$_POST['keyword'].'%';


$sql = "SELECT DISTINCT  mad_agence FROM caisse  WHERE mad_agence LIKE (:keyword) and type='entree' LIMIT 0, 10";


$query = $pdo->prepare($sql);
$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
$query->execute();
$list = $query->fetchAll();
foreach ($list as $rs) {
  // put in bold the written text
  $country_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['mad_agence']);
  // add new option
    echo '<li onclick="set_item_mad_agence(\''.$rs['mad_agence'].'\')">'.$country_name.'</li>';

}
?>