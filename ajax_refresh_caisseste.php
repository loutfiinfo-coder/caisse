<?php

include("configajax.php");


$pdo = connect();
$keyword = '%'.$_POST['keyword'].'%';







	  $user = $pdo->query('SELECT ste from users where id='.$_SESSION['idcaisse']);
	  $user_detail = $user ->fetch();
	  $user->closecursor();


            $stes= explode( ',', $user_detail['ste']);
            $steuser= "";
            $lines_total = count($stes);
            for ($i = 0; $i < $lines_total; $i++) 
            {
            $steuser = $steuser."'".$stes[$i]."',";
            }
            $steuser=rtrim($steuser,",");


      $societe="";

      if($user_detail['ste']<>"")
      {
        $societe=" and id in(".$steuser.") ";
      } 
      else
      {
        $societe=" and id in(-1) ";
      }


$sql = "SELECT DISTINCT  succursale FROM succursale  WHERE succursale LIKE (:keyword)  ".$societe."  LIMIT 0, 10";


$query = $pdo->prepare($sql);
$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
$query->execute();
$list = $query->fetchAll();
foreach ($list as $rs) {
  // put in bold the written text
  $country_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['succursale']);
  // add new option
    echo '<li onclick="set_item_caisseste(\''.$rs['succursale'].'\')">'.$country_name.'</li>';

}

?>













