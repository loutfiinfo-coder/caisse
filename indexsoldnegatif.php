<?php 
include("config.php");

?>






<?php

if(isset($_SESSION['idcaisse']))
{

$user = $bdd->query('SELECT id,pages,login,ste,exportexcel,colonnecaisse,colonneentree,colonnesortie from users where id='.$_SESSION['idcaisse']);
$user_detail = $user ->fetch();
$user->closecursor();

}
else
{
header('location:conn.php'); 
}




            $stes= explode( ',', $user_detail['ste']);
            $steuser= "";
            $lines_total = count($stes);
            for ($i = 0; $i < $lines_total; $i++) 
            {
            $steuser = $steuser."'".$stes[$i]."',";
            }
            $steuser=rtrim($steuser,",");





if(isset($_POST['enregistrer']))
{


    $colonnecaisse = "";

    if(isset($_POST['caisse']))
      $colonnecaisse =$colonnecaisse."caisse|";
    if(isset($_POST['reference']))
      $colonnecaisse =$colonnecaisse."reference|";
    if(isset($_POST['dateop']))
      $colonnecaisse =$colonnecaisse."dateop|";
    if(isset($_POST['libelle']))
      $colonnecaisse =$colonnecaisse."libelle|";
    if(isset($_POST['nature_entree']))
      $colonnecaisse =$colonnecaisse."nature_entree|";
    if(isset($_POST['type_alimentation']))
      $colonnecaisse =$colonnecaisse."type_alimentation|";
    if(isset($_POST['famille']))
      $colonnecaisse =$colonnecaisse."famille|";
    if(isset($_POST['client']))
      $colonnecaisse =$colonnecaisse."client|";
    if(isset($_POST['beneficiaire']))
      $colonnecaisse =$colonnecaisse."beneficiaire|";
    if(isset($_POST['notes']))
      $colonnecaisse =$colonnecaisse."notes|";
    if(isset($_POST['acomptabilise']))
      $colonnecaisse =$colonnecaisse."acomptabilise|";
    if(isset($_POST['justifie']))
      $colonnecaisse =$colonnecaisse."justifie|";
    if(isset($_POST['caissier']))
      $colonnecaisse =$colonnecaisse."caissier|";

    $req = $bdd->prepare('UPDATE users  set  colonnecaisse=:colonnecaisse where id=:id');
    $req->execute(array('colonnecaisse' => $colonnecaisse, 'id' => $_SESSION['idcaisse']));

    header('location:index.php#');    
    
}




?>







<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Caisse ..</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="ctm.css" />
  <link rel="icon" type="image/ico" href="logo.ico" />
  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
 <script src="jquery.min.js"></script>
<script>
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html   <meta charset="utf-8"> xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()


</script>
<style>
@-webkit-keyframes demo {


    50% {
        background-color: #f1c94f;
    }
}

.demo {
    background-color:#fff;
    -webkit-animation-name: demo;
    -webkit-animation-duration: 900ms;
    -webkit-animation-iteration-count: infinite;
    -webkit-animation-timing-function: ease-in-out;
}

</style>
</head>
<body onload="defaut()">

<div class="head"  >


<img src="logo.png" style="margin:2px 120px  3px 0px;" >


<a class="menu" href="#" style="background:url(crossword.png) ;color:#00BA84;border-bottom:3px solid #00BA84;"   ><i  class="fa fa-history" ></i> Journal de caisse</a>
<a class="menu" href="sortie.php"  ><i class="fa fa-arrow-up" aria-hidden="true"></i> Sorties</a>
<a class="menu"  href="entree.php"  ><i class="fa fa-arrow-down" aria-hidden="true"></i> Entrées</a>

<a class="menu"  href="famille.php"> <i class="fa fa-th-large" aria-hidden="true"></i> Familles & Types</a>
<a class="menu" href="societe.php" ><i  class="fa fa-archive" ></i> Caisses </a>
<style>
@-webkit-keyframes help {


    50% {
        background-color: #ffff66;
    }
}


