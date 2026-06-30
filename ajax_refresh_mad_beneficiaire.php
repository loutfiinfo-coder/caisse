<?php

include("configajax.php");


$pdo = connect();
$keyword = '%'.$_POST['keyword'].'%';


$sql = "SELECT DISTINCT  mad_beneficiaire FROM caisse  WHERE mad_beneficiaire LIKE (:keyword) and type='entree' LIMIT 0, 10";


$query = $pdo->prepare($sql);
$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
$query->execute();
$list = $query->fetchAll();
foreach ($list as $rs) {
  // put in bold the written text
  $country_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['mad_beneficiaire']);
  // add new option
    echo '<li onclick="set_item_mad_beneficiaire(\''.$rs['mad_beneficiaire'].'\')">'.$country_name.'</li>';

}
?>