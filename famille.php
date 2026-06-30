<?php
include("config.php");




if(isset($_SESSION['idcaisse']))
{

$user = $bdd->query('SELECT pages,login from users  where id='.$_SESSION['idcaisse']);
$user_detail = $user ->fetch();
$user->closecursor();


if( !stristr($user_detail['pages'], 'famille')) { header('location:conn.php'); }

}else
{
header('location:conn.php');
}





if(isset($_POST['add']))
{


    $famille11 = $bdd->query("SELECT famille FROM famille WHERE famille='".trim($_POST['famille'])."'");
    $famille11_detail = $famille11->fetch();
    $famille11->closecursor(); 


    if(strtolower(trim($_POST['famille']))==strtolower(trim($famille11_detail['famille']))) 
    {
    $familleid='1' ;
    }
    else
    {

    $req = $bdd->prepare('INSERT INTO famille(famille) VALUES(:famille)');
    $req->execute(array('famille' => $_POST['famille']  ));

    header('location:famille.php#');    

    }

}




if(isset($_POST['modif']))
{


    $famille11 = $bdd->query("SELECT count(famille) as nbfamille FROM famille WHERE famille='".trim($_POST['famillem'])."' and id<>".$_GET['famille']."");
    $famille11_detail = $famille11->fetch();
    $famille11->closecursor();


    if($famille11_detail['nbfamille']!=0) 
    {
    $familleid='1' ;
    }
    else
    {
      $req = $bdd->prepare('UPDATE famille set famille=:famille where id=:id');
      $req->execute(array('famille' => $_POST['famillem'],'id' => $_GET['famille']));

      header('location:famille.php#'); 
    } 
}











if(isset($_POST['addvente']))
{


    $famille11 = $bdd->query("SELECT famille FROM famillevente WHERE famille='".trim($_POST['famille'])."'");
    $famille11_detail = $famille11->fetch();
    $famille11->closecursor(); 


    if(strtolower(trim($_POST['famille']))==strtolower(trim($famille11_detail['famille']))) 
    {
    $familleid='1' ;
    }
    else
    {

    $req = $bdd->prepare('INSERT INTO famillevente(famille) VALUES(:famille)');
    $req->execute(array('famille' => $_POST['famille']  ));

    header('location:famille.php#');    

    }

}




if(isset($_POST['modifvente']))
{


    $famille11 = $bdd->query("SELECT count(famille) as nbfamille FROM famillevente WHERE famille='".trim($_POST['famillem'])."' and id<>".$_GET['famille']."");
    $famille11_detail = $famille11->fetch();
    $famille11->closecursor();


    if($famille11_detail['nbfamille']!=0) 
    {
    $familleid='1' ;
    }
    else
    {
      $req = $bdd->prepare('UPDATE famillevente set famille=:famille where id=:id');
      $req->execute(array('famille' => $_POST['famillem'],'id' => $_GET['famille']));

      header('location:famille.php#'); 
    } 
}







if(isset($_POST['modiftype']))
{

    $type11 = $bdd->query("SELECT count(type) as nbtype FROM type_alimentation_caisse WHERE type='".trim($_POST['typem'])."' and id<>".$_GET['type']."");
    $type11_detail = $type11->fetch();
    $type11->closecursor();


    if($type11_detail['nbtype']!=0) 
    {
    $typeid='1' ;
    }
    else
    {
      $req = $bdd->prepare('UPDATE type_alimentation_caisse set type=:type where id=:id');
      $req->execute(array('type' => $_POST['typem'],'id' => $_GET['type']));

      header('location:famille.php#'); 
    } 

}





if(isset($_POST['addtype']))
{


    $type11 = $bdd->query("SELECT type FROM type_alimentation_caisse WHERE type='".trim($_POST['type_alimentation'])."'");
    $type11_detail = $type11->fetch();
    $type11->closecursor(); 


    if(strtolower(trim($_POST['type_alimentation']))==strtolower(trim($type11_detail['type']))) 
    {
    $type_alimentationid='1' ;
    }
    else
    {


    $req = $bdd->prepare('INSERT INTO type_alimentation_caisse(type) VALUES(:type)');
    $req->execute(array('type' => $_POST['type_alimentation']  ));

    header('location:famille.php#');    


    }

}



if(isset($_GET['sup']))
{
 $req = $bdd->prepare('DELETE FROM famille where id=:iddelet');
 $req->execute(array('iddelet' => $_GET['iddelet']));

 header('location:famille.php#');
}


