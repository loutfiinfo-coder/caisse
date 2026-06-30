<?php 
include("config.php");

?>




<?php


if(isset($_SESSION['idcaisse']))
{

$user = $bdd->query('SELECT id,pages,login,ste,modification,supression,justification,editdocument,boncaisse,acomptabilise,editdateop,exportexcel,colonnecaisse,colonneentree,colonnesortie,affichersolde from users where id='.$_SESSION['idcaisse']);
$user_detail = $user ->fetch();
$user->closecursor();

if( !stristr($user_detail['pages'], 'entree')) { header('location:conn.php'); }

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







    if (isset($_POST['idcaisse']))
    {  

        if ($_FILES['img1']['error'] == 0)
        {

        $req = $bdd->query('SELECT max(id) as id from documents');                  
        $donnees = $req->fetch();
        $id=$donnees['id'];
        if (empty($id)) $id=0;
        $id=$id+1;
        $req ->closeCursor(); 

            include('reimg.php');
            $infosfichier = pathinfo($_FILES['img1']['name']);
            $extension_upload = $infosfichier['extension'];
            $_FILES['img1']['name']=$id.time().'.'.$extension_upload;
            move_uploaded_file($_FILES['img1']['tmp_name'], 'documents/' . basename($_FILES['img1']['name']));

            $req = $bdd->prepare('INSERT INTO documents(idcaisse,urldoc) VALUES(:idcaisse,:urldoc)');
            $req->execute(array('idcaisse' => $_POST['idcaisse'] , 'urldoc' => $_FILES['img1']['name'] ));



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



 $reqdocdelet = $bdd->prepare('DELETE FROM documents where id=:iddocdelet');
 $reqdocdelet->execute(array('iddocdelet' => $_GET['iddocdelet']));

 unlink("documents/".$_GET['urldoc']."");

           $currentlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


           if(strpos($currentlink, '&idc') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&idc"));
           }
           else if(strpos($currentlink, 'idc') !== false)
           {
            $currentlink="entree.php";
           }

           header('location:'.$currentlink.'#');

}











if(isset($_POST['add']))
{


    $req = $bdd->prepare('INSERT INTO caisse(type,typeentree,idsuccursale,dateop,client,montant,concerne,type_alimentation,libelle,caissier,mad_agence,mad_compte,mad_beneficiaire,mad_cin,idfamillevente) VALUES(:type,:typeentree,:idsuccursale,:dateop,:client,:montant,:concerne,:type_alimentation,:libelle,:caissier,:mad_agence,:mad_compte,:mad_beneficiaire,:mad_cin,:idfamillevente)');

    $req->execute(array('type' => "entree",'typeentree' => $_POST['typeentreeadd'],'idsuccursale' => $_POST['Succursaleadd'],'dateop' => $_POST['dateopadd'],'client' => $_POST['clientadd'],'montant' => number_format((float)$_POST['montantadd'], 2, '.', ''),'concerne' => $_POST['concerneadd'],'type_alimentation' => $_POST['type_alimentationdd'],'libelle' => $_POST['libelleadd'],'caissier' => $user_detail['login'],'mad_agence' => $_POST['mad_agence'],'mad_compte' => $_POST['mad_compte'],'mad_beneficiaire' => $_POST['mad_beneficiaire'],'mad_cin' => $_POST['mad_cin'],'idfamillevente' => $_POST['familleadd'] ));



    $reqref = $bdd->query("SELECT id,type,dateop,typeentree,type_alimentation from caisse where deleted='non' order by id desc limit 1");
    $refdetai = $reqref ->fetch();
    $reqref->closecursor();


    $countcaissereq = $bdd->query("SELECT count(id) as nbcaisse from caisse where deleted='non' and YEAR(dateop)=YEAR('".$refdetai['dateop']."') and type='entree' and type_alimentation<>'5' ");
    $countcaisse_e = $countcaissereq ->fetch();
    $countcaissereq->closecursor();


    $countcaissereq = $bdd->query("SELECT count(id) as nbcaisse from caisse where deleted='non' and YEAR(dateop)=YEAR('".$refdetai['dateop']."') and type='entree' and type_alimentation='5' ");
    $countcaisse_mad = $countcaissereq ->fetch();
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

    if($refdetai['type_alimentation']!='5')
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
    


    header('location:entree.php#');    

}






if(isset($_POST['modif']))
{


        $reqx = $bdd->prepare('UPDATE caisse  set  typeentree=:typeentree,dateop=:dateop,client=:client,montant=:montant,concerne=:concerne,type_alimentation=:type_alimentationmodif,libelle=:libelle,mad_agence=:mad_agence,mad_compte=:mad_compte,mad_beneficiaire=:mad_beneficiaire,mad_cin=:mad_cin,idfamillevente=:idfamillevente where id=:idc');

        $reqx->execute(array('idc' =>$_GET['idc'],'typeentree' => $_POST['typeentreemodif'], 'dateop' => $_POST['dateopmodif'] ,'client' => $_POST['clientmodif'],'type_alimentationmodif' => $_POST['type_alimentationmodif'],'montant' => number_format((float)$_POST['montantmodif'], 2, '.', ''),'concerne' => $_POST['concernemodif'],'libelle' => $_POST['libellemodif'],'mad_agence' => $_POST['mad_agencem'] ,'mad_compte' => $_POST['mad_comptem'] ,'mad_beneficiaire' => $_POST['mad_beneficiairem'] ,'mad_cin' => $_POST['mad_cinm'],'idfamillevente' => $_POST['famillemodif'] )); 
       
        $reqx->closecursor();



           $currentlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


           if(strpos($currentlink, '&idc') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&idc"));
           }
           else if(strpos($currentlink, 'idc') !== false)
           {
            $currentlink="entree.php";
           }

           header('location:'.$currentlink.'#');
  
}








if(isset($_GET['sup']))
{



/*
    $reqx = $bdd->prepare('UPDATE caisse  set  deleted=:deleted,deleter=:deleter where id=:id');
    $reqx->execute(array('id' =>$_GET['iddelet'],'deleted' => 'oui','deleter' => $user_detail['login'].' le: '.date("d-m-Y H:i:s"))); 
    $reqx->closecursor();

*/


 $req = $bdd->prepare('DELETE FROM caisse where id=:iddelet');
 $req->execute(array('iddelet' => $_GET['iddelet']));



 $geturldoc = $bdd->query("SELECT urldoc from documents where idcaisse=".$_GET['iddelet']." ");
 while ($geturldoc_detail = $geturldoc ->fetch())
  {
     unlink("documents/".$geturldoc_detail['urldoc']."");
  }

 $geturldoc->closecursor();


 $reqdocdelet = $bdd->prepare('DELETE FROM documents where idcaisse=:idcaisse ');
 $reqdocdelet->execute(array('idcaisse' => $_GET['iddelet']));




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








if(isset($_GET['valide']))
{


        $reqx = $bdd->prepare('UPDATE caisse  set  valide=:valide where id=:idc');

        $reqx->execute(array('idc' => $_GET['idvalide'], 'valide' => $_GET['valide'] )); 
       
        $reqx->closecursor();



           $currentlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


           if(strpos($currentlink, '&idvalide') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&idvalide"));
           }
           else if(strpos($currentlink, 'idvalide') !== false)
           {
            $currentlink="entree.php";
           }

           header('location:'.$currentlink.'#');

}





if(isset($_GET['acomptabilise']))
{


        $reqx = $bdd->prepare('UPDATE caisse  set  acomptabilise=:acomptabilise where id=:idc');

        $reqx->execute(array('idc' => $_GET['idacomptabilise'], 'acomptabilise' => $_GET['acomptabilise'] )); 
       
        $reqx->closecursor();



           $currentlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


           if(strpos($currentlink, '&idacomptabilise') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&idacomptabilise"));
           }
           else if(strpos($currentlink, 'idacomptabilise') !== false)
           {
            $currentlink="entree.php";
           }

           header('location:'.$currentlink.'#');


}






if(isset($_POST['enregistrer']))
{


    $colonneentree = "";

    if(isset($_POST['caisse']))
      $colonneentree =$colonneentree."caisse|";
    if(isset($_POST['reference']))
      $colonneentree =$colonneentree."reference|";
    if(isset($_POST['dateop']))
      $colonneentree =$colonneentree."dateop|";
    if(isset($_POST['libelle']))
      $colonneentree =$colonneentree."libelle|";
    if(isset($_POST['nature_entree']))
      $colonneentree =$colonneentree."nature_entree|";
    if(isset($_POST['type_alimentation']))
      $colonneentree =$colonneentree."type_alimentation|";
    if(isset($_POST['client']))
      $colonneentree =$colonneentree."client|";
    if(isset($_POST['notes']))
      $colonneentree =$colonneentree."notes|";
    if(isset($_POST['acomptabilise']))
      $colonneentree =$colonneentree."acomptabilise|";
    if(isset($_POST['justifie']))
      $colonneentree =$colonneentree."justifie|";
    if(isset($_POST['documents']))
      $colonneentree =$colonneentree."documents|";
    if(isset($_POST['caissier']))
      $colonneentree =$colonneentree."caissier|";

    $req = $bdd->prepare('UPDATE users  set  colonneentree=:colonneentree where id=:id');
    $req->execute(array('colonneentree' => $colonneentree, 'id' => $_SESSION['idcaisse']));

    header('location:entree.php#');    
    
}








if(isset($_POST['filtred']))
{
  if(empty($_POST['ste']) and empty($_POST['ref']) and empty($_POST['client']) and empty($_POST['concerne']) and empty($_POST['libelle']) and empty($_POST['montant']) and empty($_POST['du1']) and empty($_POST['au1']) and empty($_POST['choixst']) and empty($_POST['choixf'])  and empty($_POST['choixstvalide']) and empty($_POST['choixstype']) and empty($_POST['choixstcom']) and empty($_POST['caissier']) and !isset($_POST['deleteditems'] )) 
  {
  header('location:entree.php#');
  }  
}







?>







<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Entrées ..</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="ctm.css" />
  <link rel="icon" type="image/ico" href="logo.ico" />
  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  
 <script src="jquery.min.js"></script>

  <script src="xlsx.full.min.js"></script>


<script>
var tableToExcel = (function() {
  /* ── Parsers ────────────────────────────────────────────── */
  function parseAmount(str) {
    if (typeof str !== 'string') return str;
    var s = str.trim().replace(/\s/g, '');
    if (s.indexOf(',') !== -1 && s.indexOf('.') !== -1) {
      s = s.replace(/,/g, '');
    } else if (s.indexOf(',') !== -1) {
      s = s.replace(',', '.');
    }
    var n = parseFloat(s);
    return isNaN(n) ? str : n;
  }

  function isAmountHeader(txt) {
    return /montant|amount|prix|price|total|solde|balance|debit|credit|somme/i.test(txt);
  }

  /* ── Couleur RGB/RGBA → hex RRGGBB (sans #) ─────────────── */
  function rgbToHex(rgb) {
    if (!rgb || rgb === 'transparent' || rgb === 'rgba(0, 0, 0, 0)') return null;
    var m = rgb.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
    if (!m) return null;
    return [m[1], m[2], m[3]]
      .map(function(x) { return ('0' + parseInt(x).toString(16)).slice(-2); })
      .join('').toUpperCase();
  }

  /* ── Style xlsx-js-style à partir d'une cellule HTML ────── */
  function cellStyle(cell, isHeader) {
    var cs  = window.getComputedStyle(cell);
    var bg  = rgbToHex(cs.backgroundColor);
    var fg  = rgbToHex(cs.color);

    var style = {};

    if (bg) style.fill = { fgColor: { rgb: bg } };
    if (fg) style.font = { color: { rgb: fg } };
    if (isHeader) {
      style.font = Object.assign({ bold: true }, style.font);
    }

    return style;
  }

  /* ── Export principal ───────────────────────────────────── */
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table);

    var rows      = table.querySelectorAll('tr');
    var wsData    = [];   /* valeurs */
    var wsStyles  = [];   /* styles parallèles */
    var amountCols = {};

    rows.forEach(function(row, ri) {
      var cells    = row.querySelectorAll('th, td');
      var rowData  = [];
      var rowStyle = [];
      var isHeader = ri === 0;

      cells.forEach(function(cell, ci) {
        var txt = (cell.innerText || cell.textContent || '').trim();

        if (isHeader && isAmountHeader(txt)) amountCols[ci] = true;

        var value = (!isHeader && amountCols[ci]) ? parseAmount(txt) : txt;
        rowData.push(value);
        rowStyle.push(cellStyle(cell, isHeader));
      });

      wsData.push(rowData);
      wsStyles.push(rowStyle);
    });

    /* ── Construction feuille avec xlsx-js-style ────────────
       Nécessite : https://cdn.jsdelivr.net/npm/xlsx-js-style/dist/xlsx.bundle.js
       (drop-in replacement de SheetJS — même API + propriété .s sur chaque cell) */
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.aoa_to_sheet(wsData);

    /* Injecter les styles cellule par cellule */
    wsStyles.forEach(function(rowStyle, ri) {
      rowStyle.forEach(function(style, ci) {
        if (!Object.keys(style).length) return;
        var addr = XLSX.utils.encode_cell({ r: ri, c: ci });
        if (ws[addr]) ws[addr].s = style;
      });
    });

    XLSX.utils.book_append_sheet(wb, ws, name || 'Feuille1');
    XLSX.writeFile(wb, (name || 'export') + '.xlsx');
  };
})();


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


<a class="menu" href="index.php"  ><i  class="fa fa-history" ></i> Journal de caisse </a>
<a class="menu" href="sortie.php" ><i class="fa fa-arrow-up" aria-hidden="true"></i> Sorties</a>
<a class="menu"  href="#" style="background:url(crossword.png) ;color:#00BA84;border-bottom:3px solid #00BA84;" ><i class="fa fa-arrow-down" aria-hidden="true"></i> Entrées</a>

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


<a class="menu" href="decon.php" style="color:#00BA84"><i class="fa fa-power-off"></i></a>

</div>

<div style="width:1250px;margin:auto;color:#444;font-size:20px;margin-top:-20px;font-family: 'Oswald',serif;">Bienvenu: <strong style="color:#00BA84;margin-left:5px;text-transform : capitalize;"><?php echo $user_detail['login']; ?></strong></div>







<?php

if(isset($_POST['affichertt']))
{

    unset($_POST['client']);
    unset($_POST['concerne']);
    unset($_POST['choixv']);
    unset($_POST['ref']);

    unset($_POST['montant']);
    unset($_POST['choixg']);
    unset($_POST['choixst']);
    unset($_POST['choixstvalide']);
    unset($_POST['choixstype']);

    unset($_POST['choixd']);

	  unset($_POST['du1']);
	  unset($_POST['au1']);



    echo "<script type='text/javascript'>document.location.href='entree.php';</script>";

}


?>





<script>
function societe(ste)
{


 if(ste!="")
 {
 location.href="entree.php?ste="+ste; 
 }
 else
 {
 location.href="entree.php";
 }


}

</script>

<form method="post" id="form_av" action=""> 


<div style="width:1250px;margin:20px auto">
<div style="width:300px;display:inline-block;" >
<a id="sarr" href="#addentree" ><i class="fa fa-pencil-square-o"></i> Effectuer une entrée </a>
</div>
<div style="width:80px;display:inline-block" >
<a id="sarr" href="entree.php"  style="margin-left:10px"><i class="fa fa-refresh" ></i></a>
</div>


<input type="button"  id="affichertt" onclick="location.href='#colonneentree';" value="Personnaliser les colonnes" style="width:270px;padding:0px;display:inline-block;height:67px;position:relative;top:-2px;font-size:20px;cursor:pointer;margin-left:10px" />




</div>
</form>


<div class="filtre" style="height:100px;padding:4px 10px 16px 10px;margin-bottom:15px;background:#fff">

    <ul class="nav">
        <form method="post" id="form_av" action=""> 




        <input type="hidden" id="id_ste" value="" />

       <fieldset style="width:180px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;margin-right:2px;height:70px;">
          <legend>Caisse</legend>
            <select  name="ste"  style="margin:10px 0px;width:170px;"  >
              <option  value=""></option>
              <?php
                $req1 = $bdd->query("SELECT id,succursale from succursale where etat='encours' order by succursale asc ");
                while ($ste1 = $req1->fetch())
                {

                  // total especes entree all succursale
                  $totalespeceall = $bdd->query("SELECT sum(montant) as totalespeceall from caisse  where  deleted='non' and   type='entree'  and idsuccursale=".$ste1['id']);
                  $totalespecealldetail = $totalespeceall ->fetch();
                  $totalespeceall->closecursor();


                  // total sortie all succursale
                  $totalsortieall = $bdd->query("SELECT sum(montant) as totalsortieall from caisse  where  deleted='non' and   type='sortie' and idsuccursale=".$ste1['id']);
                  $totalsortieall_detail = $totalsortieall ->fetch();
                  $totalsortieall->closecursor();


                  $totalcaisse = $totalespecealldetail['totalespeceall'] - $totalsortieall_detail['totalsortieall'];

                   
                  
                  if(stristr($steuser, "'".$ste1['id']."'"))
                  {
                  ?> 
                  <option  value="<?php echo $ste1['succursale'] ;?>" <?php if (isset($_POST['ste'])){ if($_POST['ste']==$ste1['succursale']) echo "selected" ;}elseif (isset($_GET['ste'])){ if($_GET['ste']==$ste1['succursale']) echo "selected" ;} ?> >  <?php echo $ste1['succursale'] ;?>   </option>

                  <?php
                  }
                }
              ?>  
             </select>
        </fieldset>




        <fieldset style="width:150px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;margin-right:2px;height:70px;">
        <legend>Caissier</legend>
            <li id="filtrec1"><input style="padding:9px;width:115px" name="caissier" type="text"  id="caissierf"  onkeyup="apost('caissierf')"  autocomplete="off"  <?php if(isset($_POST['caissier'])){echo ' value="'.$_POST["caissier"].'" ';}elseif(isset($_GET['caissier'])){echo ' value="'.$_GET["caissier"].'" ';}?>/></li> 
        </fieldset>


        <fieldset style="width:150px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;margin-right:2px;height:70px;">
        <legend>Numéro</legend>
            <li id="filtrec1"><input style="padding:9px;width:115px" name="ref" type="text"  id="country_idref1" onkeyup="autocompletref1();apost('country_idref1')"  autocomplete="off"  <?php if(isset($_POST['ref'])){echo ' value="'.$_POST["ref"].'" ';}elseif(isset($_GET['ref'])){echo ' value="'.$_GET["ref"].'" ';}?>/></li> 
            <ul style="width:128px;color:#333;font-size:15px;margin-left:6px;" id="country_list_idref1"></ul>
        </fieldset>



        <fieldset style="width:150px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;margin-right:2px;height:70px;">
        <legend>Client</legend>
            <li id="filtrec1"><input style="padding:9px;width:115px" name="client" type="text"  id="country_id2" onkeyup="autocomplet2();apost('country_id2')"  autocomplete="off"  <?php if(isset($_POST['client'])){echo ' value="'.$_POST["client"].'" ';}elseif(isset($_GET['client'])){echo ' value="'.$_GET["client"].'" ';}?>/></li> 
            <ul style="width:128px;color:#333;font-size:15px;margin-left:4px;" id="country_list_id2"></ul>
        </fieldset>


        <fieldset style="width:190px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;margin-right:2px;height:70px;">
        <legend>Libellé </legend>
            <li id="filtrec1"><input style="padding:9px;width:158px" name="libelle" type="text"  id="country_idlibelletentreefiltre" onkeyup="autocompletlibelletentreefiltre();apost('country_idlibelletentreefiltre')"  autocomplete="off"  <?php if(isset($_POST['libelle'])){echo ' value="'.$_POST["libelle"].'" ';}elseif(isset($_GET['libelle'])){echo ' value="'.$_GET["libelle"].'" ';}?>/></li>
            <ul style="width:172px;color:#333;font-size:15px;margin-left:8px;" id="country_list_idlibelletentreefiltre"></ul> 
        </fieldset>



        <fieldset style="width:170px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;margin-right:2px;height:70px;">
        <legend>Note</legend>
            <li id="filtrec1"><input style="padding:9px;width:140px" name="concerne" type="text"  id="concernef"  onkeyup="apost('concernef')"  autocomplete="off"  <?php if(isset($_POST['concerne'])){echo ' value="'.$_POST["concerne"].'" ';}elseif(isset($_GET['concerne'])){echo ' value="'.$_GET["concerne"].'" ';}?>/></li> 
        </fieldset>



        <fieldset style="width:170px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;height:70px;">
          <legend>Montant</legend>
            <li id="filtrec1"><select  name="choixv" id="choixv" style="width:60px;padding:8px"   >  <option value="="  <?php if (isset($_POST['choixv'])){ if($_POST['choixv']=="=") echo "selected" ;}elseif (isset($_GET['choixv'])){ if($_GET['choixv']=="=") echo "selected" ;} ?> >=</option>  <option  value=">="  <?php if (isset($_POST['choixv'])){ if($_POST['choixv']==">=") echo "selected" ;}elseif (isset($_GET['choixv'])){ if($_GET['choixv']==">=") echo "selected" ;} ?> >>=</option>  <option  value="<="  <?php if (isset($_POST['choixv'])){ if($_POST['choixv']=="<=") echo "selected" ; }elseif (isset($_GET['choixv'])){ if($_GET['choixv']=="<=") echo "selected" ; }?> ><=</option> </select> <input style="padding:9px;width:70px" name="montant" id="montant" type="text"  onKeyUp="nombre('montant')" autocomplete="off" <?php if(isset($_POST['montant'])){echo ' value="'.$_POST["montant"].'" ';}elseif(isset($_GET['montant'])){echo ' value="'.$_GET["montant"].'" ';}?>/></li> 
        </fieldset>

    </ul>
</div>


<div class="filtre" style="height:74px;padding:4px 10px 20px 10px;background:#fff">
    <ul class="nav">




<script>
$(window).click(function() {
  $('#country_list_id2').hide();
  $('#country_list_idlibelletentreefiltre').hide();
  $('#country_list_id1').hide();
  $('#country_list_idlibelletentree').hide();
  $('#country_list_id').hide();
  $('#country_list_idlibelletentreemodif').hide();

});
</script>



<script>




function defaut()
{       


        <?php if (isset($_GET['ste'])) {?>
          document.getElementById('id_ste').value=<?php echo $_GET['ste'];?>;
        <?php }else{?>
          document.getElementById('id_ste').value=""; 
        <?php }?>

}

</script>
        



        <fieldset style="width:180px;text-align:center;margin:0px;padding:5px 0px 10px 0px;display:inline-block;margin-right:2px;height:65px;">
          <legend>Famille vente</legend>
             <li >
             <select  name="choixf" id="choixf" style="width:170px;padding:8px" >  
             <option value=""  <?php if (isset($_POST['choixf'])){ if($_POST['choixf']=="") echo "selected" ;}elseif (isset($_GET['choixf'])){ if($_GET['choixf']=="") echo "selected" ;} ?> > </option>  

                    <?php

                      $reqf = $bdd->query("SELECT id,famille from famillevente order by famille asc ");
                      while ($famille = $reqf->fetch())
                      {
                    ?> 

                        <option  value="<?php echo $famille['id'] ;?>"  <?php if (isset($_POST['choixf'])){ if($_POST['choixf']==$famille['id']) echo "selected" ;}elseif (isset($_GET['choixf'])){ if($_GET['choixf']==$famille['id']) echo "selected" ;} ?>  > <?php echo $famille['famille'] ;?> </option>

                    <?php
                      }
                    ?>  

              </select>
             </li> 
         </fieldset>



            

        <fieldset style="width:180px;text-align:center;margin:0px;padding:5px 0px 10px 0px;display:inline-block;margin-right:2px;height:65px;">
          <legend>Nature d'entrée</legend>
            <li ><select  name="choixst" id="choixst" style="width:170px;padding:8px;">  
            <option value=""  <?php if (isset($_POST['choixst'])){ if($_POST['choixst']=="") echo "selected" ;}elseif (isset($_GET['choixst'])){ if($_GET['choixst']=="") echo "selected" ;} ?> > </option> 
           
            <option  value="vente"  <?php if (isset($_POST['choixst'])){ if($_POST['choixst']=="vente") echo "selected" ;}elseif (isset($_GET['choixst'])){ if($_GET['choixst']=="vente") echo "selected" ;} ?> >Vente</option>  
            
            <option  value="alimentation de caisse"  <?php if (isset($_POST['choixst'])){ if($_POST['choixst']=="alimentation de caisse") echo "selected" ;}elseif (isset($_GET['choixst'])){ if($_GET['choixst']=="alimentation de caisse") echo "selected" ;} ?> >Alimentation de caisse</option>  

            </select> </li> 
        </fieldset>



        <fieldset style="width:180px;text-align:center;margin:0px;padding:5px 0px 10px 0px;display:inline-block;margin-right:2px;height:65px;">
          <legend>Type alimentation</legend>


            <li ><select  name="choixstype" id="choixstype" style="width:170px;padding:8px;">  
            <option  value="" <?php if (isset($_POST['choixstype'])){ if($_POST['choixstype']=="") echo "selected" ;}elseif (isset($_GET['choixstype'])){ if($_GET['choixstype']=="") echo "selected" ;} ?> > </option>

          <?php

            $req11 = $bdd->query("SELECT id,type from type_alimentation_caisse order by type asc ");


            while ($type_alimentation_caisse = $req11->fetch())
            {

              ?> 

              <option  value="<?php echo $type_alimentation_caisse['id'] ;?>"  <?php if (isset($_POST['choixstype'])){ if($_POST['choixstype']==$type_alimentation_caisse['id']) echo "selected" ;}elseif (isset($_GET['choixstype'])){ if($_GET['choixstype']==$type_alimentation_caisse['id']) echo "selected" ;} ?>   >  <?php echo $type_alimentation_caisse['type'] ;?> </option>

              <?php
              
            }
          ?>  

            </select> </li> 

        </fieldset>



        <fieldset style="width:100px;text-align:center;margin:0px;padding:5px 0px 10px 0px;display:inline-block;margin-right:2px;height:65px;">
          <legend>Justifiée</legend>
            <li ><select  name="choixstvalide" id="choixstvalide" style="width:90px;padding:8px;">  
            <option value=""  <?php if (isset($_POST['choixstvalide'])){ if($_POST['choixstvalide']=="") echo "selected" ;}elseif (isset($_GET['choixstvalide'])){ if($_GET['choixstvalide']=="") echo "selected" ;} ?> > </option> 
           
            <option  value="en cours"  <?php if (isset($_POST['choixstvalide'])){ if($_POST['choixstvalide']=="en cours") echo "selected" ;}elseif (isset($_GET['choixstvalide'])){ if($_GET['choixstvalide']=="en cours") echo "selected" ;} ?> >Non</option>  
            
            <option  value="valide"  <?php if (isset($_POST['choixstvalide'])){ if($_POST['choixstvalide']=="valide") echo "selected" ;}elseif (isset($_GET['choixstvalide'])){ if($_GET['choixstvalide']=="valide") echo "selected" ;} ?> >Oui</option>  

            </select> </li> 
        </fieldset>




        <fieldset style="width:150px;text-align:center;margin:0px;padding:5px 0px 10px 0px;display:inline-block;margin-right:2px;height:65px;">
          <legend>à comptabiliser</legend>
            <li ><select  name="choixstcom" id="choixstcom" style="width:140px;padding:8px;">  
            <option value=""  <?php if (isset($_POST['choixstcom'])){ if($_POST['choixstcom']=="") echo "selected" ;}elseif (isset($_GET['choixstcom'])){ if($_GET['choixstcom']=="") echo "selected" ;} ?> > </option> 
           
            <option  value="oui"  <?php if (isset($_POST['choixstcom'])){ if($_POST['choixstcom']=="oui") echo "selected" ;}elseif (isset($_GET['choixstcom'])){ if($_GET['choixstcom']=="oui") echo "selected" ;} ?> >oui</option>  
            
            <option  value="non"  <?php  if (isset($_POST['choixstcom'])){ if($_POST['choixstcom']=="non") echo "selected" ;}elseif (isset($_GET['choixstcom'])){ if($_GET['choixstcom']=="non") echo "selected" ;} ?> >non</option>  

            </select> </li> 
        </fieldset>




        <fieldset style="width:270px;text-align:center;margin:0px;padding-bottom:11px;display:inline-block;margin-right:2px;height:70px;position:relative;top:-2px">
          <legend>Date opération</legend>
            <li >

            <input style="padding:9px;margin-right:5px;width:105px;padding:6.5px" name="du1" type="date" id="du1" <?php if(isset($_POST['du1'])){echo ' value="'.$_POST["du1"].'" ';}elseif(isset($_GET['du1'])){echo ' value="'.$_GET["du1"].'" ';}?> /><input style="padding:9px;margin-right:2px;width:105px;padding:6.5px" name="au1" type="date" id="au1" <?php if(isset($_POST['au1'])){echo ' value="'.$_POST["au1"].'" ';}elseif(isset($_GET['au1'])){echo ' value="'.$_GET["au1"].'" ';}?> /></li> 
        </fieldset>

        
        <input type="submit" name="filtred" id="filtred" value="Filtrer"  style="width:110px;padding:21px 2px;font-size:18px;position:relative;top:2px;margin-right:4px"/>
 
        </form>
    </ul>
</div>











































  <?php

    $reqcolonne = $bdd->query('SELECT colonneentree FROM users where id='.$_SESSION['idcaisse']);
    $colonneentree_detail = $reqcolonne->fetch();
    $reqcolonne->closecursor(); 

  ?>

<div style="overflow-x: scroll;margin:auto">

<table id="testTable" summary="Code page support in different versions of MS Windows." rules="groups" frame="hsides" border="2" >
        <tr>

            <?php if(strpos($colonneentree_detail['colonneentree'], 'caisse') !== false){ ?>
                <th>Caisse</th>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'caissier') !== false){ ?>
                <th>Caissier</th>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'reference') !== false){ ?>
                <th>Numéro</th>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'dateop') !== false){ ?>
                <th>Date opération</th>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'nature_entree') !== false){ ?>
                <th>Nature</th>
                <th>Famille vente </th>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'type_alimentation') !== false){ ?>
                <th>Type alimentation</th>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'libelle') !== false){ ?>
                <th>Libellé</th>
            <?php }?>


            <th>Montant</th>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'client') !== false){ ?>
                <th>Client</th>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'notes') !== false){ ?>
                <th>Notes </th>                                                  
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'acomptabilise') !== false){ ?>
                <th>à compta.</th>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'justifie') !== false){ ?>
                <th>Justifiée</th>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'documents') !== false){ ?>
                <th>Documents</th>           
            <?php }?>










                <?php if( $user_detail['boncaisse']=="oui" ){ ?>
                <th>Bon d'entrée</th>
                <?php }?>


                <?php if( ($user_detail['modification']=="oui" or $user_detail['supression']=="oui") and (!isset($_POST['deleteditems']) and !isset($_GET['deleted'])) ){ ?>
                <th>Action</th>
                <?php }?>


                <?php if( (isset($_POST['deleteditems']) or isset($_GET['deleted'])) ){ ?>
                <th>Supprimée par</th>
                <?php }?>

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
        $societe=" and idsuccursale in(".$steuser.") ";
      } 
      else
      {
        $societe=" and idsuccursale in(-1) ";
      }



