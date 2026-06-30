<?php
include("config.php");




if(isset($_SESSION['idcaisse']))
{

$user = $bdd->query('SELECT pages,login,cloturercaisse,fermeturercaisse,transfertcaisse,affichersolde from users  where id='.$_SESSION['idcaisse']);
$user_detail = $user ->fetch();
$user->closecursor();

if( !stristr($user_detail['pages'], 'societe')) { header('location:conn.php'); }


}else
{
header('location:conn.php');
}




if(isset($_POST['add']))
{


    $succursale11 = $bdd->query("SELECT succursale FROM succursale WHERE succursale='".trim($_POST['succursale'])."'");
    $succursale11_detail = $succursale11->fetch();
    $succursale11->closecursor(); 


    if(strtolower(trim($_POST['succursale']))==strtolower(trim($succursale11_detail['succursale']))) 
    {
    $succursaleid='1' ;
    }
    else
    {

    $req = $bdd->prepare('INSERT INTO succursale(succursale,etat) VALUES(:succursale,:etat)');
    $req->execute(array('succursale' => $_POST['succursale'],'etat' => 'encours'   ));


      $reqidsecursal = $bdd->query("SELECT id from succursale order by id desc limit 1 ");
      $idsecursal = $reqidsecursal->fetch();


      $reqste = $bdd->query("SELECT ste from users  where login='admin' ");
      $ste11 = $reqste->fetch();
      $societe = $ste11['ste'].",".$idsecursal['id'];
      $societe=trim($societe,",");
      $reqste->closecursor();

      $reqm = $bdd->prepare('UPDATE users  set  ste=:ste where login=:login');
      $reqm->execute(array('login' =>'admin','ste' => $societe));


      header('location:societe.php#');    

    }


}



if(isset($_GET['sup']))
{

      $reqste = $bdd->query("SELECT id,ste from users");

      $societe = "";
      while ($ste11 = $reqste->fetch())
      {
        $societetable = explode(',',$ste11['ste']);
        foreach($societetable as $key => $sterow)
        {
          if($sterow==$_GET['iddelet'])
          {
             unset($societetable[$key]);
          }
        }

        $societe = implode(",",$societetable);
        $reqm = $bdd->prepare('UPDATE users  set  ste=:ste where id=:id');
        $reqm->execute(array('id' =>$ste11['id'],'ste' => $societe));

      }


 $reqlogo = $bdd->query("SELECT logo from succursale where id=".$_GET['iddelet']);
 $logo = $reqlogo->fetch();
 unlink("logo_caisse/".$logo['logo']."");

 
 $req = $bdd->prepare('DELETE FROM succursale where id=:iddelet');
 $req->execute(array('iddelet' => $_GET['iddelet']));





           $currentlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

           if(strpos($currentlink, '&iddelet') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&iddelet"));
           }
           else if(strpos($currentlink, '?iddelet') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"?iddelet"));
           }

           header('location:'.$currentlink.'#');

}






if(isset($_GET['etat']))
{

              // total especes entree all succursale
              $totalespeceall = $bdd->query("SELECT sum(montant) as totalespeceall from caisse  where  type='entree'  and idsuccursale=".$_GET['idcaisse']);
              $totalespecealldetail = $totalespeceall ->fetch();
              $totalespeceall->closecursor();


              // total sortie all succursale
              $totalsortieall = $bdd->query("SELECT sum(montant) as totalsortieall from caisse  where  type='sortie' and idsuccursale=".$_GET['idcaisse']);
              $totalsortieall_detail = $totalsortieall ->fetch();
              $totalsortieall->closecursor();


              $totalcaisse = $totalespecealldetail['totalespeceall'] - $totalsortieall_detail['totalsortieall'];


  if($totalcaisse==0)
  {

        $datecloture = date('Y-m-d H:i:s');

        $reqx = $bdd->prepare('UPDATE succursale  set  etat=:etat,datelastcloture=:datelastcloture where id=:idcaisse');

        $reqx->execute(array('etat' => $_GET['etat'],'datelastcloture' => $datecloture, 'idcaisse' => $_GET['idcaisse'] )); 
       
        $reqx->closecursor();


  }


           $currentlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


           if(strpos($currentlink, '&idcaisse') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&idcaisse"));
           }
           else if(strpos($currentlink, 'idcaisse') !== false)
           {
            $currentlink="societe.php";
           }

           header('location:'.$currentlink.'#');


}