if(isset($_GET['supvente']))
{
 $req = $bdd->prepare('DELETE FROM famillevente where id=:iddelet');
 $req->execute(array('iddelet' => $_GET['iddelet']));

 header('location:famille.php#');
}

if(isset($_GET['supa']))
{
 $req = $bdd->prepare('DELETE FROM type_alimentation_caisse where id=:iddelet');
 $req->execute(array('iddelet' => $_GET['iddelet']));

 header('location:famille.php#');
}




?>


<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Familles & Types</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" type="image/ico" href="logo.ico" />
  <link rel="stylesheet" href="ctm.css" />
  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    
</head>
<body onload="defaut()">

<div class="head"  >


<img src="logo.png" style="margin:2px 120px  3px 0px;" >


<a class="menu" href="index.php"  ><i  class="fa fa-history" ></i> Journal de caisse </a>
<a class="menu" href="sortie.php" ><i class="fa fa-arrow-up" aria-hidden="true"></i> Sorties</a>
<a class="menu" href="entree.php" ><i class="fa fa-arrow-down" aria-hidden="true"></i> Entrées</a>

<a class="menu"  href="#" style="background:url(crossword.png) ;color:#00BA84;border-bottom:3px solid #00BA84;"  > <i class="fa fa-th-large" aria-hidden="true"></i> Familles & Types</a>
<a class="menu"   href="societe.php" ><i  class="fa fa-archive" ></i> Caisses </a>

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


<a class="menu" href="decon.php" style="color:#00BA84"><i class="fa fa-power-off"></i></a>

</div>




<div style="width:1240px;margin:auto;color:#444;font-size:20px;margin-top:-20px;margin-bottom:10px;font-family: 'Oswald',serif;">Bienvenu: <strong style="color:#00BA84;margin-left:5px;text-transform : capitalize;"><?php echo $user_detail['login']; ?></strong></div>


























<div style="width:1220px;text-align:center;margin:30px auto" >






<div style="width:600px;display:inline-block;text-align:center;" >

<div>

<h1 style="font-family: 'Oswald',serif;color:#424242;color:#00BA84;" ><u>Familles Sorties</u></h1>
</br>

<div style="width:300px;display:inline-block" >
<a id="sarr" href="#ste" ><i class="fa fa-pencil-square-o"></i> Ajouter une famille sortie </a>
</div>

<div style="width:80px;display:inline-block" >
<a id="sarr" href="famille.php"  style="margin-left:10px"><i class="fa fa-refresh" ></i></a>
</div>

</div>

<table style="width:500px;border:1px solid black" >
        <tr>

                <th >famille sortie</th>

                <th >Modifier</th>

                <th >Supprimer</th>

        </tr>


<?php

$req = $bdd->query("SELECT * from famille order by id desc ");

while ($famille = $req->fetch())
{

?>       
        <tr>
                <td ><?php echo $famille['famille'] ?></td>


       
                <td>
                    <a onclick="location.href='?famille=<?php echo $famille['id'] ?>#modiffamille';" id="valactive" style="cursor:pointer" ><i class="fa fa-pencil-square-o"></i> Modifier</a>
                </td>

                <?php
                  $reqste = $bdd->query('SELECT count(id) as nbfamille from caisse where idfamille='.$famille['id']);
                  $idste = $reqste ->fetch();
                  $reqste->closecursor();
                ?>


                <?php if($famille['famille']!="Transfert entre caisses" ){ ?>
                  <?php if( $idste['nbfamille']!=0 ){ ?>
                    <td style="color:#888"><i class="fa fa-times"></i> Supprimer</td>   
                    <?php }else { ?>
                    <td><a href="#<?php echo $famille['id']; ?>" id="supactive" onclick="document.getElementById('<?php echo $famille['id']; ?>c').style.display='block';" ><i class="fa fa-times"></i> Supprimer</a>
                                            <div class="del" id="<?php echo $famille['id']; ?>c" style="width:180px">  
                                                <a href="#" onclick="location.href='famille.php?iddelet=<?php echo $famille['id']; ?>&amp;sup=ok';"  style="display:inline;" >Oui</a>&nbsp;&nbsp;-&nbsp;
                                                <a href="#" onclick="document.getElementById('<?php echo $famille['id']; ?>c').style.display='none';" style="display:inline;" >Non</a>
                                            </div>
                    </td>
                  <?php }?>
                <?php } else echo "<td style='color:#888'><i class='fa fa-times'></i> Supprimer</td>";?>

        </tr>
<?php 
}
$req->closecursor();
?> 
</table>