#help {
    background-color:#fff;
    -webkit-animation-name: help;
    -webkit-animation-duration: 2000ms;
    -webkit-animation-iteration-count: infinite;
    -webkit-animation-timing-function: ease-in-out;
}

</style>
<a class="menu" href="help.php"  target="_blank" style="color:red;font-size:20px" id="help" >Help !</a>

<a class="menu" href="decon.php" style="color:#00BA84"><i class="fa fa-power-off"></i></a>

</div>

<div style="width:1250px;margin:auto;color:#444;font-size:20px;margin-top:-20px;font-family: 'Oswald',serif;">Bienvenu: <strong style="color:#00BA84;margin-left:5px;text-transform : capitalize;"><?php echo $user_detail['login']; ?></strong></div>




































<div style="width:1220px;border:0px solid black;margin:auto;text-align:right;margin-top:20px;">





<?php



//if($user_detail['login']=='admin') {


      $societe="";

      if($user_detail['ste']<>"")
      {
        $societe=" and idsuccursale in(".$steuser.")";
      } 
      else
      {
        $societe=" and idsuccursale in(-1)";
      }

// total especes entree all succursale
$totalespeceall = $bdd->query("SELECT sum(montant) as totalespeceall from caisse  where deleted='non' and   type='entree' ".$societe." ");
$totalespecealldetail = $totalespeceall ->fetch();
$totalespeceall->closecursor();


// total sortie all succursale
$totalsortieall = $bdd->query("SELECT sum(montant) as totalsortieall from caisse  where deleted='non' and   type='sortie' ".$societe." ");
$totalsortieall_detail = $totalsortieall ->fetch();
$totalsortieall->closecursor();


$totalcaisse = $totalespecealldetail['totalespeceall'] - $totalsortieall_detail['totalsortieall'];

?>


<?php if($totalcaisse >=0 ) {?>
<div style="border:0px solid transparent;width:282px;margin:10px;border-radius:6px;display:inline-block;" >

<p style="border:1px solid transparent;text-align:center;padding:5px;font-size:14px;font-weight:bold;background:#6fe36f;border-radius:10px 10PX 0PX 0PX"> <span style="font-weight:normal">caisse </span> Total </p>

<p style="border:1px solid transparent;text-align:center;padding:10px;font-size:20px;background:#93ea93;border-radius:0PX 0PX 10px 10PX "> <?php echo number_format( $totalcaisse, 2, ',', ' '); ?> <span> DHs</span></p>

</div>

<?php }else {?>

<div style="border:0px solid transparent;width:282px;margin:10px;border-radius:6px;display:inline-block;" >

<p style="border:1px solid transparent;text-align:center;padding:5px;font-size:14px;font-weight:bold;background:#F37E82;border-radius:10px 10PX 0PX 0PX"> <span style="font-weight:normal">caisse </span> Total </p>

<p style="border:1px solid transparent;text-align:center;padding:10px;font-size:20px;background:#f5979b;border-radius:0PX 0PX 10px 10PX "> <?php echo number_format( $totalcaisse, 2, ',', ' '); ?> <span> DHs</span></p>

</div>

<?php }?>