if(isset($_POST['filtred']))
{

        $champs=" ";
        $filtre="?filtre=ok&";


    if(!empty($_POST['ste']))
    {

      $succursale11 = $bdd->query("SELECT id FROM succursale WHERE succursale='".trim($_POST['ste'])."'");
      $succursale11_detail = $succursale11->fetch();

      if(!empty($succursale11_detail['id']))
        $champs=$champs." AND idsuccursale=".$succursale11_detail['id']." ";
      else
        $champs=$champs." AND idsuccursale=-1 ";

      $filtre=$filtre."ste=".$_POST['ste']."&";
    }


     if(!empty($_POST['caissier']))
     {
        $champs=$champs." AND caissier like '%".$_POST['caissier']."%' ";
        $filtre=$filtre."caissier=".$_POST['caissier']."&";
     }


     if(!empty($_POST['concerne']))
     {
        $champs=$champs." AND concerne like '%".$_POST['concerne']."%' ";
        $filtre=$filtre."concerne=".$_POST['concerne']."&";
     }



     if(!empty($_POST['client']))
     {
        $champs=$champs." AND client='".$_POST['client']."' ";
        $filtre=$filtre."client=".$_POST['client']."&";
     }

     if(!empty($_POST['libelle']))
     {
        $champs=$champs." AND libelle like '%".$_POST['libelle']."%' ";
        $filtre=$filtre."libelle=".$_POST['libelle']."&";
     }


     if(!empty($_POST['ref']))
     {
        $champs=$champs." AND ref='".$_POST['ref']."' ";
        $filtre=$filtre."ref=".$_POST['ref']."&";
     }


     if(!empty($_POST['montant']))
     {
        $champs=$champs." AND  CAST(montant AS DECIMAL(10,2)) ".$_POST['choixv']."".floatval($_POST['montant'])."";   
        $filtre=$filtre."choixv=".$_POST['choixv']."&";  
        $filtre=$filtre."montant=".$_POST['montant']."&";  
     }


     if(!empty($_POST['choixst']))
     {
        if($_POST['choixst']=="alimentation de caisse")
        {
          $champs=$champs." AND typeentree='alimentation de caisse'";
          $filtre=$filtre."choixst=".$_POST['choixst']."&"; 
        }
        elseif($_POST['choixst']=="vente")
        {
          $champs=$champs." AND typeentree='vente'";
          $filtre=$filtre."choixst=".$_POST['choixst']."&"; 
        }
        

      }
      
     if(!empty($_POST['choixf']))
     {
        $champs=$champs." AND idfamillevente=".$_POST['choixf']." ";
        $filtre=$filtre."choixf=".$_POST['choixf']."&"; 
     }



     if(!empty($_POST['choixstype']))
     {
 
        $champs=$champs." AND type_alimentation='".$_POST['choixstype']."'";
        $filtre=$filtre."choixstype=".$_POST['choixstype']."&";
     }




     if(!empty($_POST['choixstvalide']))
     {
        if($_POST['choixstvalide']=="en cours")
        {
          $champs=$champs." AND valide='non'";
          $filtre=$filtre."choixstvalide=".$_POST['choixstvalide']."&"; 
        }
        elseif($_POST['choixstvalide']=="valide")
        {
          $champs=$champs." AND valide='oui'";
          $filtre=$filtre."choixstvalide=".$_POST['choixstvalide']."&"; 
        }

      }


     if(!empty($_POST['choixstcom']))
     {
        if($_POST['choixstcom']=="oui")
        {
          $champs=$champs." AND acomptabilise='oui'";
          $filtre=$filtre."choixstcom=".$_POST['choixstcom']."&";
        }
        elseif($_POST['choixstcom']=="non")
        {
          $champs=$champs." AND acomptabilise='non'";
          $filtre=$filtre."choixstcom=".$_POST['choixstcom']."&";
        }

      }



     if( !empty($_POST['du1']) and !empty($_POST['au1']) )
     {
        $champs=$champs." AND dateop BETWEEN '".$_POST['du1']."' AND '".$_POST['au1']."'";
        $filtre=$filtre."du1=".$_POST['du1']."&";
        $filtre=$filtre."au1=".$_POST['au1']."&";
     }


     if(isset($_POST['deleteditems']))
     {
        $champs=$champs." AND deleted='oui' ";
        $filtre=$filtre."deleted=oui&"; 
     }
     else
     {
         $champs=$champs." AND deleted='non' ";
     }



     //echo $champs;

     $req = $bdd->query("SELECT * from caisse where  type='entree'  ".$champs."  ".$societe." order by dateop desc,id desc ");



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
       $champs=" ";



    if(!empty($_GET['ste']))
    {

      $succursale11 = $bdd->query("SELECT id FROM succursale WHERE succursale='".trim($_GET['ste'])."'");
      $succursale11_detail = $succursale11->fetch();

      if(!empty($succursale11_detail['id']))
        $champs=$champs." AND idsuccursale=".$succursale11_detail['id']." ";
      else
        $champs=$champs." AND idsuccursale=-1";
    }



     if(!empty($_GET['caissier']))
     {
        $champs=$champs." AND caissier like '%".$_GET['caissier']."%' ";
     }

     if(!empty($_GET['concerne']))
     {
        $champs=$champs." AND concerne like '%".$_GET['concerne']."%' ";
     }

     if(!empty($_GET['client']))
     {
        $champs=$champs." AND client='".$_GET['client']."' ";
     }

     if(!empty($_GET['libelle']))
     {
        $champs=$champs." AND libelle like '%".$_GET['libelle']."%' ";
     }


     if(!empty($_GET['ref']))
     {
        $champs=$champs." AND ref='".$_GET['ref']."' ";
     }


     if(!empty($_GET['montant']))
     {
        $champs=$champs." AND  CAST(montant AS DECIMAL(10,2)) ".$_GET['choixv']."".floatval($_GET['montant'])."";   

     }


     if(!empty($_GET['choixst']))
     {
        if($_GET['choixst']=="alimentation de caisse")
        {
          $champs=$champs." AND typeentree='alimentation de caisse'";
        }
        elseif($_GET['choixst']=="vente")
        {
          $champs=$champs." AND typeentree='vente'";
        }
        

      }


     if(!empty($_GET['choixf']))
     {
        $champs=$champs." AND idfamillevente=".$_GET['choixf']." ";
     }



     if(!empty($_GET['choixstype']))
     {
 
        $champs=$champs." AND type_alimentation='".$_GET['choixstype']."'";
     }




     if(!empty($_GET['choixstvalide']))
     {
        if($_GET['choixstvalide']=="en cours")
        {
          $champs=$champs." AND valide='non'";
        }
        elseif($_GET['choixstvalide']=="valide")
        {
          $champs=$champs." AND valide='oui'";
        }

      }


     if(!empty($_GET['choixstcom']))
     {
        if($_GET['choixstcom']=="oui")
        {
          $champs=$champs." AND acomptabilise='oui'";
        }
        elseif($_GET['choixstcom']=="non")
        {
          $champs=$champs." AND acomptabilise='non'";
        }

      }


     if( !empty($_GET['du1']) and !empty($_GET['au1']) )
     {
        $champs=$champs." AND dateop BETWEEN '".$_GET['du1']."' AND '".$_GET['au1']."'";
     }


     if(isset($_GET['deleted']))
     {
        $champs=$champs." AND deleted='oui' ";
     }
     else
     {
         $champs=$champs." AND deleted='non' ";
     }



     //echo $champs;

     $req = $bdd->query("SELECT * from caisse where  type='entree'  ".$champs."  ".$societe." order by dateop desc,id desc  ");

}
else
{

    $req = $bdd->query("SELECT * from caisse where  deleted='non' and  type='entree' ".$societe." order by dateop desc,id desc  LIMIT ".$sf.",".$nb);

}









