<?php

include("configajax.php");


$pdo = connect();
$keyword = '%'.$_POST['keyword'].'%';


$sql = "SELECT DISTINCT  mad_compte FROM caisse  WHERE mad_compte LIKE (:keyword) and type='entree' LIMIT 0, 10";


$query = $pdo->prepare($sql);
$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
$query->execute();
$list = $query->fetchAll();
foreach ($list as $rs) {
  // put in bold the written text
  $country_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['mad_compte']);
  // add new option
    echo '<li onclick="set_item_mad_comptem(\''.$rs['mad_compte'].'\')">'.$country_name.'</li>';

}
?>