<?php
//}




            $req = $bdd->query("SELECT id,succursale from succursale where etat='encours' order by succursale asc ");
            while ($ste = $req->fetch())
            {

              if(stristr($steuser, "'".$ste['id']."'"))              
              {
          
                // total especes entree par sucursale
                $totalespece = $bdd->query("SELECT sum(montant) as totalespece from caisse  where deleted='non' and   type='entree' and idsuccursale=".$ste['id'] );
                $totalespecedetail = $totalespece ->fetch();
                $totalespece->closecursor();

                // total sortie par sucursale
                $totalsortie = $bdd->query("SELECT sum(montant) as totalsorti from caisse  where deleted='non' and   type='sortie'  and idsuccursale=".$ste['id'] );
                $totalsortie_detail = $totalsortie ->fetch();
                $totalsortie->closecursor();

                $caissesuccursale = $totalespecedetail['totalespece'] - $totalsortie_detail['totalsorti'];


                ?>


                <?php if($caissesuccursale >=0 ) {?>

                <div style="border:0px solid transparent;width:282px;margin:10px;border-radius:6px;display:inline-block" >

                <p style="border:1px solid transparent;text-align:center;padding:5px;font-size:14px;font-weight:bold;background:#DDD;border-radius:10px 10PX 0PX 0PX"><span style="font-weight:normal">Caisse</span> <?php echo $ste['succursale']; ?></p>

                <p style="border:1px solid transparent;text-align:center;padding:10px;font-size:20px;background:#EEE;border-radius:0PX 0PX 10px 10PX "> <?php echo number_format( $caissesuccursale, 2, ',', ' '); ?> <span> DHs</span></p>

                </div>
                <?php }else {?>
                  <div style="border:0px solid transparent;width:282px;margin:10px;border-radius:6px;display:inline-block" >

                <p style="border:1px solid transparent;text-align:center;padding:5px;font-size:14px;font-weight:bold;background:#F37E82;border-radius:10px 10PX 0PX 0PX"><span style="font-weight:normal">Caisse</span> <?php echo $ste['succursale']; ?></p>

                <p style="border:1px solid transparent;text-align:center;padding:10px;font-size:20px;background:#f5979b;border-radius:0PX 0PX 10px 10PX "> <?php echo number_format( $caissesuccursale, 2, ',', ' '); ?> <span> DHs</span></p>

                </div>
                <?php }?>


                <?php

              }
            }
            $req->closecursor();


?>

</div>














































<div class="filtre" style="height:74px;padding:4px 10px 20px 10px;background:#fff">
    <ul class="nav">


        <form method="post" id="form_av" action=""> 

        <input type="hidden" id="id_ste" value="" />

       <fieldset style="width:210px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;margin-right:2px;height:70px;">
        <legend>Caisse</legend>
            <li id="filtrec1"><input style="padding:9px;width:168px" name="ste" type="text" onpaste="return false;" id="country_caisse" onkeyup="autocompletcaisseste();apost('country_caisse')"  autocomplete="off"  <?php if(isset($_POST['ste'])){echo ' value="'.$_POST["ste"].'" ';}elseif(isset($_GET['ste'])){echo ' value="'.$_GET["ste"].'" ';}?>/></li> 
            <ul style="width:181px;color:#333;font-size:15px;margin-left:9px;" id="country_list_caisse"></ul>
        </fieldset>



        <fieldset style="width:200px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;margin-right:2px;height:70px;">
        <legend>Caissier</legend>
            <li id="filtrec1"><input   style="padding:9px;width:158px" name="caissier" type="text" onpaste="return false;" id="caissierf"  onkeyup="apost('caissierf')"  autocomplete="off"  <?php if(isset($_POST['caissier'])){echo ' value="'.$_POST["caissier"].'" ';}elseif(isset($_GET['caissier'])){echo ' value="'.$_GET["caissier"].'" ';}?>/></li> 
        </fieldset>


<?php

if(isset($_POST['affichertt']))
{


    unset($_POST['du1']);
    unset($_POST['au1']);


    echo "<script type='text/javascript'>document.location.href='index.php';</script>";

}


?>




<script>

function societe(ste)
{


 if(ste!="")
 {
 location.href="index.php?ste="+ste; 
 }
 else
 {
 location.href="index.php";
 }


}



function defaut()
{       


        <?php if (isset($_GET['ste'])) {?>
          document.getElementById('id_ste').value=<?php echo $_GET['ste'];?>;
        <?php }else{?>
          document.getElementById('id_ste').value=""; 
        <?php }?>

}