$count=0;
$total=0;


                 
function changedate($var)
{
$tab = explode("-",$var);
$nouvelledate = $tab[2]."/".$tab[1]."/".$tab[0];
return $nouvelledate;
}
                    

while ($cheque = $req->fetch())
{
  $count=$count+1;
  $total=$total + $cheque["montant"];

?>       


    <tr>


    <?php
    $succursale11 = $bdd->query("SELECT succursale,datelastcloture FROM succursale WHERE id=".$cheque['idsuccursale']);
    $succursale11_detail = $succursale11->fetch();


$type_alimentation = $bdd->prepare("SELECT type FROM type_alimentation_caisse WHERE id = :id");
$type_alimentation->execute(['id' => $cheque['type_alimentation']]);
$type_alimentation_detail = $type_alimentation->fetch(PDO::FETCH_ASSOC);


    $famille11 = $bdd->query("SELECT famille FROM famillevente WHERE id=".$cheque['idfamillevente']);
    $famille11_detail = $famille11->fetch();
    $famillevente = "";
    if(isset($famille11_detail['famille']))
        $famillevente = $famille11_detail['famille'];

    ?>





            <?php if(strpos($colonneentree_detail['colonneentree'], 'caisse') !== false){ ?>
                <td ><?php echo ucfirst($succursale11_detail['succursale']) ?></td>
            <?php }?>

            <?php if(strpos($colonneentree_detail['colonneentree'], 'caissier') !== false){ ?>
                <td style="min-width:80px"> <?php echo $cheque['caissier'] ?></td>
            <?php }?>

            <?php if(strpos($colonneentree_detail['colonneentree'], 'reference') !== false){ ?>
                <td style="min-width:110px" ><?php echo $cheque['ref'] ?></td>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'dateop') !== false){ ?>
                <td ><?php echo changedate($cheque['dateop']) ?></td>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'nature_entree') !== false){ ?>
                <td ><?php echo ucfirst($cheque['typeentree']); ?></td>
                <td ><?php echo $famillevente ?></td>

            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'type_alimentation') !== false){ ?>
                <td>
                    <?php 
                        echo $type_alimentation_detail && isset($type_alimentation_detail['type']) 
                            ? ucfirst($type_alimentation_detail['type']) 
                            : '';
                    ?>
                </td>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'libelle') !== false){ ?>
                <td style="min-width:120px"  ><?php echo ucfirst($cheque['libelle']); ?></td>
            <?php }?>


            <td style="min-width:100px;text-align:right;font-weight:bold;font-size:13px" ><?php if($cheque['montant']!="") echo  number_format( $cheque['montant'], 2, ',', ' '); ?></td>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'client') !== false){ ?>
                <td style="min-width:100px" ><?php echo ucfirst($cheque['client']) ?></td>
            <?php }?>


            <?php if(strpos($colonneentree_detail['colonneentree'], 'notes') !== false){ ?>
                <td style="min-width:120px;white-space: pre-wrap;" ><?php echo $cheque['concerne'] ?></td>
            <?php }?>


























            <?php if(strpos($colonneentree_detail['colonneentree'], 'acomptabilise') !== false){ ?>

                <?php if( $cheque['acomptabilise']=="oui" ){ ?>
                <td>
                <?php if($user_detail['acomptabilise']=='oui') {?>

                      <?php
                      if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']) )
                      {
                      ?>
                        <a href="#" onclick="location.href=location.href.substring(0, location.href.indexOf('#'))+'&idacomptabilise=<?php echo $cheque['id']; ?>&amp;acomptabilise=non';" style="background:#00cd00;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >oui</a>
                      <?php
                      }
                      else
                      {
                      ?>
                        <a href="#" onclick="location.href='?idacomptabilise=<?php echo $cheque['id']; ?>&amp;acomptabilise=non';" style="background:#00cd00;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >oui</a>
                      <?php 
                      }
                      ?>

                <?php }else {?>
                  oui
                <?php }?>

                </td>
                <?php }else { ?>
                <td >
                <?php if($user_detail['acomptabilise']=='oui') {?>
                      <?php
                      if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']) )
                      {
                      ?>
                      <a href="#" onclick="location.href=location.href.substring(0, location.href.indexOf('#'))+'&idacomptabilise=<?php echo $cheque['id']; ?>&amp;acomptabilise=oui';" style="background:#888;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >non</a>
                      <?php
                      }
                      else
                      {
                      ?>
                      <a href="#" onclick="location.href='?idacomptabilise=<?php echo $cheque['id']; ?>&amp;acomptabilise=oui';" style="background:#888;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >non</a>
                      <?php 
                      }
                      ?>

                <?php }else {?>
                  non
                <?php }?>
                
                </td>
                <?php }?>
          <?php }?>
        







            <?php if(strpos($colonneentree_detail['colonneentree'], 'justifie') !== false){ ?>

                <?php if( $cheque['valide']=="oui" ){ ?>
                <td>
                <?php if($user_detail['justification']=='oui') {?>

                    <?php
                    if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']) )
                    {
                    ?>
                    <a href="#" onclick="location.href=location.href.substring(0, location.href.indexOf('#'))+'&idvalide=<?php echo $cheque['id']; ?>&amp;valide=non';" style="background:#00cd00;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >oui</a>
                    <?php
                    }
                    else
                    {
                    ?>

                    <a href="#" onclick="location.href='?idvalide=<?php echo $cheque['id']; ?>&amp;valide=non';" style="background:#00cd00;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >oui</a>
                    <?php 
                    }
                    ?>

                <?php }else {?>
                  oui
                <?php }?>
                </td>
                <?php }else { ?>
                <td >
                <?php if($user_detail['justification']=='oui') {?>

                <?php
                if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']) )
                {
                ?>
                <a href="#" onclick="location.href=location.href.substring(0, location.href.indexOf('#'))+'&idvalide=<?php echo $cheque['id']; ?>&amp;valide=oui';" style="background:#888;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >non</a>

                <?php
                }
                else
                {
                ?>
                <a href="#" onclick="location.href='?idvalide=<?php echo $cheque['id']; ?>&amp;valide=oui';" style="background:#888;color:#FFF;font-weight:bold;padding:0px 20px;border-radius:4px;margin:4px;cursor:pointer" >non</a>
                <?php 
                }
                ?>

                <?php }else {?>
                  non
                <?php }?>

                </td>
                <?php }?>
            <?php }?>
    







    <?php if(strpos($colonneentree_detail['colonneentree'], 'documents') !== false){ ?>

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



                <?php if( $user_detail['editdocument']=="oui" ){ ?>
                <td style="font-size:16px;color:#00cd00;cursor:pointer"> 
                  <form action="entree.php" method="post" enctype="multipart/form-data" name="importdoc" id="importdoc<?php echo $cheque['id'] ?>" >
                    <input type="file" name="img1"  id="img1<?php echo $cheque['id'] ?>" style="display: none;" onchange="inmportdoc(<?php echo $cheque['id'] ?>)" /> 
                    <input  type="hidden" name="pagindoc" value="<?php if(isset($_GET['sf']))  echo $_GET['sf']; ?>" />
                    <input  type="hidden" name="stedoc" value="<?php if(isset($_GET['ste']))  echo $_GET['ste']; ?>" />
                    <input  type="hidden" name="currentlink" id="currentlink<?php echo $cheque['id'] ?>" value="" />

                    <i style="color:#14B214" class="fa fa-plus-square" onclick="document.getElementById('currentlink<?php echo $cheque['id'] ?>').value=location.href;document.getElementById('img1<?php echo $cheque['id'] ?>').click();" > </i> 
                    <input type="hidden"  name="idcaisse" value="<?php echo $cheque['id'] ?>" >
                  </form>
                  <?php
                  $reqdoc = $bdd->query("SELECT urldoc,id from documents where idcaisse='".$cheque['id']."'");
                  while ($urldoc = $reqdoc->fetch())
                  {
                  ?>


                    <i onclick="docurlhref(<?php echo $urldoc['id'].',\''.$urldoc['urldoc'].'\'' ?>)" id="<?php echo $urldoc['urldoc'] ?>" class="fa fa-image"></i>


                  <?php
                  }
                  ?>
                </td>    

                
                <?php }else { ?>
                <td style="font-size:16px;color:#00cd00;cursor:pointer"> 

                  <i style="color:#888" class="fa fa-plus-square" onclick="document.getElementById('img1<?php echo $cheque['id'] ?>').click();" > </i> 
  
                  <?php
                  $reqdoc = $bdd->query("SELECT urldoc,id from documents where idcaisse='".$cheque['id']."'");
                  while ($urldoc = $reqdoc->fetch())
                  {
                  ?>
                  <i onclick="docurlhref(<?php echo $urldoc['id'].',\''.$urldoc['urldoc'].'\'' ?>)" id="<?php echo $urldoc['urldoc'] ?>" class="fa fa-image"></i>
                  <?php
                  }
                  ?>
                </td>                  
                <?php } ?>
        <?php } ?>





                <?php if( $user_detail['boncaisse']=="oui" )
                      {
                        if($cheque['type_alimentation']!="5" )
                        {
                ?>
                <td> <a style="min-width:110px" href="boncaisse.php?type=entree&id=<?php echo $cheque['id'] ?>" id="passer"  target="_blank"> <i class="fa fa-print"></i> Bon  d'entrée</a></td> 
                <?php 
                        }
                        else
                        {
                ?>
                <td> <a style="min-width:125px" href="boncaisse.php?type=entree&id=<?php echo $cheque['id'] ?>" id="passer"  target="_blank"> <i class="fa fa-print"></i> Mise à disposition</a></td> 

                <?php
                        }
                ?>
                <?php 
                      }
                ?>