</div>









































<div style="width:600px;display:inline-block;text-align:center;" >

<div>

<h1 style="font-family: 'Oswald',serif;color:#424242;color:#00BA84;" ><u>Familles ventes</u></h1>
</br>

<div style="width:300px;display:inline-block" >
<a id="sarr" href="#stevente" ><i class="fa fa-pencil-square-o"></i> Ajouter une famille vente </a>
</div>

<div style="width:80px;display:inline-block" >
<a id="sarr" href="famille.php"  style="margin-left:10px"><i class="fa fa-refresh" ></i></a>
</div>

</div>

<table style="width:500px;border:1px solid black" >
        <tr>

                <th >famille vente</th>

                <th >Modifier</th>

                <th >Supprimer</th>

        </tr>


<?php

$req = $bdd->query("SELECT * from famillevente order by id desc ");

while ($famille = $req->fetch())
{

?>       
        <tr>
                <td ><?php echo $famille['famille'] ?></td>


       
                <td>
                    <a onclick="location.href='?famille=<?php echo $famille['id'] ?>#modiffamillevente';" id="valactive" style="cursor:pointer" ><i class="fa fa-pencil-square-o"></i> Modifier</a>
                </td>

                <?php
                  $reqste = $bdd->query('SELECT count(id) as nbfamille from caisse where idfamillevente='.$famille['id']);
                  $idste = $reqste ->fetch();
                  $reqste->closecursor();
                ?>


                <?php if($famille['famille']!="Transfert entre caisses" ){ ?>
                  <?php if( $idste['nbfamille']!=0 ){ ?>
                    <td style="color:#888"><i class="fa fa-times"></i> Supprimer</td>   
                    <?php }else { ?>
                    <td><a href="#<?php echo $famille['id']; ?>" id="supactive" onclick="document.getElementById('<?php echo $famille['id']; ?>c').style.display='block';" ><i class="fa fa-times"></i> Supprimer</a>
                                            <div class="del" id="<?php echo $famille['id']; ?>c" style="width:180px">  
                                                <a href="#" onclick="location.href='famille.php?iddelet=<?php echo $famille['id']; ?>&amp;supvente=ok';"  style="display:inline;" >Oui</a>&nbsp;&nbsp;-&nbsp;
                                                <a href="#" onclick="document.getElementById('<?php echo $famille['id']; ?>c').style.display='none';" style="display:inline;" >Non</a>
                                            </div>
                    </td>
                  <?php }?>
                <?php } else echo "<td style='color:#888'><i class='fa fa-times'></i> Supprimer</td>";?>

        </tr>
<?php 
}
$req->closecursor();
?> 
</table>

</div>


























<div style="width:600px;display:inline-block;text-align:center" >

<div  >

<h1 style="font-family: 'Oswald',serif;color:#424242;color:#00BA84;" ><u>Types d'Alimentation Caisse</u></h1>
</br>

<div style="width:400px;display:inline-block" >
<a id="sarr" href="#type" ><i class="fa fa-pencil-square-o"></i> Ajouter un type d'alimentation caisse </a>
</div>

<div style="width:80px;display:inline-block" >
<a id="sarr" href="famille.php"  style="margin-left:10px"><i class="fa fa-refresh" ></i></a>
</div>

</div>

<table style="width:500px;border:1px solid black" >
        <tr>

                <th >Type alimentation caisse</th>

                <th >Modifier</th>

                <th >Supprimer</th>

        </tr>


<?php

$reqa = $bdd->query("SELECT * from type_alimentation_caisse order by id desc ");