if(isset($_POST['addtransfert']))
{


                // total especes entree par sucursale
                $totalespece = $bdd->query("SELECT sum(montant) as totalespece from caisse  where  type='entree' and deleted='non' and idsuccursale=".$_GET['idcaisse'] );
                $totalespecedetail = $totalespece ->fetch();
                $totalespece->closecursor();

                // total sortie par sucursale
                $totalsortie = $bdd->query("SELECT sum(montant) as totalsorti from caisse  where  type='sortie' and deleted='non'  and idsuccursale=".$_GET['idcaisse'] );
                $totalsortie_detail = $totalsortie ->fetch();
                $totalsortie->closecursor();

                $caissesuccursale = $totalespecedetail['totalespece'] - $totalsortie_detail['totalsorti'];

                $caissesuccursale =number_format((float)$caissesuccursale, 2, '.', '');

    if(number_format((float)$_POST['montant'], 2, '.', '') > $caissesuccursale) 
    {
    $verifycaissevalue='1' ;
    }
    else
    {





















    // operation de sortie

    $succursale = $bdd->query('SELECT succursale from succursale where id='.$_POST['succursale']);
    $succursale_detail = $succursale ->fetch();
    $succursale->closecursor();



    $famille = $bdd->query("SELECT id from famille where famille='Transfert entre caisses' ");
    $famille_detail = $famille ->fetch();
    $famille->closecursor();


    $req = $bdd->prepare('INSERT INTO caisse(type,idsuccursale,valide,dateop,fournisseur,montant,concerne,idfamille,libelle,caissier) VALUES(:type,:idsuccursale,:valide,:dateop,:fournisseur,:montant,:concerne,:idfamille,:libelle,:caissier)');

    $req->execute(array('type' => "sortie",'idsuccursale' => $_GET['idcaisse'],'valide' => "non",'dateop' => date("Y-m-d") ,'fournisseur' => 'Caisse: '.$succursale_detail['succursale'],'montant' => number_format((float)$_POST['montant'], 2, '.', ''),'concerne' => '','idfamille' => $famille_detail['id'],'libelle' => 'Transfert d\'espèces vers la caisse: '.$succursale_detail['succursale'] ,'caissier' => $user_detail['login'] ));


    $reqref = $bdd->query("SELECT id,type,dateop from caisse where deleted='non' order by id desc limit 1");
    $refdetai = $reqref ->fetch();
    $reqref->closecursor();


    $countcaissereq = $bdd->query("SELECT count(id) as nbcaisse from caisse where deleted='non' and YEAR(dateop)=YEAR('".$refdetai['dateop']."') and type='sortie' ");
    $countcaisse = $countcaissereq ->fetch();
    $countcaissereq->closecursor();


    function date_fr($format, $timestamp=false) {
        if ( !$timestamp ) $date_en = date($format);
        else               $date_en = date($format,$timestamp);

        $texte_en = array(
            "Monday", "Tuesday", "Wednesday", "Thursday",
            "Friday", "Saturday", "Sunday", "January",
            "February", "March", "April", "May",
            "June", "July", "August", "September",
            "October", "November", "December"
        );
        $texte_fr = array(
            "Lundi", "Mardi", "Mercredi", "Jeudi",
            "Vendredi", "Samedi", "Dimanche", "Janvier",
            "F&eacute;vrier", "Mars", "Avril", "Mai",
            "Juin", "Juillet", "Ao&ucirc;t", "Septembre",
            "Octobre", "Novembre", "D&eacute;cembre"
        );
        $date_fr = str_replace($texte_en, $texte_fr, $date_en);

        $texte_en = array(
            "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun",
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
            "Aug", "Sep", "Oct", "Nov", "Dec"
        );
        $texte_fr = array(
            "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim",
            "Jan", "F&eacute;v", "Mar", "Avr", "Mai", "Jui",
            "Jui", "Ao&ucirc;", "Sep", "Oct", "Nov", "D&eacute;c"
        );
        $date_fr = str_replace($texte_en, $texte_fr, $date_fr);

        return $date_fr;
    }



    function pad($num, $size) {
        $s = "0000000000" . $num;
        return substr($s,strlen($s)-$size);
    }


    if($countcaisse['nbcaisse']==0)
      $ref=strtoupper(($countcaisse['nbcaisse']+1)."/".date_fr("Y",strtotime($refdetai['dateop'])));
    else
      $ref=strtoupper($countcaisse['nbcaisse']."/".date_fr("Y",strtotime($refdetai['dateop'])));

    $ref="S-".pad($ref, 11);
    //$ref=strtoupper(substr($refdetai['type'],0,1).$refdetai['id']."-".date_fr('m',strtotime($refdetai['dateop'])).substr(date_fr("F",strtotime($refdetai['dateop'])),0,1).date_fr("d",strtotime($refdetai['dateop']))); old avec lettre et jour et mois

    $reqx = $bdd->prepare('UPDATE caisse  set  ref=:ref where id=:id');
    $reqx->execute(array('id' =>$refdetai['id'],'ref' => $ref)); 
    $reqx->closecursor();





















    $succursale = $bdd->query('SELECT succursale from succursale where id='.$_GET['idcaisse']);
    $succursale_detail = $succursale ->fetch();
    $succursale->closecursor();


    // operation d'entrée

    $req = $bdd->prepare('INSERT INTO caisse(type,typeentree,idsuccursale,dateop,montant,concerne,type_alimentation,libelle,caissier) VALUES(:type,:typeentree,:idsuccursale,:dateop,:montant,:concerne,:type_alimentation,:libelle,:caissier)');

    $req->execute(array('type' => "entree",'typeentree' => 'alimentation de caisse' ,'idsuccursale' => $_POST['succursale'],'dateop' => date("Y-m-d"),'montant' => number_format((float)$_POST['montant'], 2, '.', ''),'concerne' => '','type_alimentation' => 'Transfert entre caisses','libelle' => 'Réception d\'espèces de caisse: '.$succursale_detail['succursale'] ,'caissier' => $user_detail['login'] ));


    $reqref = $bdd->query("SELECT id,type,dateop,typeentree,type_alimentation from caisse where deleted='non' order by id desc limit 1");
    $refdetai = $reqref ->fetch();
    $reqref->closecursor();


    $countcaissereq = $bdd->query("SELECT count(id) as nbcaisse from caisse where deleted='non' and YEAR(dateop)=YEAR('".$refdetai['dateop']."') and type='entree' and type_alimentation<>'Mise à disposition' ");
    $countcaisse_e = $countcaissereq ->fetch();
    $countcaissereq->closecursor();


    $countcaissereq = $bdd->query("SELECT count(id) as nbcaisse from caisse where deleted='non' and YEAR(dateop)=YEAR('".$refdetai['dateop']."') and type='entree' and type_alimentation='Mise à disposition' ");
    $countcaisse_mad = $countcaissereq ->fetch();
    $countcaissereq->closecursor();



    if($refdetai['type_alimentation']!='Mise à disposition')
    {
      if($countcaisse_e['nbcaisse']==0)
        $ref=strtoupper(($countcaisse_e['nbcaisse']+1)."/".date_fr("Y",strtotime($refdetai['dateop'])));
      else
        $ref=strtoupper($countcaisse_e['nbcaisse']."/".date_fr("Y",strtotime($refdetai['dateop'])));
      
      $ref="E-".pad($ref, 11);
    }
    else
    {
      if($countcaisse_mad['nbcaisse']==0)
        $ref=strtoupper(($countcaisse_mad['nbcaisse']+1)."/".date_fr("Y",strtotime($refdetai['dateop'])));
      else
        $ref=strtoupper($countcaisse_mad['nbcaisse']."/".date_fr("Y",strtotime($refdetai['dateop'])));

      $ref="MAD-".pad($ref, 11);
    }


    //$ref=strtoupper(substr($refdetai['type'],0,1).$refdetai['id']."-".date_fr('m',strtotime($refdetai['dateop'])).substr(date_fr("F",strtotime($refdetai['dateop'])),0,1).date_fr("d",strtotime($refdetai['dateop']))); old avec lettre old avec lettre et jour et mois

    $reqx = $bdd->prepare('UPDATE caisse  set  ref=:ref where id=:id');
    $reqx->execute(array('id' =>$refdetai['id'],'ref' => $ref)); 
    $reqx->closecursor();
    




























           $currentlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


           if(strpos($currentlink, '&idcaisse') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&idcaisse"));
           }
           else if(strpos($currentlink, 'idcaisse') !== false)
           {
            $currentlink="societe.php";
           }

           header('location:'.$currentlink.'#');
  }

}







    if (isset($_POST['idcaisse']))
    {  

           header('location:google.com');

        if ($_FILES['img1']['error'] == 0)
        {

            include('reimg.php');
            $infosfichier = pathinfo($_FILES['img1']['name']);
            $extension_upload = $infosfichier['extension'];
            $_FILES['img1']['name']=$_POST['idcaisse'].'.'.$extension_upload;
            move_uploaded_file($_FILES['img1']['tmp_name'], 'logo_caisse/' . basename($_FILES['img1']['name']));


            $reqx = $bdd->prepare('UPDATE succursale  set  logo=:logo where id=:id');
            $reqx->execute(array('logo' =>$_FILES['img1']['name'],'id' => $_POST['idcaisse'])); 
            $reqx->closecursor();
            

           $currentlink=$_POST['currentlink'];

           if(strpos($currentlink, '&idc') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&idc"));
           }

           header('location:'.$currentlink.'#');



        }
    }