<?php if( (isset($_POST['deleteditems']) or isset($_GET['deleted'])) ){ ?>
      <td style="min-width:100px;color:red;font-weight:bold" >
        <?php echo $cheque['deleter']; ?>
      </td>
<?php }?>


<?php if($succursale11_detail['datelastcloture'] < $cheque['dateop']){ ?>

<?php if( ($user_detail['modification']=="oui" or $user_detail['supression']=="oui") and $cheque['deleted']=='non' ){ ?>
 <td style="min-width:80px" >
                <?php if( $user_detail['modification']=="oui" ){ ?>

        <script>

        currenturl=location.href;
        urlhref="";

        function modifurlhref(id)
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
              urlhref=currenturl+"&idc="+id+"&modif=ok#modif"; 
            }
            else if(currenturl.indexOf('#ok') !== -1)
            {
              currenturl=currenturl.substring(0, currenturl.indexOf('#ok'));
              urlhref=currenturl+"&idc="+id+"&modif=ok#modif";              
            }
            else
            {
              if(currenturl.indexOf('#') !== -1)
              {
                 currenturl=currenturl.substring(0, currenturl.indexOf('#'));             
              }
              urlhref=currenturl+"&idc="+id+"&modif=ok#modif";              
            }
          }
          else
          {
            if(currenturl.indexOf('#') !== -1)
              {
                 currenturl=currenturl.substring(0, currenturl.indexOf('#'));             
              }
            urlhref="?<?php if(isset($_GET['sf']))  echo 'sf='.$_GET['sf'].'&'; ?><?php if(isset($_GET['ste'])) echo 'ste='.$_GET['ste'].'&'; ?><?php if(isset($_GET['compte'])) echo 'compte='.$_GET['compte'].'&'; ?>idc="+id+"&modif=ok#modif";
          }

          location.href=urlhref;
        }

        </script>


               <a  onclick="modifurlhref(<?php echo $cheque['id'] ; ?>)" id="valactive" style="cursor:pointer" ><i class="fa fa-pencil-square-o"></i> Modifier</a>



                <?php }?>


                <?php if( $user_detail['supression']=="oui" ){ ?>
                <a href="#" id="supactive" onclick="document.getElementById('<?php echo $cheque['id']; ?>c').style.display='block';" ><i class="fa fa-times"></i> Supprimer</a>
                                        <span class="del" id="<?php echo $cheque['id']; ?>c" style="width:100px">  


                                        <?php
                                        if(isset($_GET['filtre']) or isset($_GET['sf']) or isset($_POST['filtred']) )
                                        {
                                        ?>
                                            <a href="#" onclick="location.href=location.href.substring(0, location.href.indexOf('#'))+'&iddelet=<?php echo $cheque['id']; ?>&amp;sup=ok';"  style="display:inline;" >Oui</a>
                                        <?php
                                        }
                                        else
                                        {
                                        ?>
                                            <a href="#" onclick="location.href='?iddelet=<?php echo $cheque['id']; ?>&amp;sup=ok';"  style="display:inline;" >Oui</a>
                                        <?php 
                                        }
                                        ?>





                                            &nbsp;&nbsp;-&nbsp;
                                            <a style="cursor:pointer" onclick="document.getElementById('<?php echo $cheque['id']; ?>c').style.display='none';" style="display:inline;" >Non</a>
                                        </span>
                
                <?php }?>
                </td>

