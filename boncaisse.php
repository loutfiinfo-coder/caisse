<?php 
include("config.php");


if(isset($_SESSION['idcaisse']))
{

$user = $bdd->query('SELECT id,pages,login,ste,modification,supression,justification,editdocument from users where id='.$_SESSION['idcaisse']);
$user_detail = $user ->fetch();
$user->closecursor();


if( !stristr($user_detail['pages'], 'sortie')) { header('location:conn.php'); }

}
else
{
header('location:conn.php'); 
}







if(isset($_POST['enregistrer'])) 
{

    $reqm = $bdd->prepare('UPDATE caisse  set  miseadisposition=:miseadisposition where id=:id ');
    $reqm->execute(array('miseadisposition' => $_POST['miseadisposition'],'id' => $_GET['id'])); 
}



?>








<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Impression Bon de caisse</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" type="image/ico" href="logo.ico" />
  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
<script type="text/javascript" src="script_money.js"></script>



<script type="text/javascript" src="widgEditor_1.0.1/scripts/widgEditor.js"></script>

<style type="text/css" media="all">
    @import "widgEditor_1.0.1/css/widgEditor.css";

    #lettreWidgToolbar
    {
        display:none;
    }



    .widgIframe 
    {
    white-space: pre-line;
    font-size: 16px;
    white-space: pre-wrap;
    width: 700PX;
    resize: none;
    height: 900px;
    font-family: 'TimesNewRoman';
    margin:auto;
    display:block;
    border:0px;


    }

</style>


<style type="text/css" media="print">
.btncache
{
display:none;
}

</style>





</head>


<body>



<script type="text/javascript">
function imprimer_page(){
  window.print();
}
</script>




<?php 

  $reqcaisse = $bdd->query('SELECT * from caisse where id='.$_GET["id"]);
  $caisse = $reqcaisse->fetch();
  $reqcaisse->closecursor();



  $reqfamille = $bdd->query('SELECT famille from famille where id='.$caisse["idfamille"]);
  $famille = $reqfamille->fetch();
  $reqfamille->closecursor();



  $reqfamillevente = $bdd->query('SELECT famille from famillevente where id='.$caisse["idfamillevente"]);
  $famillevente = $reqfamillevente->fetch();
  $reqfamillevente->closecursor();



  $reqsuccursale = $bdd->query('SELECT succursale,logo from succursale where id='.$caisse["idsuccursale"]);
  $succursale = $reqsuccursale->fetch();
  $reqsuccursale->closecursor();


function changedate($var)
{
$tab = explode("-",$var);
$nouvelledate = $tab[2]."/".$tab[1]."/".$tab[0];
return $nouvelledate;
}


?>



<form action="" method="post">

<?php
if($caisse['type_alimentation']!="5")
{
?>
  <div class="btncache"  style="position:absolute;top:10px;">
      <table>
        <tr>
          <td style="border:1px dashed #bbb;padding:2px" >
            <input type="checkbox" id="headerp" name="headerp" style="cursor:pointer" onclick="headerpf()" >
          </td> 
          <td style="font-size:20px;text-align:left;padding-left:10px;border:1px dashed #bbb;padding:2px 5px;" for="ok" >Impression sur Papier en-tête </td>
        </tr>
      </table>
  </div>
<?php
}
?>


  <input class="btncache" style="position:absolute;top:60px;" id="impression" name="impression" type="button" onclick="imprimer_page()" value="Imprimer " />






<script>

function headerpf()
{
    if(document.getElementById("headerp").checked == true)
      document.getElementById("boncaisse").style.marginTop = "250px";
    else
      document.getElementById("boncaisse").style.marginTop = "0px";
}

</script>











