</script>
        



        <fieldset style="width:330px;text-align:center;margin:0px;padding-bottom:11px;display:inline-block;margin-right:2px;height:70px;position:relative;top:-2px">
          <legend>Date opération</legend>
            <li >

            <input style="padding:9px;margin-right:5px;width:130px;padding:6.5px" name="du1" type="date" id="du1" <?php if(isset($_POST['du1'])){echo ' value="'.$_POST["du1"].'" ';}?> /><input style="padding:9px;margin-right:2px;width:130px;padding:6.5px" name="au1" type="date" id="au1" <?php if(isset($_POST['au1'])){echo ' value="'.$_POST["au1"].'" ';}?> /></li> 
        </fieldset>

        
        <input type="submit" name="filtred" id="filtred" value="Filtrer"  style="width:125px;padding:21px 2px;font-size:18px;position:relative;top:2px;margin-right:4px"/>


        <input type="submit" name="affichertt" id="affichertt" value="Rafraichir" style="width:125px;padding:0px;display:inline-block;height:75px;position:relative;top:2px;font-size:20px;margin-right:8px" />


        <input type="button"  id="affichertt" onclick="location.href='#colonnecaisse';" value="Pers. les colonnes" style="width:195px;padding:0px;display:inline-block;height:75px;position:relative;top:0px;font-size:20px;cursor:pointer" />


 
        </form>
    </ul>
</div>



<?php



if(isset($_POST['du1']) and isset($_POST['au1']))
{

if(!empty($_POST['du1']) and !empty($_POST['au1']))
{




    $stechamps="";
    if(!empty($_POST['ste']))
    {

      $succursale11 = $bdd->query("SELECT id FROM succursale WHERE succursale='".trim($_POST['ste'])."'");
      $succursale11_detail = $succursale11->fetch();

      if(!empty($succursale11_detail['id']))
        $stechamps=$stechamps." AND idsuccursale=".$succursale11_detail['id']." ";
      else
        $stechamps=$stechamps." AND idsuccursale=-1 ";

    }
    else
    {

      if($user_detail['ste']<>"")
      {
        $stechamps=$stechamps." and idsuccursale in(".$steuser.")";
      } 
      else
      {
        $stechamps=$stechamps." and idsuccursale in(-1)";
      }

    }



//$caisse debut
$caissed=0;
$totalec=0;
$totalsc=0;


$reqfirstdate = $bdd->query("SELECT dateop from caisse where  deleted='non'  order by dateop asc LIMIT 1");
$firstdate = $reqfirstdate->fetch();

$reqcaisseinitial = $bdd->query("SELECT * from caisse where deleted='non' and  dateop BETWEEN '".$firstdate['dateop']."' and DATE_SUB('".$_POST['du1']."', INTERVAL 1 DAY)  ".$stechamps." ");

while ($caisseinitial = $reqcaisseinitial->fetch())
{

  if($caisseinitial["type"]=='entree')
  {
  $totalec=$totalec + $caisseinitial["montant"];    
  }

  if($caisseinitial["type"]=='sortie')
  {
  $totalsc=$totalsc + $caisseinitial["montant"];    
  }

}

$caissed=$totalec - $totalsc;




//$caisse fin
$caissef=0;
$totalec=0;
$totalsc=0;


$reqcaissefinale = $bdd->query("SELECT * from caisse where deleted='non' and  dateop BETWEEN '".$_POST['du1']."' and '".$_POST['au1']."' ".$stechamps." ");

while ($caissefinale = $reqcaissefinale->fetch())
{

  if($caissefinale["type"]=='entree')
  {
  $totalec=$totalec + $caissefinale["montant"];    
  }

  if($caissefinale["type"]=='sortie')
  {
  $totalsc=$totalsc + $caissefinale["montant"];    
  }

}

$caissef= ($totalec + $caissed) - $totalsc ;


      function changedate1($var)
      {
      $tab = explode("-",$var);
      $nouvelledate = $tab[2]."/".$tab[1]."/".$tab[0];
      return $nouvelledate;
      }


//echo "<p>Caisse ".changedate1(date('Y-m-d', strtotime($_POST['du1'] . ' -1 day')))." = ".number_format($caissed, 2, ',', ' ')." DH</p>";
//echo "<p>Caisse ".changedate1($_POST['au1'])." = ".number_format($caissef, 2, ',', ' ')." DH</p>";

}

}


?>

<div style="overflow-x: scroll;max-width:1360px;margin:auto">

<div id="tableprint" >
<table id="testTable" summary="Code page support in different versions of MS Windows." rules="groups" frame="hsides" border="2" style="margin-top:20px;" >
 

  <?php

    $reqcolonne = $bdd->query('SELECT colonnecaisse FROM users where id='.$_SESSION['idcaisse']);
    $colonnecaisse_detail = $reqcolonne->fetch();
    $reqcolonne->closecursor(); 

  ?>