<?php }?>
<?php }else{?>
  <td><i style="color:#858585;font-size:14px">clôturée</i></td>
<?php }?>



        </tr>
<?php 
}
$req->closecursor();
?> 


</table>
</div>
<?php 
echo "<p style='width:1230px;margin:auto'>* NB entrées : <strong style='color:#00BA84'>".$count."</strong></p>";
echo "<p style='width:1230px;margin:auto'>* Total entrées : <strong style='color:#00BA84'>".number_format($total, 2, ',', ' ')." DH</strong></p>";
?> 


      <?php
      if($user_detail['exportexcel']=='oui')
      {
      ?>
      <div style="width:1230px;margin:auto;margin-top:10px">
<a href="#" style="padding:5px 10px" id="export" onclick="tableToExcel('testTable', 'entrées');">Exporter en excel-csv ..</a>
      </div>
      </br>
      <?php
      }
      ?>




<!-- Pagination PHP ------------------------------------------------------------------------------------------>




        <?php if ( !isset($_POST['filtred']) and !isset($_GET['filtre'])) {?>
                        
                <ul id="nav">
                    <?php


                            $societe="";

                            if($user_detail['ste']<>"")
                            {
                              $societe=" and idsuccursale in(".$steuser.")";
                            } 
                            else
                            {
                              $societe=" and idsuccursale in(-1)";
                            }

                          $req = $bdd->query("SELECT COUNT(*) AS nb FROM caisse where  deleted='non' and type='entree' ".$societe );

                        

                        $m = 'entree.php?nb='.$nb.'&amp;';
                        

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












<!------------------------------Effectuer une entrée ------------------------------>

<div id="addentree" class="modalDialog">

  <div class="popup">
   
    <h1>Effectuer une entrée </h1>
<form action="" method="post">
      <div class="cont">


        <fieldset>
          <legend>Caisse</legend>
            <select required name="Succursaleadd" id="Succursaleadd" style="margin:10px 0px" onchange="soldecaisse(this)" >
              <option  value=""></option>
          <?php
            $req1 = $bdd->query("SELECT id,succursale from succursale where etat='encours' order by succursale asc ");
            while ($ste1 = $req1->fetch())
            {

              // total especes entree all succursale
              $totalespeceall = $bdd->query("SELECT sum(montant) as totalespeceall from caisse  where  deleted='non' and   type='entree'  and idsuccursale=".$ste1['id']);
              $totalespecealldetail = $totalespeceall ->fetch();
              $totalespeceall->closecursor();


              // total sortie all succursale
              $totalsortieall = $bdd->query("SELECT sum(montant) as totalsortieall from caisse  where  deleted='non' and   type='sortie' and idsuccursale=".$ste1['id']);
              $totalsortieall_detail = $totalsortieall ->fetch();
              $totalsortieall->closecursor();


              $totalcaisse = $totalespecealldetail['totalespeceall'] - $totalsortieall_detail['totalsortieall'];

               
              
              if(stristr($steuser, "'".$ste1['id']."'"))
              {
              ?> 
              <option  value="<?php echo $ste1['id'] ;?>">  <?php echo $ste1['succursale'] ;?> </option>

              <?php
              }
            }
          ?>  

             </select>



          <?php

          if($user_detail['affichersolde']=='oui'){
            $req1 = $bdd->query("SELECT id,succursale from succursale where etat='encours' order by succursale asc ");
            while ($ste1 = $req1->fetch())
            {


              // total especes entree all succursale
              $totalespeceall = $bdd->query("SELECT sum(montant) as totalespeceall from caisse  where deleted='non' and   type='entree'  and idsuccursale=".$ste1['id']);
              $totalespecealldetail = $totalespeceall ->fetch();
              $totalespeceall->closecursor();


              // total sortie all succursale
              $totalsortieall = $bdd->query("SELECT sum(montant) as totalsortieall from caisse  where deleted='non' and   type='sortie' and idsuccursale=".$ste1['id']);
              $totalsortieall_detail = $totalsortieall ->fetch();
              $totalsortieall->closecursor();


              $totalcaisse = $totalespecealldetail['totalespeceall'] - $totalsortieall_detail['totalsortieall'];

               

              if(stristr($steuser, "'".$ste1['id']."'"))
              {
              ?> 
              <p id="<?php echo $ste1['id'].'c'; ?>" style="margin-top:10px;margin-bottom:15px;display:none">Solde caisse: <span style="color:#00BA84;font-weight:bold"><?php echo number_format( $totalcaisse, 2, ',', ' '); ?> DHs</span> </p>

              <?php
              }
            }
          }
          ?>  


        </fieldset>


<script>

function soldecaisse(val)
{
   var soldecaisse = val.value;


          <?php

            $req1 = $bdd->query("SELECT id,succursale from succursale where etat='encours' order by succursale asc ");
            while ($ste1 = $req1->fetch())
            {


              // total especes entree all succursale
              $totalespeceall = $bdd->query("SELECT sum(montant) as totalespeceall from caisse  where  deleted='non' and  type='entree'  and idsuccursale=".$ste1['id']);
              $totalespecealldetail = $totalespeceall ->fetch();
              $totalespeceall->closecursor();


              // total sortie all succursale
              $totalsortieall = $bdd->query("SELECT sum(montant) as totalsortieall from caisse  where  deleted='non' and  type='sortie' and idsuccursale=".$ste1['id']);
              $totalsortieall_detail = $totalsortieall ->fetch();
              $totalsortieall->closecursor();


              $totalcaisse = $totalespecealldetail['totalespeceall'] - $totalsortieall_detail['totalsortieall'];

               

              if(stristr($steuser, "'".$ste1['id']."'"))
              {
              ?> 

                document.getElementById(<?php echo $ste1['id']; ?>+'c').style.display = "none";

              <?php
              }
            }
          ?>  

    document.getElementById(soldecaisse+'c').style.display = "block";
}



function typeentreef()
{



  if(document.getElementById('typeentreeadd').value == "vente")
  {
    document.getElementById("country_id1").style.display = "";
    document.getElementById("clientadd1").style.display = "";
    document.getElementById("country_id1").required = true;


    document.getElementById("familleadd1").style.display = "";
    document.getElementById("familleadd").required = true;

    document.getElementById("type_alimentationdd1").style.display = "none";
    document.getElementById("type_alimentationdd").style.display = "none";
    document.getElementById("type_alimentationdd").required = false;
    document.getElementById("type_alimentationdd").value = "";


      document.getElementById("mad_fields").style.display = "none";
      document.getElementById("country_mad_cin").value = "";
      document.getElementById("country_mad_beneficiaire").value = "";
      document.getElementById("country_mad_compte").value = "";
      document.getElementById("country_mad_agence").value = "";
      
  }
  else
  {
    document.getElementById("country_id1").style.display = "none";
    document.getElementById("clientadd1").style.display = "none";
    document.getElementById("country_id1").required = false;
    document.getElementById("country_id1").value = "";


    document.getElementById("familleadd1").style.display = "none";
    document.getElementById("familleadd").required = false;
    document.getElementById("familleadd").value = "";


    document.getElementById("type_alimentationdd1").style.display = "";
    document.getElementById("type_alimentationdd").style.display = "";
    document.getElementById("type_alimentationdd").required = true;

  }

}




function typemad()
{

    if(document.getElementById('type_alimentationdd').value == 5)
    {
      document.getElementById("mad_fields").style.display = "block";
    }
    else
    {
      document.getElementById("mad_fields").style.display = "none";
      document.getElementById("country_mad_cin").value = "";
      document.getElementById("country_mad_beneficiaire").value = "";
      document.getElementById("country_mad_compte").value = "";
      document.getElementById("country_mad_agence").value = "";
    }

}


function nombre(id)
{
    var source = document.getElementById(id);

    // Remplacer toutes les virgules par des points
    source.value = source.value.replace(/,/g, '.');

    // Autoriser uniquement chiffres, +, - et .
    source.value = source.value.replace(/[^0-9.+-]/g, '');

    // Garder un seul point
    var p = source.value.indexOf('.');
    if (p != -1)
    {
        source.value = source.value.substring(0, p + 1) +
                       source.value.substring(p + 1).replace(/\./g, '');
    }

    // Supprimer le point s'il est le premier caractère
    if (source.value.charAt(0) == '.')
    {
        source.value = source.value.substring(1);
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








      <?php
      if($user_detail['editdateop']=='oui')
      {
      ?>
        <fieldset>
          <legend>Date d'opération</legend>
          <p><input required type="date" name="dateopadd"  value="<?php echo date('Y-m-d', strtotime(date('Y-m-d')));?>"></p>        
        </fieldset>

      <?php
      }
      else
      {
      ?>
          <input required type="hidden" name="dateopadd"  value="<?php echo date('Y-m-d', strtotime(date('Y-m-d')));?>">    

      <?php
      }
      ?>
      


        <fieldset >
          <legend>Nature d'entrée</legend>
            <select required name="typeentreeadd" id="typeentreeadd" onchange="typeentreef()" style="margin:10px 0px" >
              <option  value="vente">Vente </option>
              <option  value="alimentation de caisse">Alimentation de caisse </option>
            </select>
        </fieldset>



        <fieldset style="display:none"   id="type_alimentationdd1" >
          <legend>Type alimentation caisse</legend>
            <select  name="type_alimentationdd" id="type_alimentationdd" style="margin:10px 0px"  onchange="typemad()" >
            <option  value=""> </option>

              <?php

                $req11 = $bdd->query("SELECT id,type from type_alimentation_caisse order by type asc ");

                while ($type_alimentation_caisse = $req11->fetch())
                {

                  ?> 

                  <option  value="<?php echo $type_alimentation_caisse['id'] ;?>"><?php echo $type_alimentation_caisse['type'] ;?></option>

                  <?php
                  
                }

              ?>

             </select>
        </fieldset>



        <fieldset   id="familleadd1" >
          <legend >Famille vente</legend>
             <select  name="familleadd" id="familleadd" style="margin:5px 0px 10px 0px"  >  
             <option value="" > </option>  

                    <?php

                      $reqf = $bdd->query("SELECT id,famille from famillevente order by famille asc ");
                      while ($famille = $reqf->fetch())
                      {
                    ?> 

                        <option  value="<?php echo $famille['id'] ;?>"  > <?php echo $famille['famille'] ;?> </option>

                    <?php
                      }
                    ?>  

              </select>
        </fieldset>




        <fieldset id="clientadd1" >
          <legend>Client</legend>
          <p> <input  required type="text"  name="clientadd" id="country_id1" onkeyup="autocomplet1();apost('country_id1')"  autocomplete="off" onkeyup="apost('clientadd')" value="">
          <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_id1"></ul> </p>
        </fieldset>






        <div id="mad_fields"  style="display:none" >
          <!-- MAD -->
          <fieldset id="mad_agence" >
            <legend>Banque et agence</legend>
            <p> <input   type="text"  name="mad_agence" id="country_mad_agence" onkeyup="autocomplet_mad_agence();apost('country_mad_agence')"  autocomplete="off" onkeyup="apost('mad_agence')" value="">
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_mad_agence"></ul> </p>
          </fieldset>

          <fieldset id="mad_compte" >
            <legend>N° de compte à débiter(RIB)</legend>
            <p> <input   pattern="\s*(\S\s*){24}" title="le RIB doit avoir 24 caractères." type="text"  name="mad_compte" id="country_mad_compte" onkeyup="autocomplet_mad_compte();apost('country_mad_compte')"  autocomplete="off" onkeyup="apost('mad_compte')" value="">
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_mad_compte"></ul> </p>
          </fieldset>

          <fieldset id="mad_beneficiaire" >
            <legend>Nom Bénéficiaire</legend>
            <p> <input   type="text"  name="mad_beneficiaire" id="country_mad_beneficiaire" onkeyup="autocomplet_mad_beneficiaire();apost('country_mad_beneficiaire')"  autocomplete="off" onkeyup="apost('mad_beneficiaire')" value="">
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_mad_beneficiaire"></ul> </p>
          </fieldset>

          <fieldset id="mad_cin" >
            <legend>CIN Bénéficiaire</legend>
            <p> <input   type="text"  name="mad_cin" id="country_mad_cin" onkeyup="autocomplet_mad_cin();apost('country_mad_cin')"  autocomplete="off" onkeyup="apost('mad_cin')" value="">
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_mad_cin"></ul> </p>
          </fieldset>
          <!-- MAD -->
        </div>
















        <fieldset>
          <legend>Libellé</legend>
          <p> <input  required type="text"  name="libelleadd" id="country_idlibelletentree" autocomplete="off" value=""  onKeyUp="autocompletlibelletentree();apost('country_idlibelletentree')" >
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_idlibelletentree"></ul></p>
        </fieldset>


        <fieldset>
          <legend>Montant</legend>
          <p> <input  onpaste="return false;" required type="text"  name="montantadd" id="montantadd" autocomplete="off" value=""  onKeyUp="nombre('montantadd')" ></p>
        </fieldset>


        <fieldset id="concerneadd1" >
          <legend>Notes</legend>
          <p> 
          <textarea rows="8" name="concerneadd" id="concerneadd" onKeyUp="apost('concerneadd')"  ></textarea>
          </p>
        </fieldset>


        <p><input type="submit" name="add" value="Ajouter"></p>
      </div>
    </form>


  <a href="#" id="x" title="quitter">x</a>
  </div>
       
    
</div>






<!------------------------------popup  documents option ------------------------------>



<div id="docoption" class="modalDialog">

  <div class="popup">
   
    <h1>Document</h1>
    <form action="" method="post"  >
    <div class="cont" style="" >

        <a onclick="popupCenter('documents/<?php echo $_GET["urldoc"] ?>', 'Ajbon',500,500);" style="border:1px solid #14B214;border-radius: 3px;margin:30px;color:#14B214;font-weight:bold;display:block;text-align:center;padding:10px;font-size:18px;background:#FEFEFE;cursor:pointer"  > <i class="fa fa-eye"></i> Afficher</a></p>

        <?php if( $user_detail['editdocument']=="oui" ){ ?>
        <a onclick="document.getElementById('deletedoc').style.display='block';" style="margin:30px;color:red;font-weight:bold;display:block;text-align:center;padding:10px;font-size:14px;cursor:pointer"  > <i class="fa fa-trash"></i> Supprimer</a></p>
        <?php } ?>

          <div class="del" style="width:277px;position:relative;bottom:10px;left:30px;text-align:center;color:red" id="deletedoc" >  
              <a  onclick="location.href=location.href.substring(0, location.href.indexOf('#docoption'))+'&iddocdelet=<?php echo $_GET['idc'];?>'"  style="display:inline;cursor:pointer" >Oui</a>&nbsp;&nbsp;-&nbsp;
              <a  onclick="document.getElementById('deletedoc').style.display='none';" style="display:inline;cursor:pointer" >Non</a>
          </div>

    </div>
    </form>

                  <script>
                    function inmportdoc(idc)
                    {
                        if( document.getElementById("img1"+idc).files.length != 0 ){
                            document.getElementById("importdoc"+idc).submit();
                        }
                    }

                    function popupCenter(url, title, w, h) {
                      var left = (screen.width/2)-(w/2);
                      var top = (screen.height/2)-(h/2);
                      return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                    }
                  </script>


  <a href="#" id="x" title="quitter">x</a>
  </div>
       
    
</div>




<!------------------------------popup  detail Affichage ------------------------------>
<div id="colonneentree" class="modalDialog" >

  <div class="popup" style="width:400px" >
 
  <?php

    $reqcolonne = $bdd->query('SELECT colonneentree FROM users where id='.$_SESSION['idcaisse']);
    $colonneentree_detail = $reqcolonne->fetch();
    $reqcolonne->closecursor(); 

  ?>

  <form method="post" id="colonneentree" action=""> 
  
    <h1>Personnaliser l'affichage des colonnes...</h1>
    </br>
    <div class="cont">
      <table style="width:100%">

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'caisse') !== false) echo "checked"; ?> type="checkbox" id="caisse" name="caisse">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Caisse </td>
        </tr>




        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'caissier') !== false) echo "checked"; ?> type="checkbox" id="caissier" name="caissier">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Caissier </td>
        </tr>



        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'reference') !== false) echo "checked"; ?>  type="checkbox" id="reference"  name="reference">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Numéro </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'dateop') !== false) echo "checked"; ?>  type="checkbox" id="dateop" name="dateop">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Date opération </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'libelle') !== false) echo "checked"; ?>  type="checkbox" id="libelle" name="libelle">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Libellé </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'nature_entree') !== false) echo "checked"; ?>  type="checkbox" id="nature_entree" name="nature_entree">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Nature entrée </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'type_alimentation') !== false) echo "checked"; ?>  type="checkbox" id="type_alimentation" name="type_alimentation">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Type alimentation </td>
        </tr>


        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'client') !== false) echo "checked"; ?>  type="checkbox" id="client" name="client">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Client </td>
        </tr>


        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'notes') !== false) echo "checked"; ?>  type="checkbox" id="notes" name="notes">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Notes </td>
        </tr>



        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'acomptabilise') !== false) echo "checked"; ?>  type="checkbox" id="acomptabilise" name="acomptabilise">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> à compta. </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'justifie') !== false) echo "checked"; ?>  type="checkbox" id="justifie" name="justifie" ></td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Justifiée </td>
        </tr>


        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonneentree_detail['colonneentree'], 'documents') !== false) echo "checked"; ?>  type="checkbox" id="documents" name="documents">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Documents </td>
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