while ($type_alimentation_caisse = $reqa->fetch())
{

?>       
        <tr>
                <td ><?php echo $type_alimentation_caisse['type'] ?></td>


                <?php if($type_alimentation_caisse['type']!="Mise à disposition" and $type_alimentation_caisse['type']!="Transfert entre caisses" ){ ?>

                <td>
                    <a onclick="location.href='?type=<?php echo $type_alimentation_caisse['id'] ?>#modiftype';" id="valactive" style="cursor:pointer" ><i class="fa fa-pencil-square-o"></i> Modifier</a> 
                </td>

                <?php } else echo "<td style='color:#888' <i class='fa fa-pencil-square-o'></i> Modifier</td>";?>

                <?php
                  $reqste = $bdd->query("SELECT count(id) as nbcaisse from caisse where type_alimentation=".$type_alimentation_caisse['id']);
                  $idste = $reqste ->fetch();
                  $reqste->closecursor();
                ?>


                <?php if($type_alimentation_caisse['type']!="Mise à disposition" and $type_alimentation_caisse['type']!="Transfert entre caisses" ){ ?>

                <?php if( $idste['nbcaisse']!=0 ){ ?>
                <td style="color:#888"><i class="fa fa-times"></i> Supprimer</td>   
                <?php }else { ?>
                <td><a href="#<?php echo $type_alimentation_caisse['id']; ?>" id="supactive" onclick="document.getElementById('<?php echo $type_alimentation_caisse['id']; ?>ca').style.display='block';" ><i class="fa fa-times"></i> Supprimer</a>
                                        <div class="del" id="<?php echo $type_alimentation_caisse['id']; ?>ca" style="width:180px">  
                                            <a href="#" onclick="location.href='famille.php?iddelet=<?php echo $type_alimentation_caisse['id']; ?>&amp;supa=ok';"  style="display:inline;" >Oui</a>&nbsp;&nbsp;-&nbsp;
                                            <a href="#" onclick="document.getElementById('<?php echo $type_alimentation_caisse['id']; ?>ca').style.display='none';" style="display:inline;" >Non</a>
                                        </div>
                </td>
                <?php }?>
                <?php } else echo "<td style='color:#888'><i class='fa fa-times'></i> Supprimer</td>";?>
        </tr>

<?php 
}
$reqa->closecursor();
?> 
</table>


</div>

</div>














<!--  -------------------------------------- Pagination PHP --------------------------------------------------   -->
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="script-autocomplete.js"></script>







<script> $( document ).ready(function() {document.getElementById("error").style.display="none"});</script>

<!------------------------------popup ajout carnet------------------------------>

<div id="ste" class="modalDialog">

  <div class="popup">
  
  <h1>Ajouter une famille sortie</h1>

   <form action="" method="post">
      <div class="cont">


        <fieldset>
          <legend>famille sortie:</legend>
          <p><input  required type="text"  id="famille" name="famille" autocomplete="off" onkeyup="apost('famille')" ></p>
        </fieldset>


        <p><input type="submit" name="add" value="Ajouter"></p>
      </div>
    </form>


    <ul id="error">

        <?php
                                if(isset($_POST['add']))
                                {
                                    if($familleid=='1'){
                                        echo'<script> $( document ).ready(function() {document.getElementById("error").style.display="block"}); </script>';
                                        echo '<li style="color:#fb065c;font-weight:bold">La famille existe déjà</li>';
                                    }
                                        
                                }
        ?>

    </ul>

  <a href="#" id="x" title="quitter">x</a>
  </div>
       
    
</div>






<script> $( document ).ready(function() {document.getElementById("error1").style.display="none"});</script>

<!------------------------------popup ajout carnet------------------------------>

<div id="stevente" class="modalDialog">

  <div class="popup">
  
  <h1>Ajouter une famille vente</h1>

   <form action="" method="post">
      <div class="cont">


        <fieldset>
          <legend>famille vente:</legend>
          <p><input  required type="text"  id="famille" name="famille" autocomplete="off" onkeyup="apost('famille')" ></p>
        </fieldset>


        <p><input type="submit" name="addvente" value="Ajouter"></p>
      </div>
    </form>


    <ul id="error1">

        <?php
                                if(isset($_POST['addvente']))
                                {
                                    if($familleid=='1'){
                                        echo'<script> $( document ).ready(function() {document.getElementById("error1").style.display="block"}); </script>';
                                        echo '<li style="color:#fb065c;font-weight:bold">La famille existe déjà</li>';
                                    }
                                        
                                }
        ?>

    </ul>

  <a href="#" id="x" title="quitter">x</a>
  </div>
       
    
</div>






<script> $( document ).ready(function() {document.getElementById("error2").style.display="none"});</script>

<!------------------------------popup ajout carnet------------------------------>

<div id="type" class="modalDialog">

  <div class="popup">
  
  <h1>Ajouter un Type d'alimentation caisse</h1>

   <form action="" method="post">
      <div class="cont">


        <fieldset>
          <legend>Type d'alimentation caisse:</legend>
          <p><input  required type="text"  id="type_alimentation" name="type_alimentation" autocomplete="off" onkeyup="apost('type_alimentation')" ></p>
        </fieldset>


        <p><input type="submit" name="addtype" value="Ajouter"></p>
      </div>
    </form>


    <ul id="error2">

        <?php
                                if(isset($_POST['addtype']))
                                {
                                    if($type_alimentationid=='1'){
                                        echo'<script> $( document ).ready(function() {document.getElementById("error2").style.display="block"}); </script>';
                                        echo '<li style="color:#fb065c;font-weight:bold">Le type alimentation caisse existe déjà</li>';
                                    }
                                        
                                }
        ?>

    </ul>

  <a href="#" id="x" title="quitter">x</a>
  </div>
       
    