<?php
if($caisse['type_alimentation']!="5")
{
?>

<div id="boncaisse" style="width:700PX ;height:520PX; border: 0px dashed #eee;margin:auto; font-size:18px;padding:5px 10px;border-radius:5px">


<?php 
if($succursale['logo']<>"")
{
?>
  <div style="display:inline-block;">
    <img src="logo_caisse/<?php echo $succursale['logo'];  ?>" style="max-width:150px;max-height:100px" />
  </div>

  <div style="display:inline-block;float:right">

    <p style="display:block;" >
      Caisse : <span style="font-weight: bold;" > <?php echo $succursale['succursale'];  ?> </span>
    </p>
    
    <p style="display:block;" >
      Date : <span style="font-weight: bold;" > <?php echo changedate($caisse['dateop']); ?> </span>
    </p>
  
  </div>


<?php 
}
else
{
?>

  <div>

    <p style="display:inline-block;" >
      Caisse : <span style="font-weight: bold;" > <?php echo $succursale['succursale'];  ?> </span>
    </p>
    
    <p style="display:inline-block;float:right" >
      Date : <span style="font-weight: bold;" > <?php echo changedate($caisse['dateop']); ?> </span>
    </p>
  
  </div>

<?php 
}
?>


  <br>

  <div style="text-align:center" >

    <?php if($caisse['type']=="entree") { ?>
      <h2 style="text-decoration:underline;font-size: 22px" >
        BON D'ENTRÉE CAISSE : <span style="font-weight: bold;" > <?php echo $caisse['ref']; ?> </span>
      </h2>
    <?php } elseif($caisse['type']=="sortie") { ?>
      <h2 style="text-decoration:underline;font-size: 22px" >
        ORDRE DE DÉPENSE CAISSE : <span style="font-weight: bold;" > <?php echo $caisse['ref']; ?> </span>
      </h2>
    <?php } ?>

  </div>



  <div style="text-align:right;margin-top:50px">

    <p>
      Montant total : <span style="font-weight: bold;border:2px solid black;padding:5px 30px" > # <?php echo  number_format($caisse['montant'], 2, ',', '.');  ?> Dhs # </span>
    </p>
  
  </div>





  <div style="margin-top:30px" >

    <p>
      Montant en lettres :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <script> 
        document.write("<span style='font-weight: bold;'  style='text-transform: capitalize;'># "+ConvNumberLetter_fr('<?php echo  number_format($caisse['montant'], 2, '.', ''); ?>','.')+" #</span>");
      </script>
    </p>
  
  </div>





  <div>

    <?php 

    /*$reqfamille = $bdd->query('SELECT famille from famille where id='.$caisse["idfamille"]);
    $famille = $reqfamille->fetch();
    $reqfamille->closecursor();

    if($caisse['type']=="sortie") { 
      ?>
      <p  >
        Famille : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" > <?php echo $famille['famille']; ?> </span>
      </p>
    <?php }*/ ?>

  </div>



  <div>

    <p>
       Libellé : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" > <?php echo $caisse['libelle']; ?> </span>
    </p>

  </div>




  <div>

    <?php if($caisse['type']=="entree") { ?>
     

        <?php if($caisse['type_alimentation']==0) { ?>
           <p >Client : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" > <?php echo $caisse['client']; ?>  </span></p>

           <p >Famille  : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" > <?php if(isset($famillevente['famille'])) echo $famillevente['famille']; ?>  </span></p>

        <?php } else { 
              $type_alimentation = $bdd->query("SELECT type FROM type_alimentation_caisse WHERE id=".$caisse['type_alimentation']);
              $type_alimentation_detail = $type_alimentation->fetch();
          ?>
          Alimentation de caisse : <span style="font-weight: bold;" > <?php echo $type_alimentation_detail['type']; ?>  </span>
        <?php } ?>

      
    <?php } elseif($caisse['type']=="sortie") { ?>
      <p  >
        Bénéficiaire : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" > <?php echo $caisse['fournisseur']; ?> </span>
      </p>
    <?php } ?>

  </div>









  <div>


    <?php if($caisse['concerne']!="") { ?>
    <p >
      Notes :<span style="white-space: pre-wrap; display: block;font-weight: bold;" ><?php echo $caisse['concerne']; ?></span>
    </p>
    <?php } else { ?>
    <p >
      Notes :<span style="white-space: pre-wrap; display: block;font-weight: bold;" ><?php echo $caisse['concerne']; ?></span>
    </p>
    <?php }?>




  </div>



  </br>
    <?php if($caisse['type']=="entree") { ?>
      <table style="border:0px solid black;width:100%">
        <tr>
          <th >Caissier(e): <?php  echo $caisse['caissier']; ?></th>
          <?php if($caisse['typeentree']=="vente") { ?>
            <th >Client</th>
          <?php } ?>
          <th >Direction</th>
        </tr>
      </table>
    <?php } elseif($caisse['type']=="sortie") { ?>
      <table style="border:0px solid black;width:100%">
        <tr>
          <th >Caissier(e): <?php  echo $caisse['caissier']; ?></th>
          <th >Bénéficiaire</th>
          <th >Direction</th>
          <th >PDG</th>
        </tr>
      </table>
    <?php } ?>



</div>

<?php
}
else
{

?>

<div style="width:700PX ;height:1090PX; border: 0px dashed #eee;margin:auto; font-size:18px;padding:5px 10px;border-radius:5px">

<?php


//text area lettre
$reqlettre = $bdd->query('SELECT miseadisposition from miseadisposition');
$lettre = $reqlettre ->fetch();
$reqlettre->closecursor();

$lettre_displaye=$lettre["miseadisposition"];

$lettre_displaye=str_replace("[dateop]",changedate($caisse['dateop']),$lettre_displaye);

$lettre_displaye=str_replace("[numero]",$caisse['ref'],$lettre_displaye);

$lettre_displaye=str_replace("[libelle]",$caisse['libelle'],$lettre_displaye);




if($caisse['mad_agence']=="")
$lettre_displaye=str_replace("[mad_agence]",'......................',$lettre_displaye);
else
$lettre_displaye=str_replace("[mad_agence]",$caisse['mad_agence'],$lettre_displaye);



if($caisse['mad_compte']=="")
$lettre_displaye=str_replace("[mad_compte]",'......................',$lettre_displaye);
else
$lettre_displaye=str_replace("[mad_compte]",$caisse['mad_compte'],$lettre_displaye);



if($caisse['mad_beneficiaire']=="")
$lettre_displaye=str_replace("[mad_beneficiaire]",'......................',$lettre_displaye);
else
$lettre_displaye=str_replace("[mad_beneficiaire]",$caisse['mad_beneficiaire'],$lettre_displaye);



if($caisse['mad_cin']=="")
$lettre_displaye=str_replace("[mad_cin]",'......................',$lettre_displaye);
else
$lettre_displaye=str_replace("[mad_cin]",$caisse['mad_cin'],$lettre_displaye);








if($caisse['montant']<>'') 
{
$lettre_displaye=str_replace("[montant]",number_format($caisse['montant'], 2, ',', '.'),$lettre_displaye);
}
else
{
$lettre_displaye=str_replace("[montant]","",$lettre_displaye);
}


echo "<textarea  class='widgEditor nothing' name='miseadisposition' id='lettre' >".$lettre_displaye."</textarea>";
?>

<script>
  var str = document.getElementById("lettre").innerHTML; 
  var res = str.replace("[montant_en_lettre]", ConvNumberLetter_fr('<?php echo  number_format($caisse['montant'], 2, '.', ''); ?>','.') );
  document.getElementById("lettre").innerHTML = res;
</script>


</div>

<?php
}
?>




</form>


</body>
</html>