if(isset($_GET['iddocdelet']))
{


 $reqx = $bdd->prepare('UPDATE succursale  set  logo=:logo where id=:id');
 $reqx->execute(array('logo' =>'','id' => $_GET['iddocdelet'])); 
 $reqx->closecursor();
            

 unlink("logo_caisse/".$_GET['urldoc']."");

           $currentlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


           if(strpos($currentlink, '&idc') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&idc"));
           }
           else if(strpos($currentlink, 'idc') !== false)
           {
            $currentlink="societe.php";
           }

           header('location:'.$currentlink.'#');


}






if(isset($_POST['changedatelastcloture']))
{


          $reqx = $bdd->prepare('UPDATE succursale  set  datelastcloture=:datelastcloture where id=:id');
          $reqx->execute(array('datelastcloture' =>$_POST['datelastcloture'],'id' => $_GET['idcaisse'])); 
          $reqx->closecursor();



           $currentlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


           if(strpos($currentlink, '&idcaisse') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&idcaisse"));
           }
           else if(strpos($currentlink, 'idcaisse') !== false)
           {
            $currentlink="societe.php";
           }

           header('location:'.$currentlink.'#');

}









?>


<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Caisses</title>
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

<a class="menu"  href="famille.php"> <i class="fa fa-th-large" aria-hidden="true"></i> Familles & Types</a>
<a class="menu"  href="#" style="background:url(crossword.png) ;color:#00BA84;border-bottom:3px solid #00BA84;" ><i  class="fa fa-archive" ></i> Caisses </a>

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







    <form method="post" id="form_av" action=""> 