</div>











<script> $( document ).ready(function() {document.getElementById("error3").style.display="none"});</script>

<!------------------------------popup ajout soctété------------------------------>

<div id="modiffamille" class="modalDialog">

  <div class="popup">
  
  <h1>Modifier une famille</h1>

<?php
if(!isset($_GET["famille"])) $_GET["famille"]=0;
$req = $bdd->query('SELECT * from famille where id='.$_GET["famille"]);
$modf = $req->fetch();
$req->closecursor();
?>




   <form action="" method="post">
      <div class="cont">


        <fieldset>
          <legend>famille sortie:</legend>
          <p><input value="<?php echo $modf['famille'] ; ?>"  required type="text"  id="famillem" name="famillem" autocomplete="off" onkeyup="apost('famille')" ></p>
        </fieldset>


        <p><input type="submit" name="modif" value="Modifier"></p>
      </div>
    </form>


    <ul id="error3">

        <?php
                                if(isset($_POST['modif']))
                                {
                                    if($familleid=='1'){
                                        echo'<script> $( document ).ready(function() {document.getElementById("error3").style.display="block"}); </script>';
                                        echo '<li style="color:#fb065c;font-weight:bold">La famille existe déjà</li>';
                                    }
                                        
                                }
        ?>

    </ul>

  <a href="#" id="x" title="quitter">x</a>

  </div>
  
</div>








<script> $( document ).ready(function() {document.getElementById("error4").style.display="none"});</script>

<!------------------------------popup ajout soctété------------------------------>

<div id="modiftype" class="modalDialog">

  <div class="popup">
  
  <h1>Modifier un type</h1>

<?php
if(!isset($_GET["type"])) $_GET["type"]=0;
$req = $bdd->query('SELECT * from type_alimentation_caisse where id='.$_GET["type"]);
$modf = $req->fetch();
$req->closecursor();
?>




   <form action="" method="post">
      <div class="cont">

        <fieldset>
          <legend>type alimentation caisse :</legend>
          <p><input value="<?php echo $modf['type'] ; ?>"  required type="text"  id="typem" name="typem" autocomplete="off" onkeyup="apost('typem')" ></p>
        </fieldset>

        <p><input type="submit" name="modiftype" value="Modifier"></p>
      </div>
    </form>


    <ul id="error4">

        <?php 
                                if(isset($_POST['modiftype']))
                                {
                                    if($typeid=='1'){
                                        echo'<script> $( document ).ready(function() {document.getElementById("error4").style.display="block"}); </script>';
                                        echo '<li style="color:#fb065c;font-weight:bold">Le type_alimentation_caisse existe déjà</li>';
                                    }
                                        
                                }
        ?> 

    </ul>

    <a href="#" id="x" title="quitter">x</a>

  </div>
  
</div>












<script> $( document ).ready(function() {document.getElementById("error5").style.display="none"});</script>

<!------------------------------popup ajout soctété------------------------------>

<div id="modiffamillevente" class="modalDialog">

  <div class="popup">
  
  <h1>Modifier une famille</h1>

<?php
if(!isset($_GET["famille"])) $_GET["famille"]=0;
$req = $bdd->query('SELECT * from famillevente where id='.$_GET["famille"]);
$modf = $req->fetch();
$req->closecursor();
?>




   <form action="" method="post">
      <div class="cont">


        <fieldset>
          <legend>famille vente:</legend>
          <p><input value="<?php echo $modf['famille'] ; ?>"  required type="text"  id="famillem" name="famillem" autocomplete="off" onkeyup="apost('famille')" ></p>
        </fieldset>


        <p><input type="submit" name="modifvente" value="Modifier"></p>
      </div>
    </form>


    <ul id="error5">

        <?php
                                if(isset($_POST['modifvente']))
                                {
                                    if($familleid=='1'){
                                        echo'<script> $( document ).ready(function() {document.getElementById("error5").style.display="block"}); </script>';
                                        echo '<li style="color:#fb065c;font-weight:bold">La famille existe déjà</li>';
                                    }
                                        
                                }
        ?>

    </ul>

  <a href="#" id="x" title="quitter">x</a>

  </div>
  
</div>








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


<br/>
</body>
</html>