<?php

if(isset($_POST['du1']) and isset($_POST['au1']))
{

if(!empty($_POST['du1']) and !empty($_POST['au1']))
{

?>
        <tr>
          <th style="background:url(crossword.png);color:#242424;font-weight:bold;font-size:18px;text-align:right" colspan="6"><?php echo "Caisse ".changedate1(date('Y-m-d', strtotime($_POST['du1'] . ' -1 day')))."" ; ?> : </th>
          <th style="background:#B2F0B2;color:#242424;font-weight:bold;font-size:18px" colspan="6"><?php echo number_format($caissed, 2, ',', ' '); ?> DH</th>
        </tr>
        <tr><th style="background:url(crossword.png);padding-top:20px" colspan="11"  ></th></tr>

<?php } } ?>


        <tr>

            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'caisse') !== false){ ?>
                <th>Caisse</th>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'caissier') !== false){ ?>
                <th>Caissier</th>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'reference') !== false){ ?>
                <th>Numéro</th>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'dateop') !== false){ ?>
                <th>Date opération</th>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'libelle') !== false){ ?>
                <th>Libellé</th>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'nature_entree') !== false){ ?>
                <th>Nature entrée</th>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'type_alimentation') !== false){ ?>
                <th>Type alimentation</th>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'famille') !== false){ ?>
                <th>Famille sortie</th>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'client') !== false){ ?>
                <th>Client</th>
            <?php }?>
   

            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'beneficiaire') !== false){ ?>              
                <th>Bénéficiaire</th>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'notes') !== false){ ?>
                <th>Notes</th>   
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'acomptabilise') !== false){ ?>
                <th>à compta.</th>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'justifie') !== false){ ?>
                <th>Justifiée</th>
            <?php }?>


                <th>Entrée (DH)</th>

                <th>Sortie (DH)</th>

        </tr>


<?php
                    // Pagination PHP
                    if(isset($_GET['nb'])){
                        $nb=$_GET['nb'];
                    }
                    else{
                        $nb=50; // initialisation NB lighnes pour la 1ere fois
                    }
                    if(isset($_GET['sf'])){
                        $sf=$_GET['sf']*$nb;
                    }
                    else{
                        $sf=0;
                    }









      $societe="";

      if($user_detail['ste']<>"")
      {
        $societe="   idsuccursale in(".$steuser.")";
      } 
      else
      {
        $societe="   idsuccursale in(-1)";
      }


if(isset($_POST['filtred']))
{

     $champs=" id<>-1 ";


     if( !empty($_POST['du1']) and !empty($_POST['au1']) )
        $champs=$champs." and dateop BETWEEN '".$_POST['du1']."' AND '".$_POST['au1']."'";

     if( !empty($_POST['caissier']))
        $champs=$champs." and caissier like '%".$_POST['caissier']."%'";


    if(!empty($_POST['ste']))
    {

      $succursale11 = $bdd->query("SELECT id FROM succursale WHERE succursale='".trim($_POST['ste'])."'");
      $succursale11_detail = $succursale11->fetch();

      if(!empty($succursale11_detail['id']))
        $champs=$champs." AND idsuccursale=".$succursale11_detail['id']." ";
      else
        $champs=$champs." AND idsuccursale=-1";
    }


     //echo $champs;
     if( !empty($_POST['du1']) and !empty($_POST['au1']) )
     {
        $req = $bdd->query("SELECT * from caisse where deleted='non' and  ".$champs." and ".$societe." order by dateop asc,id asc ");
     }
     else
     {
        $req = $bdd->query("SELECT * from caisse where deleted='non'  and ".$champs." and ".$societe." order by dateop desc,id desc ");
     }


}

else
{


    $req = $bdd->query("SELECT * from caisse where   deleted='non' and  ".$societe."  order by dateop desc,id desc LIMIT ".$sf.",".$nb);



}































$counte=0;
$totale=0;
$counts=0;
$totals=0;



                 