<div class="filtre" style="height:76px;padding:4px 10px 20px 0px;background:#fff">

  <ul class="nav">
    <div style="width:300px;display:inline-block;margin-right:313px;position:relative;top:-0px;" >
      <a id="sarr" style="padding:18px 2px" href="#ste" ><i class="fa fa-pencil-square-o"></i> Ajouter une Caisse </a>
    </div>
        
        <fieldset style="width:520px;text-align:center;margin:0px;padding-bottom:10px;display:inline-block;margin-right:2px;height:70px;">
        <legend  >Recherche par Caisse:</legend>
            <li id="filtrec1" style="display:inline-block" >
              <input  placeholder="Caisse..." style="padding:9px;width:280px" name="caisse" type="text"  id="country_caisse" onkeyup="autocompletcaisse();apost('country_caisse')"  autocomplete="off" <?php if(isset($_POST['caisse'])){echo ' value="'.$_POST["caisse"].'" ';}elseif(isset($_GET['caisse'])){echo ' value="'.$_GET["caisse"].'" ';}?> />
            </li> 
            <ul style="width:293px;color:#333;font-size:15px;margin-left:18px;" id="country_list_caisse"></ul>

            <input type="submit" name="filtred" id="filtred" value="Rechercher"  style="margin-left:10px;height:46px;width:165px;padding:0px 2px;font-size:18px;position:relative;top:1px"/> 
        </fieldset>  
  </ul>

    <div style="width:80px;display:inline-block;position:relative;top:0px;" >
    <a id="sarr" href="societe.php"  style="margin-left:10px;padding:19px;position:relative;top:8px"><i class="fa fa-refresh" ></i></a>
    </div>

</div>

    </form>




<table>
        <tr>

                <th >Caisse</th>

                <th>Logo caisse</th>

                <?php if($user_detail['affichersolde']=='oui'){ ?>
                <th >Solde</th>
                <?php } ?>

                <?php if($user_detail['transfertcaisse']=='oui') {?>
                  <th >Transfert d'espèces entre caisses</th>
                <?php } ?>

                <?php if($user_detail['cloturercaisse']=='oui') {?>

                <th >Date dernière clôture </th>

                <?php }?>

                <th >En cours / Fermée </th>

                <th >Supprimer</th>

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





if(isset($_POST['filtred']))
{

  $filtre="?filtre=ok&";
  $filtre=$filtre."caisse=".$_POST['caisse']."&";
  $req = $bdd->query("SELECT * from succursale where succursale='".$_POST['caisse']."' order by etat desc ,id desc");



                           //add parametre get filte
                        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $queryurl = parse_url($url, PHP_URL_QUERY);
                        // Returns a string if the URL has parameters or NULL if not
        
                        $url = rtrim($filtre,"&");
                        $url = $url."#";
                        

                        echo '<script> window.history.replaceState(null, null, "'.$url.'") </script>';   


}
elseif(isset($_GET['filtre']))
{

  $req = $bdd->query("SELECT * from succursale where succursale='".$_GET['caisse']."' order by etat desc ,id desc");

}
else
{

  $req = $bdd->query("SELECT * from succursale order by etat desc ,id desc LIMIT ".$sf.",".$nb);

}


