<?php

include("configajax.php");


$pdo = connect();
$keyword = '%'.$_POST['keyword'].'%';


$sql = "SELECT DISTINCT  fournisseur FROM caisse  WHERE fournisseur LIKE (:keyword) and type='sortie' LIMIT 0, 10";


$query = $pdo->prepare($sql);
$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
$query->execute();
$list = $query->fetchAll();
foreach ($list as $rs) {
  // put in bold the written text
  $country_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['fournisseur']);
  // add new option
    echo '<li onclick="set_item5(\''.$rs['fournisseur'].'\')">'.$country_name.'</li>';
    


}
?>