function changedate($var)
{
$tab = explode("-",$var);
$nouvelledate = $tab[2]."/".$tab[1]."/".$tab[0];
return $nouvelledate;
}
                    

while ($cheque = $req->fetch())
{

  if($cheque["type"]=='entree')
  {
  $counte=$counte+1;
  $totale=$totale + $cheque["montant"];    
  }

  if($cheque["type"]=='sortie')
  {
  $counts=$counts+1;
  $totals=$totals + $cheque["montant"];    
  }

?>       


    <tr>


    <?php
    $succursale11 = $bdd->query("SELECT succursale FROM succursale WHERE id=".$cheque['idsuccursale']);
    $succursale11_detail = $succursale11->fetch();

    $famille11 = $bdd->query("SELECT famille FROM famille WHERE id=".$cheque['idfamille']);
    $famille11_detail = $famille11->fetch();

    ?>
  

            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'caisse') !== false){ ?>
                <td style="min-width:80px"> <?php echo $succursale11_detail['succursale'] ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'caissier') !== false){ ?>
                <td style="min-width:80px"> <?php echo $cheque['caissier'] ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'reference') !== false){ ?>
                <td style="min-width:110px" ><?php echo $cheque['ref'] ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'dateop') !== false){ ?>
                <td style="min-width:80px"> <?php echo changedate($cheque['dateop']) ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'libelle') !== false){ ?>
                <td style="min-width:100px" ><?php echo ucfirst($cheque['libelle']); ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'nature_entree') !== false){ ?>
                <td style="min-width:100px" ><?php echo ucfirst($cheque['typeentree']); ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'type_alimentation') !== false){ ?>
                <td style="min-width:100px" ><?php echo $cheque['type_alimentation'] ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'famille') !== false){ ?>
                <td style="min-width:100px" ><?php echo $famille11_detail['famille'] ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'client') !== false){ ?>
                <td style="min-width:80px" ><?php echo $cheque['client'] ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'beneficiaire') !== false){ ?>
                <td style="min-width:80px" ><?php echo $cheque['fournisseur'] ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'notes') !== false){ ?>
                <td style="min-width:100px;white-space: pre-wrap;" ><?php echo $cheque['concerne'] ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'acomptabilise') !== false){ ?>
                <td ><?php echo $cheque['acomptabilise'] ?></td>
            <?php }?>


            <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'justifie') !== false){ ?>
                <td ><?php echo $cheque['valide'] ?></td>
            <?php }?>


                <?php  if($cheque['type']=="entree") {?>
                <td style="min-width:100px;text-align:right;background-color:#6fe36f;padding:5px 20px 5px 0px;font-size:14px" ><?php if($cheque['montant']!="") echo  number_format( $cheque['montant'], 2, ',', ' '); ?></td>
                <?php  }else{?>
                <td style="min-width:100px;text-align:right" ></td>
                <?php  }?>


                <?php  if($cheque['type']=="sortie") {?>
                <td style="min-width:100px;text-align:right;background-color:#f37e82;padding:5px 20px 5px 0px;font-size:14px" ><?php if($cheque['montant']!="") echo  number_format( $cheque['montant'], 2, ',', ' '); ?></td>
                <?php  }else{?>
                <td style="min-width:100px;text-align:right" ></td>
                <?php  }?>

        </tr>




<?php 
}
$req->closecursor();
?> 



<?php

if(isset($_POST['du1']) and isset($_POST['au1']))
{

if(!empty($_POST['du1']) and !empty($_POST['au1']))
{

?>

        <tr ><th style="background:url(crossword.png);padding-top:20px" colspan="11"  ></th></tr>
        <tr>
          <th style="background:url(crossword.png);color:#242424;font-weight:bold;font-size:18px;text-align:right" colspan="6">Caisse <?php echo changedate1($_POST['au1']); ?> : </th>
          <th style="background:#B2F0B2;color:#242424;font-weight:bold;font-size:18px" colspan="6"><?php echo number_format($caissef, 2, ',', ' '); ?> DH</th>
        </tr>


        <tr ><th style="background:url(crossword.png);padding-top:20px" colspan="11"  ></th></tr>
        <tr>
          <th style="background:#fff;color:#242424;font-weight:bold;font-size:18px;text-align:center;padding-bottom:100px" colspan="6"> Visa caissier(e) <?php echo  $user_detail['login'];?></th>
          <th style="background:#fff;color:#242424;font-weight:bold;font-size:18px;text-align:center;padding-bottom:100px" colspan="6">Visa Direction</th>
        </tr>


<?php } } ?>





        