function changedate($var)
{
$tab = explode("-",$var);
$nouvelledate = $tab[2]."/".$tab[1]."/".$tab[0];
return $nouvelledate;
}
          

while ($succursale = $req->fetch())
{

?>       
        <tr>
                <td ><?php echo $succursale['succursale'] ?></td>





        <script>

        currenturl=location.href;
        urlhref="";

        function docurlhref(iddoc,urldoc)
        {

          if(currenturl.indexOf('filtre') !== -1)
          {

            if(currenturl.indexOf('idc') !== -1)
            {
              if(currenturl.indexOf('#') !== -1)
              {
                 currenturl=currenturl.substring(0, currenturl.indexOf('#'));             
              }
              currenturl=currenturl.substring(0, currenturl.indexOf('&idc'));
              urlhref=currenturl+"&idc="+iddoc+"&urldoc="+urldoc+"#docoption"; 
            }
            else if(currenturl.indexOf('#ok') !== -1)
            {
              currenturl=currenturl.substring(0, currenturl.indexOf('#ok'));
              urlhref=currenturl+"&idc="+iddoc+"&urldoc="+urldoc+"#docoption"; 
            }
            else
            {
              if(currenturl.indexOf('#') !== -1)
              {
                 currenturl=currenturl.substring(0, currenturl.indexOf('#'));             
              }
              urlhref=currenturl+"&idc="+iddoc+"&urldoc="+urldoc+"#docoption"; 
            }
          }
          else
          {
            if(currenturl.indexOf('#') !== -1)
              {
                 currenturl=currenturl.substring(0, currenturl.indexOf('#'));             
              }
            urlhref="?<?php if(isset($_GET['sf']))  echo 'sf='.$_GET['sf'].'&'; ?><?php if(isset($_GET['ste'])) echo 'ste='.$_GET['ste'].'&'?><?php if(isset($_GET['compte'])) echo 'compte='.$_GET['compte'].'&'?>idc="+iddoc+"&urldoc="+urldoc+"#docoption";
          }

          location.href=urlhref;
        }

        </script>




                <td style="font-size:16px;color:#00cd00;cursor:pointer">
                  <form action="societe.php" method="post" enctype="multipart/form-data" name="importdoc" id="importdoc<?php echo $succursale['id'] ?>" >
                    <input type="file" name="img1"  id="img1<?php echo $succursale['id'] ?>" style="display: none;" onchange="inmportdoc(<?php echo $succursale['id'] ?>)" /> 
                    <input  type="hidden" name="pagindoc" value="<?php if(isset($_GET['sf']))  echo $_GET['sf']; ?>" />
                    <input  type="hidden" name="stedoc" value="<?php if(isset($_GET['ste']))  echo $_GET['ste']; ?>" />
                    <input  type="hidden" name="currentlink" id="currentlink<?php echo $succursale['id'] ?>" value="" />

                    <i style="color:#14B214" class="fa fa-plus-square" onclick="document.getElementById('currentlink<?php echo $succursale['id'] ?>').value=location.href;document.getElementById('img1<?php echo $succursale['id'] ?>').click();" > </i> 
                    <input type="hidden"  name="idcaisse" value="<?php echo $succursale['id'] ?>" >
                  </form>
                  <?php
                  $reqdoc = $bdd->query("SELECT id,logo from succursale where id=".$succursale['id']);
                  $urldoc = $reqdoc->fetch();
                  if ($urldoc['logo']<>"")
                  {
                  ?>
                    <i onclick="docurlhref(<?php echo $urldoc['id'].',\''.$urldoc['logo'].'\'' ?>)" id="<?php echo $urldoc['logo'] ?>" class="fa fa-image"></i>
                  <?php
                  }
                  $reqdoc->closecursor();
                  ?>
                </td>

<script>

                    function inmportdoc(idc)
                    {
                        if( document.getElementById("img1"+idc).files.length != 0 ){
                            document.getElementById("importdoc"+idc).submit();
                        }
                    }

</script>


          <?php

              // total especes entree all succursale
              $totalespeceall = $bdd->query("SELECT sum(montant) as totalespeceall from caisse  where deleted='non' and type='entree'  and idsuccursale=".$succursale['id']);
              $totalespecealldetail = $totalespeceall ->fetch();
              $totalespeceall->closecursor();


              // total sortie all succursale
              $totalsortieall = $bdd->query("SELECT sum(montant) as totalsortieall from caisse  where deleted='non' and  type='sortie' and idsuccursale=".$succursale['id']);
              $totalsortieall_detail = $totalsortieall ->fetch();
              $totalsortieall->closecursor();


              $totalcaisse = $totalespecealldetail['totalespeceall'] - $totalsortieall_detail['totalsortieall'];
          
          ?>

                <?php if($user_detail['affichersolde']=='oui'){ ?>
                <td style="font-weight:bold"><?php echo number_format( $totalcaisse, 2, ',', ' ')." Dhs"; ?></td>
                <?php } ?>



                <?php if($user_detail['transfertcaisse']=='oui') {?>
                  <td >

                        <?php
                        if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']))
                        {
                        ?>
                          <a href="#" onclick="location.href=location.href.substring(0, location.href.indexOf('#'))+'&idcaisse=<?php echo $succursale['id']; ?>#transfert'" style="background:#d6d6d6;color:#555555;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >Transférer</a>
                        <?php
                        }
                        else
                        {
                        ?>
                          <a href="#" onclick="location.href='?idcaisse=<?php echo $succursale['id']; ?>#transfert'" style="background:#d6d6d6;color:#555555;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >Transférer</a>
                        <?php 
                        }
                        ?>


                  </td>
                <?php }?>








                <?php if($user_detail['cloturercaisse']=='oui') {?>
                  <td >

                        <?php
                        if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']))
                        {
                        ?>
                          <a href="#" onclick="location.href=location.href.substring(0, location.href.indexOf('#'))+'&idcaisse=<?php echo $succursale['id']; ?>&datelastcloture=<?php echo $succursale['datelastcloture']; ?>#changedatelastcloture'" style="background:#d6d6d6;color:#555555;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" ><?php echo changedate($succursale['datelastcloture']) ?></a>
                        <?php
                        }
                        else
                        {
                        ?>
                          <a href="#" onclick="location.href='?idcaisse=<?php echo $succursale['id']; ?>&datelastcloture=<?php echo $succursale['datelastcloture']; ?>#changedatelastcloture'" style="background:#d6d6d6;color:#555555;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" ><?php echo changedate($succursale['datelastcloture']) ?></a>
                        <?php 
                        }
                        ?>

                  </td>
                <?php }?>









              <?php if(number_format($totalcaisse, 2, '.', '')==0.00 ){ ?>

                <?php if($succursale['etat']=="encours" ){ ?>
                <td>
                <?php if($user_detail['fermeturercaisse']=='oui') {?>

                      <?php
                      if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']))
                      {
                      ?>
                        <a href="#" onclick="location.href=location.href.substring(0, location.href.indexOf('#'))+'&idcaisse=<?php echo $succursale['id']; ?>&amp;etat=cloture';" style="background:#00cd00;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >En cours</a>
                      <?php
                      }
                      else
                      {
                      ?>
                        <a href="#" onclick="location.href='?idcaisse=<?php echo $succursale['id']; ?>&amp;etat=cloture';" style="background:#00cd00;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >En cours</a>
                      <?php 
                      }
                      ?>

                <?php }else {?>
                  En cours
                <?php }?>

                </td>
                <?php }else { ?>
                <td >
                <?php if($user_detail['fermeturercaisse']=='oui') {?>
                      <?php
                      if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']) )
                      {
                      ?>
                      <a href="#" onclick="location.href=location.href.substring(0, location.href.indexOf('#'))+'&idcaisse=<?php echo $succursale['id']; ?>&amp;etat=encours';" style="background:#888;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >Fermée</a>
                      <?php
                      }
                      else
                      {
                      ?>
                      <a href="#" onclick="location.href='?idcaisse=<?php echo $succursale['id']; ?>&amp;etat=encours';" style="background:#888;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >Fermée</a>
                      <?php 
                      }
                      ?>

                <?php }else {?>
                  Fermée
                <?php }?>
                
                </td>
                <?php }?>

            <?php }else
                 {
                  if($succursale['etat']=="encours")
                    echo "<td>En cours</td>";
                  else
                    echo "<td>Fermée</td>";
                 }

            ?>







                <?php
                  $reqste = $bdd->query('SELECT count(id) as nbsuccursale from caisse where idsuccursale='.$succursale['id']);
                  $idste = $reqste ->fetch();
                  $reqste->closecursor();
                ?>


                <?php if( $idste['nbsuccursale']!=0 ){ ?>
                <td style="color:#888"><i class="fa fa-times"></i> Supprimer</td>   
                <?php }else { ?>
                <td><a href="#<?php echo $succursale['id']; ?>" id="supactive" onclick="document.getElementById('<?php echo $succursale['id']; ?>c').style.display='block';" ><i class="fa fa-times"></i> Supprimer</a>
                                        <div class="del" id="<?php echo $succursale['id']; ?>c" style="width:200px">  



                                        <?php
                                        if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']) )
                                        {
                                        ?>
                                            <a href="#" onclick="location.href=location.href.substring(0, location.href.indexOf('#'))+'&iddelet=<?php echo $succursale['id']; ?>&amp;sup=ok';"  style="display:inline;" >Oui</a>
                                        <?php
                                        }
                                        else
                                        {
                                        ?>
                                            <a href="#" onclick="location.href='?iddelet=<?php echo $succursale['id']; ?>&amp;sup=ok';"  style="display:inline;" >Oui</a>
                                        <?php 
                                        }
                                        ?>



                                            &nbsp;&nbsp;-&nbsp;
                                            <a href="#" onclick="document.getElementById('<?php echo $succursale['id']; ?>c').style.display='none';" style="display:inline;" >Non</a>
                                        </div>
                </td>
                <?php }?>
        </tr>
<?php 
}
$req->closecursor();
?> 
</table>



