<?php

include("configajax.php");


$pdo = connect();
$keyword = '%'.$_POST['keyword'].'%';


$sql = "SELECT DISTINCT  ref_piece FROM caisse  WHERE ref_piece LIKE (:keyword) and type='entree' LIMIT 0, 10";


$query = $pdo->prepare($sql);
$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
$query->execute();
$list = $query->fetchAll();
foreach ($list as $rs) {
  // put in bold the written text
  $country_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['ref_piece']);
  // add new option
    echo '<li onclick="set_item3(\''.$rs['ref_piece'].'\')">'.$country_name.'</li>';
    


}
?>