</table>

</div>

</div>
<?php 
echo "<div style='display:block;border:0px solid black;width:1220px;margin:20px auto'>";


echo "<p style='display:inline-block;width:300px'>";
echo "<span style='width:1230px;margin:auto'>* NB Entrées : <strong style='color:#00BA84'>".$counte."</strong></span> </br>";
echo "<span style='width:1230px;margin:auto'>* Total Entrées : <strong style='color:#00BA84'>".number_format($totale, 2, ',', ' ')." DH</strong></span>";
echo "</p>";


echo "<p style='display:inline-block;width:300px'>";
echo "<span style='width:1230px;margin:auto'>* NB Sorties : <strong style='color:#00BA84'>".$counts."</strong></span> </br>";
echo "<span style='width:1230px;margin:auto'>* Total Sorties : <strong style='color:#00BA84'>".number_format($totals, 2, ',', ' ')." DH</strong></span>";
echo "</p>";


echo "</div>";









?> 





<div style="width:1230px;margin:auto;margin-top:10px">

<?php
if($user_detail['exportexcel']=='oui')
{
?>
<a href="#" style="padding:5px 10px;display:inline-block;margin-right:20px" id="export" onclick="tableToExcel('testTable', 'W3C Example Table')">Exporter en excel-csv ..</a>
<?php
}
?>

<a href="#" style="padding:5px 10px;display:inline-block" id="export" onclick="Printer.print(document.getElementById('tableprint').innerHTML);">Imprimer</a>

<script type="text/javascript">





   var Printer=new Object();
    Printer.print=function (HTML) { 
        var win = window.open(location,null,null)
        win.blur(); window.focus(); 
        win.document.title="Impression journal de caisse";
        win.document.write("<html><head><style> td{border:1px solid #bbb;padding:2px 3px};</style><title>"+document.title+"</title></head><body style='margin-top:50px'><center><h1>Journal de caisse</h1>"+ HTML +"</center></body></html>")
        win.print();
        win.close();

    }
    window.top.Printer=Printer;


</script>
</div>

<!-- Pagination PHP ------------------------------------------------------------------------------------------>




        <?php if ( !isset($_POST['filtred']) ) {?>
                        
                <ul id="nav">
                    <?php


                            $societe="";

                            if($user_detail['ste']<>"")
                            {
                              $societe="  idsuccursale in(".$steuser.")";
                            } 
                            else
                            {
                              $societe="  idsuccursale in(-1)";
                            }

                          $req = $bdd->query("SELECT COUNT(*) AS nb FROM caisse where  deleted='non' and   ".$societe );

                        

                        $m = 'index.php?nb='.$nb.'&amp;';
                        

                        $row1 = $req->fetch();
                        if($row1['nb']!=0){
                            $nbpage=ceil($row1['nb']/$nb);
                            $fp=0;

                            if(isset($_GET['sf'])){
                                if($_GET['sf']>0){
                                    ?>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=0"><<</a></li>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=<?php echo $_GET['sf']-1;?>"><</a></li>
                                    <?php
                                }
                                if($_GET['sf']>=$nbpage){
                                    $fp=$_GET['sf']-2;              
                                }
                            }
                            for($i=$fp;$i<$nbpage+$fp;$i++){
                                ?>
                                <li ><a <?php if(isset($_GET['sf'])){if($_GET['sf']==$i){echo "class='active'";}}elseif($i==0){echo "class='active'";}?> href="<?php echo $m;?>sf=<?php echo $i;?>"><?php echo $i+1;?></a></li>
                                <?php
                            }
                            if(isset($_GET['sf'])){
                                if($_GET['sf']<$nbpage-1 AND $nbpage!=1){
                                    ?>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=<?php echo $_GET['sf']+1;?>"> > </a></li>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=<?php echo $nbpage-1;?>.html"> >> </a></li>     
                                    <?php
                                }
                            }
                            elseif($nbpage!=1){
                                ?>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=<?php echo 1;?>"> > </a></li>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=<?php echo $nbpage-1;?>"> >> </a></li>              
                                <?php               
                            }
                        }
                    ?>
                    </ul>  

            <?php }?>