<!-- Pagination PHP ------------------------------------------------------------------------------------------>




        <?php if ( !isset($_POST['filtred']) and !isset($_GET['filtre'])) {?>
                        
                <ul id="nav">
                    <?php


                        $req = $bdd->query("SELECT COUNT(*) AS nb FROM succursale");

                        $m = 'societe.php?nb='.$nb.'&amp;';
                      

                        $row1 = $req->fetch();
                        if($row1['nb']!=0){
                            $nbpage=ceil($row1['nb']/$nb);
                            $fp=0;

                            if(isset($_GET['sf'])){
                                if($_GET['sf']>0){
                                    ?>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=0#"><<</a></li>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=<?php echo $_GET['sf']-1;?>#"><</a></li>
                                    <?php
                                }
                                if($_GET['sf']>=$nbpage){
                                    $fp=$_GET['sf']-2;              
                                }
                            }
                            for($i=$fp;$i<$nbpage+$fp;$i++){
                                ?>
                                <li ><a <?php if(isset($_GET['sf'])){if($_GET['sf']==$i){echo "class='active'";}}elseif($i==0){echo "class='active'";}?> href="<?php echo $m;?>sf=<?php echo $i;?>#"><?php echo $i+1;?></a></li>
                                <?php
                            }
                            if(isset($_GET['sf'])){
                                if($_GET['sf']<$nbpage-1 AND $nbpage!=1){
                                    ?>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=<?php echo $_GET['sf']+1;?>#"> > </a></li>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=<?php echo $nbpage-1;?>#"> >> </a></li>     
                                    <?php
                                }
                            }
                            elseif($nbpage!=1){
                                ?>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=<?php echo 1;?>#"> > </a></li>
                                <li ><a class="nbpage" href="<?php echo $m;?>sf=<?php echo $nbpage-1;?>#"> >> </a></li>              
                                <?php               
                            }
                        }
                    ?>
                    </ul>  

            <?php }?>