<?php
if(!isset($_GET["idc"])) $_GET["idc"]=0;
$req = $bdd->query("SELECT * from caisse where  deleted='non' and  id=".$_GET["idc"]);
$modf = $req->fetch();
$req->closecursor();
?>


<!------------------------------Effectuer une entrée ------------------------------>

<div id="modif" class="modalDialog">

  <div class="popup">
   
    <h1>Modification entrée </h1>
<form action="" method="post">
      <div class="cont">



        <fieldset >
          <legend>Caisse</legend>
            <select required name="Succursalemodif" id="Succursalemodif" style="margin:10px 0px" >

          <?php

            $req1 = $bdd->query("SELECT id,succursale from succursale where etat='encours' and id=".$modf['idsuccursale']." order by succursale asc ");
            while ($ste1 = $req1->fetch())
            {

              ?> 
              <option  value="<?php echo $modf['idsuccursale'] ;?>">  <?php echo $ste1['succursale'] ;?> </option>
              <?php

            }
          ?>  

             </select>


          <?php

          if($user_detail['affichersolde']=='oui'){
            $req1 = $bdd->query("SELECT id,succursale from succursale where etat='encours' and  id=".$modf['idsuccursale']." order by succursale asc ");
            while ($ste1 = $req1->fetch())
            {


              // total especes entree all succursale
              $totalespeceall = $bdd->query("SELECT sum(montant) as totalespeceall from caisse  where  deleted='non' and  type='entree'  and idsuccursale=".$modf['idsuccursale']);
              $totalespecealldetail = $totalespeceall ->fetch();
              $totalespeceall->closecursor();


              // total sortie all succursale
              $totalsortieall = $bdd->query("SELECT sum(montant) as totalsortieall from caisse  where  deleted='non' and  type='sortie' and idsuccursale=".$modf['idsuccursale']);
              $totalsortieall_detail = $totalsortieall ->fetch();
              $totalsortieall->closecursor();


              $totalcaisse = $totalespecealldetail['totalespeceall'] - $totalsortieall_detail['totalsortieall'];

               

              ?> 
              <p style="margin-top:10px;margin-bottom:15px;">Solde caisse: <span style="color:#00BA84;font-weight:bold"><?php echo number_format( $totalcaisse, 2, ',', ' '); ?> DHs</span> </p>

              <?php
              
            }
          }
          ?>  

        </fieldset>




      <?php
      if($user_detail['editdateop']=='oui')
      {
      ?>
        <fieldset>
          <legend>Date d'opération</legend>
          <p><input required type="date" name="dateopmodif"  value="<?php echo $modf['dateop'] ; ?>" ></p> 
        </fieldset>
      <?php
      }
      else
      {
      ?>
          <input required type="hidden" name="dateopmodif"  value="<?php echo $modf['dateop'] ; ?>" >

      <?php
      }
      ?>


        <fieldset >
          <legend>Nature d'entrée</legend>
          <input type="hidden" name="typeentreemodif" value="<?php  echo  $modf['typeentree'] ; ?>">
            <select disabled required name="typeentreemodif" id="typeentreemodif" onchange="typeentreefm()" style="margin:10px 0px" >
              <option  value="vente"   <?php if($modf['typeentree']=='vente') echo "selected"  ; ?> >Vente </option>
              <option  value="alimentation de caisse"  <?php if($modf['typeentree']=='alimentation de caisse') echo "selected"  ; ?>  >Alimentation de caisse </option>
            </select>
        </fieldset>


        <fieldset style="display:none"  id="type_alimentationmodif1" >
          <legend>Type alimentation caisse</legend>
          <input type="hidden" name="type_alimentationmodif" value="<?php  echo  $modf['type_alimentation'] ; ?>">
            <select  name="type_alimentationmodif" id="type_alimentationmodif" style="margin:10px 0px" onchange="typemadm()" >
            <option  value="" <?php if($modf['type_alimentation']=='') echo "selected"  ; ?> > </option>

                  <?php

                    $req11 = $bdd->query("SELECT id,type from type_alimentation_caisse order by type asc ");


                    while ($type_alimentation_caisse = $req11->fetch())
                    {

                      ?> 

                      <option  value="<?php echo $type_alimentation_caisse['id'] ;?>"  <?php if($modf['type_alimentation']==$type_alimentation_caisse['id']) echo "selected"  ; ?>   ><?php echo $type_alimentation_caisse['type'] ;?></option>

                      <?php
                      
                    }
                  ?>  

             </select>
        </fieldset>







        <fieldset   id="famillemodif1" >
          <legend >Famille vente</legend>
             <select  name="famillemodif" id="famillemodif" style="margin:5px 0px 10px 0px"  >  
             <option value="" > </option>  

                    <?php

                      $reqf = $bdd->query("SELECT id,famille from famillevente order by famille asc ");
                      while ($famille = $reqf->fetch())
                      {
                    ?> 

                        <option  value="<?php echo $famille['id'] ;?>"  <?php if($modf['idfamillevente']==$famille['id']) echo "selected"; ?>  > <?php echo $famille['famille'] ;?> </option>

                    <?php
                      }
                    ?>  

              </select>
        </fieldset>





        <fieldset id="clientmodif1" >
          <legend>Client</legend>
          <p> <input  required type="text"  name="clientmodif" id="country_id" onkeyup="autocomplet();apost('country_id')"  autocomplete="off" onkeyup="apost('clientmodif')" value="<?php echo $modf['client'] ; ?>">
          <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_id"></ul> </p>
        </fieldset>





        <div id="mad_fieldsm"  style="display:none" >
          <!-- MAD -->
          <fieldset id="mad_agencem" >
            <legend>Banque et agence</legend>
            <p> <input   type="text"  name="mad_agencem" id="country_mad_agencem" onkeyup="autocomplet_mad_agencem();apost('country_mad_agencem')"  autocomplete="off" onkeyup="apost('mad_agencem')" value="<?php echo $modf['mad_agence'] ; ?>" >
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_mad_agencem"></ul> </p>
          </fieldset>

          <fieldset id="mad_comptem" >
            <legend>N° de compte à débiter</legend>
            <p> <input  pattern="\s*(\S\s*){24}" title="le RIB doit avoir 24 caractères."  type="text"  name="mad_comptem" id="country_mad_comptem" onkeyup="autocomplet_mad_comptem();apost('country_mad_comptem')"  autocomplete="off" onkeyup="apost('mad_comptem')" value="<?php echo $modf['mad_compte'] ; ?>" >
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_mad_comptem"></ul> </p>
          </fieldset>

          <fieldset id="mad_beneficiairem" >
            <legend>Nom Bénéficiaire</legend>
            <p> <input   type="text"  name="mad_beneficiairem" id="country_mad_beneficiairem" onkeyup="autocomplet_mad_beneficiairem();apost('country_mad_beneficiairem')"  autocomplete="off" onkeyup="apost('mad_beneficiairem')" value="<?php echo $modf['mad_beneficiaire'] ; ?>" >
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_mad_beneficiairem"></ul> </p>
          </fieldset>

          <fieldset id="mad_cinm" >
            <legend>CIN Bénéficiaire</legend>
            <p> <input   type="text"  name="mad_cinm" id="country_mad_cinm" onkeyup="autocomplet_mad_cinm();apost('country_mad_cinm')"  autocomplete="off" onkeyup="apost('mad_cinm')" value="<?php echo $modf['mad_cin'] ; ?>" >
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_mad_cinm"></ul> </p>
          </fieldset>
          <!-- MAD -->
        </div>





        <fieldset>
          <legend>Libellé</legend>
          <p> <input  required type="text"  value="<?php echo $modf['libelle'] ; ?>" name="libellemodif" id="country_idlibelletentreemodif" autocomplete="off" value=""  onKeyUp="autocompletlibelletentreemodif();apost('country_idlibelletentreemodif')" >
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_idlibelletentreemodif"></ul></p>
        </fieldset>




        <fieldset>
          <legend>Montant</legend>
          <p> <input  onpaste="return false;" required type="text"  name="montantmodif" id="montantmodif" autocomplete="off" value="<?php echo $modf['montant'] ; ?>" onKeyUp="nombre('montantmodif')" ></p>
        </fieldset>


        <fieldset id="concernemodif1" >
          <legend>Notes</legend>
          <p> 
          <textarea  rows="8" name="concernemodif" id="concernemodif" onKeyUp="apost('concernemodif')"  ><?php echo $modf['concerne'] ; ?></textarea>
          </p>
        </fieldset>


        <fieldset>


        <?php if(isset($_GET['modif'])) { ?>

        <script>

          $(document).ready(function(){
              typeentreefm();
              typemadm();
          });
        </script>

        <?php } ?>


        <p><input type="submit" name="modif" value="Modifier"></p>
      </div>
    </form>


  <a href="#" id="x" title="quitter">x</a>
  </div>
       
    