<!------------------------------popup  detail Affichage ------------------------------>
<div id="colonnecaisse" class="modalDialog" >

  <div class="popup" style="width:400px" >
 
  <?php

    $reqcolonne = $bdd->query('SELECT colonnecaisse FROM users where id='.$_SESSION['idcaisse']);
    $colonnecaisse_detail = $reqcolonne->fetch();
    $reqcolonne->closecursor(); 

  ?>

  <form method="post" id="colonnecaisse" action=""> 
  
    <h1>Personnaliser l'affichage des colonnes...</h1>
    </br>
    <div class="cont">
      <table style="width:100%">

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'caisse') !== false) echo "checked"; ?> type="checkbox" id="caisse" name="caisse">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Caisse </td>
        </tr>



        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'caissier') !== false) echo "checked"; ?> type="checkbox" id="caissier" name="caissier">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Caissier </td>
        </tr>


        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'reference') !== false) echo "checked"; ?>  type="checkbox" id="reference"  name="reference">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Numéro </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'dateop') !== false) echo "checked"; ?>  type="checkbox" id="dateop" name="dateop">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Date opération </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'libelle') !== false) echo "checked"; ?>  type="checkbox" id="libelle" name="libelle">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Libellé </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'nature_entree') !== false) echo "checked"; ?>  type="checkbox" id="nature_entree" name="nature_entree">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Nature entrée </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'type_alimentation') !== false) echo "checked"; ?>  type="checkbox" id="type_alimentation" name="type_alimentation">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Type alimentation </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'famille') !== false) echo "checked"; ?>  type="checkbox" id="famille" name="famille">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Famille sortie </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'client') !== false) echo "checked"; ?>  type="checkbox" id="client" name="client">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Client </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'beneficiaire') !== false) echo "checked"; ?>  type="checkbox" id="beneficiaire" name="beneficiaire">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Bénéficiaire </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'notes') !== false) echo "checked"; ?>  type="checkbox" id="notes" name="notes">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Notes </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'acomptabilise') !== false) echo "checked"; ?>  type="checkbox" id="acomptabilise" name="acomptabilise">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> à compta. </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnecaisse_detail['colonnecaisse'], 'justifie') !== false) echo "checked"; ?>  type="checkbox" id="justifie" name="justifie" ></td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Justifiée </td>
        </tr>

      </table>

        </br>
      <p>
        <input type="submit" name="enregistrer" id="enregistrer" value="Enregistrer" style="width:200px;padding:10px 2px;font-size:18px;position:relative;top:-3px;margin-right:4px"/>
        </p>
      </br>

    </div>

      <a href="#" id="x" title="quitter"><i class="fa fa-times" aria-hidden="true"></i></a>

     </form> 
  </div>

</div>






<br/><br/>



<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="script-autocomplete.js"></script>


<script>

function nombre(id)
    {
    var source = document.getElementById(id); //On récupère la balise
    var value = source.value; //ici, sa valeur
    for (var i=0; i<value.length; i++)
    {
      if(value.charCodeAt(i)<=57 && value.charCodeAt(i)>=48 || (value.charCodeAt(i)==46 || value.charCodeAt(i)==43 || value.charCodeAt(i)==45))
        source.value=value;
        else
        source.value="";
    }

    }


    function apost(id)
    {
    var source = document.getElementById(id); //On récupère la balise
    var value = source.value; //ici, sa valeur
    for (var i=0; i<value.length; i++)
    {
      if(value.charCodeAt(i)==39)
        source.value="";
        else
        source.value=value;        
    }

    }
    

</script>

</body>
</html>