<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="script-autocomplete.js"></script>





<div id="docoption" class="modalDialog">

  <div class="popup">
   
    <h1>Document</h1>
    <form action="" method="post"  >
    <div class="cont" style="" >

        <a onclick="popupCenter('logo_caisse/<?php echo $_GET["urldoc"] ?>', 'Ajbon',500,500);" style="border:1px solid #14B214;border-radius: 3px;margin:30px;color:#14B214;font-weight:bold;display:block;text-align:center;padding:10px;font-size:18px;background:#FEFEFE;cursor:pointer"  > <i class="fa fa-eye"></i> Afficher</a></p>


        <a onclick="document.getElementById('deletedoc').style.display='block';" style="margin:30px;color:red;font-weight:bold;display:block;text-align:center;padding:10px;font-size:14px;cursor:pointer"  > <i class="fa fa-trash"></i> Supprimer</a></p>

          <div class="del" style="width:277px;position:relative;bottom:10px;left:30px;text-align:center;color:red" id="deletedoc" >  
              <a  onclick="location.href=location.href.substring(0, location.href.indexOf('#docoption'))+'&iddocdelet=<?php echo $_GET['idc'];?>'"  style="display:inline;cursor:pointer" >Oui</a>&nbsp;&nbsp;-&nbsp;
              <a  onclick="document.getElementById('deletedoc').style.display='none';" style="display:inline;cursor:pointer" >Non</a>
          </div>


    </div>
    </form>




  <a href="#" id="x" title="quitter">x</a>
  </div>
       
    
</div>

<script>

                    function popupCenter(url, title, w, h) {
                      var left = (screen.width/2)-(w/2);
                      var top = (screen.height/2)-(h/2);
                      return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                    }