</div>


<script>








function typeentreefm()
{


  if(document.getElementById('typeentreemodif').value == "vente")
  {
    document.getElementById("country_id").style.display = "";
    document.getElementById("clientmodif1").style.display = "";
    document.getElementById("country_id").required = true;

    document.getElementById("type_alimentationmodif1").style.display = "none";
    document.getElementById("type_alimentationmodif").style.display = "none";
    document.getElementById("type_alimentationmodif").required = false;
    document.getElementById("type_alimentationmodif").value = "";


    document.getElementById("famillemodif1").style.display = "";
    document.getElementById("famillemodif").required = true;


  }
  else
  {
    document.getElementById("country_id").style.display = "none";
    document.getElementById("clientmodif1").style.display = "none";
    document.getElementById("country_id").required = false;
    document.getElementById("country_id").value = "";

    document.getElementById("type_alimentationmodif1").style.display = "";
    document.getElementById("type_alimentationmodif").style.display = "";
    document.getElementById("type_alimentationmodif").required = true;

    document.getElementById("famillemodif1").style.display = "none";
    document.getElementById("famillemodif").required = false;
    document.getElementById("famillemodif").value = "";

  }

}








function typemadm()
{

    if(document.getElementById('type_alimentationmodif').value == 5)
    {
      document.getElementById("mad_fieldsm").style.display = "block";
    }
    else
    {
      document.getElementById("mad_fieldsm").style.display = "none";
      document.getElementById("country_mad_cinm").value = "";
      document.getElementById("country_mad_beneficiairem").value = "";
      document.getElementById("country_mad_comptem").value = "";
      document.getElementById("country_mad_agencem").value = "";
    }

}










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