</script>

















<script> $( document ).ready(function() {document.getElementById("error").style.display="none"});</script>

<!------------------------------popup ajout carnet------------------------------>

<div id="ste" class="modalDialog">

  <div class="popup">
  
  <h1>Ajouter une Caisse</h1>

   <form action="" method="post">
      <div class="cont">


        <fieldset>
          <legend>Caisse:</legend>
          <p><input  required type="text"  id="succursale" name="succursale" autocomplete="off" onkeyup="apost('succursale')" ></p>
        </fieldset>


        <p><input type="submit" name="add" value="Ajouter la Caisse"></p>
      </div>
    </form>


    <ul id="error">

        <?php
                                if(isset($_POST['add']))
                                {
                                    if($succursaleid=='1'){
                                        echo'<script> $( document ).ready(function() {document.getElementById("error").style.display="block"}); </script>';
                                        echo '<li style="color:#fb065c;font-weight:bold">La Caisse existe déjà</li>';
                                    }
                                        
                                }
        ?>

    </ul>

  <a href="#" id="x" title="quitter">x</a>
  </div>
       
</div>




<script> $( document ).ready(function() {document.getElementById("error1").style.display="none"});</script>


<!------------------------------transfert------------------------------>

<div id="transfert" class="modalDialog">

  <div class="popup">
  
  <h1>Transfert d'espèces entre caisses</h1>

   <form action="" method="post">
      <div class="cont">



                <?php
                  $succursale = $bdd->query('SELECT succursale from succursale where id='.$_GET['idcaisse']);
                  $succursale_detail = $succursale ->fetch();
                  $succursale->closecursor();
                ?>

        <fieldset>
          <legend>Le montant à transférer de <span style="font-weight:bold"><?php echo $succursale_detail['succursale']; ?></span></legend>
          <p><input  required type="text"  id="montant" name="montant" autocomplete="off" onKeyUp="nombre('montant')" style="width:245px" ><span style="color:#666;font-weight:bold"> DHs</span></p>
        </fieldset>



        <fieldset>
          <legend style="font-weight:bold" >Vers</legend>
            <select required name="succursale" id="succursale" style="margin:10px 0px"  >
              <option  value=""></option>
              <?php
                $req1 = $bdd->query("SELECT id,succursale from succursale where etat='encours' and id<>".$_GET['idcaisse']." order by succursale asc ");
                while ($ste1 = $req1->fetch())
                {

                  ?> 
                    <option  value="<?php echo $ste1['id'] ;?>">  <?php echo $ste1['succursale'] ;?> </option>
                  <?php
      
                }
              ?>  
             </select>
        </fieldset>


        <p><input type="submit" name="addtransfert" value="Transférer"></p>
      </div>
    </form>


    <ul id="error1">

        <?php
                                if(isset($_POST['addtransfert']))
                                {
                                    if($verifycaissevalue=='1'){
                                        echo'<script> $( document ).ready(function() {document.getElementById("error1").style.display="block"});
                                        document.getElementById("num").focus(); </script>';
                                        echo '<li style="color:#fb065c;font-weight:bold">le montant de Transfert ne devrait pas être supérieur au solde de caisse</li>';
                                    }
                                        
                                }
        ?>

    </ul>


                      <?php
                      if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']))
                      {
                      ?>

                        <a href="#" id="x" title="quitter" onclick="location.href=location.href.substring(0, location.href.indexOf('&idcaisse'))+'#'" >x</a>

                      <?php
                      }
                      else
                      {
                      ?>
                      
                        <a href="#" id="x" title="quitter" onclick="location.href='societe.php#'" >x</a>

                      <?php 
                      }
                      ?>

  </div>
       
</div>








<!------------------------------transfert------------------------------>

<div id="changedatelastcloture" class="modalDialog">

  <div class="popup">
  
  <h1>Modification Date dernière clôture</h1>

   <form action="" method="post">
      <div class="cont">


        <fieldset>
          <legend style="font-weight:bold" >Date dernière clôture</legend>
            <input  name="datelastcloture" type="date" value="<?php echo $_GET['datelastcloture']?>">
        </fieldset>


        <p><input type="submit" name="changedatelastcloture" value="Modifier"></p>
      </div>
    </form>


                      <?php
                      if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']))
                      {
                      ?>

                        <a href="#" id="x" title="quitter" onclick="location.href=location.href.substring(0, location.href.indexOf('&idcaisse'))+'#'" >x</a>

                      <?php
                      }
                      else
                      {
                      ?>
                      
                        <a href="#" id="x" title="quitter" onclick="location.href='societe.php#'" >x</a>

                      <?php 
                      }
                      ?>

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