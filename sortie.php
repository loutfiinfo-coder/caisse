<?php 
include("config.php");



?>


<?php






if(isset($_SESSION['idcaisse']))
{

$user = $bdd->query('SELECT id,pages,login,ste,modification,supression,justification,editdocument,boncaisse,acomptabilise,editdateop,exportexcel,colonnecaisse,colonneentree,colonnesortie,affichersolde from users where id='.$_SESSION['idcaisse']);
$user_detail = $user ->fetch();
$user->closecursor();


if( !stristr($user_detail['pages'], 'sortie')) { header('location:conn.php'); }

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
            $currentlink="sortie.php";
           }

           header('location:'.$currentlink.'#');


}






if(isset($_POST['add']))
{



                // total especes entree par sucursale
                $totalespece = $bdd->query("SELECT sum(montant) as totalespece from caisse  where  type='entree' and deleted='non' and idsuccursale=".$_POST['Succursaleadd'] );
                $totalespecedetail = $totalespece ->fetch();
                $totalespece->closecursor();

                // total sortie par sucursale
                $totalsortie = $bdd->query("SELECT sum(montant) as totalsorti from caisse  where  type='sortie' and deleted='non'  and idsuccursale=".$_POST['Succursaleadd'] );
                $totalsortie_detail = $totalsortie ->fetch();
                $totalsortie->closecursor();

                $caissesuccursale = $totalespecedetail['totalespece'] - $totalsortie_detail['totalsorti'];



    if(number_format((float)$_POST['montantadd'], 2, '.', '') > number_format($caissesuccursale, 2, '.', '')) 
    {

    echo $caissesuccursale-number_format((float)$_POST['montantadd'], 2, '.', '');
    $verifycaissevalue='1' ;
    }
    else
    {


    $req = $bdd->prepare('INSERT INTO caisse(type,idsuccursale,valide,dateop,fournisseur,montant,concerne,idfamille,libelle,caissier) VALUES(:type,:idsuccursale,:valide,:dateop,:fournisseur,:montant,:concerne,:idfamille,:libelle,:caissier)');

    if(number_format((float)$_POST['montantadd'], 2, '.', '')== number_format($caissesuccursale, 2, '.', '')) 
    {
    $req->execute(array('type' => "sortie",'idsuccursale' => $_POST['Succursaleadd'],'valide' => "non",'dateop' => $_POST['dateopadd'],'fournisseur' => $_POST['fournisseuradd'],'montant' => $caissesuccursale,'concerne' => $_POST['concerneadd'],'idfamille' => $_POST['familleadd'],'libelle' => $_POST['libelleadd'] ,'caissier' => $user_detail['login'] ));
    }
    else
    {
    $req->execute(array('type' => "sortie",'idsuccursale' => $_POST['Succursaleadd'],'valide' => "non",'dateop' => $_POST['dateopadd'],'fournisseur' => $_POST['fournisseuradd'],'montant' => number_format((float)$_POST['montantadd'], 2, '.', ''),'concerne' => $_POST['concerneadd'],'idfamille' => $_POST['familleadd'],'libelle' => $_POST['libelleadd'] ,'caissier' => $user_detail['login'] ));
    }

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



    header('location:sortie.php#');    

  }

}




if(isset($_POST['modif']))
{




                $idmontant = $bdd->query("SELECT montant from caisse  where deleted='non' and  id=".$_GET['idc'] );
                $idmontantdetail = $idmontant ->fetch();
                $idmontant->closecursor();


                // total especes entree par sucursale
                $totalespece = $bdd->query("SELECT sum(montant) as totalespece from caisse  where deleted='non' and type='entree' and idsuccursale=".$_POST['Succursalemodif'] );
                $totalespecedetail = $totalespece ->fetch();
                $totalespece->closecursor();

                // total sortie par sucursale
                $totalsortie = $bdd->query("SELECT sum(montant) as totalsorti from caisse  where  deleted='non' and type='sortie'  and idsuccursale=".$_POST['Succursalemodif'] );
                $totalsortie_detail = $totalsortie ->fetch();
                $totalsortie->closecursor();




                // la difeirence entre dkachi li dakhal odakchi li kan deja comme sortie

                $montantdif = number_format((float)$_POST['montantmodif'], 2, '.', '')-$idmontantdetail['montant'];

                $caissesuccursale = $totalespecedetail['totalespece'] - $totalsortie_detail['totalsorti'] - $montantdif;



    if($caissesuccursale < 0) 
    {
    $verifycaissevalue1='1' ;
    }
    else
    {

        $reqx = $bdd->prepare('UPDATE caisse  set  idfamille=:idfamille,dateop=:dateop,fournisseur=:fournisseur,montant=:montant,concerne=:concerne,libelle=:libelle where id=:idc');


    if(number_format((float)$_POST['montantmodif'], 2, '.', '') == number_format($caissesuccursale, 2, '.', '')) 
    {
        $reqx->execute(array('idc' =>$_GET['idc'], 'dateop' => $_POST['dateopmodif'] ,'fournisseur' => $_POST['fournisseurmodif'],'montant' => $caissesuccursale,'concerne' => $_POST['concernemodif'],'idfamille' => $_POST['famillemodif'],'libelle' => $_POST['libellemodif']  )); 
    }
    else
    {
        $reqx->execute(array('idc' =>$_GET['idc'], 'dateop' => $_POST['dateopmodif'] ,'fournisseur' => $_POST['fournisseurmodif'],'montant' => number_format((float)$_POST['montantmodif'], 2, '.', '') ,'concerne' => $_POST['concernemodif'],'idfamille' => $_POST['famillemodif'],'libelle' => $_POST['libellemodif']  )); 

    }

        $reqx->closecursor();



           $currentlink="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


           if(strpos($currentlink, '&idc') !== false)
           {
            $currentlink=substr($currentlink,0,strpos($currentlink,"&idc"));
           }
           else if(strpos($currentlink, 'idc') !== false)
           {
            $currentlink="sortie.php";
           }

           header('location:'.$currentlink.'#');

  
  }

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
            $currentlink="sortie.php";
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
            $currentlink="sortie.php";
           }

           header('location:'.$currentlink.'#');


}






if(isset($_POST['enregistrer']))
{


    $colonnesortie = "";

    if(isset($_POST['caisse']))
      $colonnesortie =$colonnesortie."caisse|";
    if(isset($_POST['reference']))
      $colonnesortie =$colonnesortie."reference|";
    if(isset($_POST['dateop']))
      $colonnesortie =$colonnesortie."dateop|";
    if(isset($_POST['libelle']))
      $colonnesortie =$colonnesortie."libelle|";
    if(isset($_POST['famille']))
      $colonnesortie =$colonnesortie."famille|";
    if(isset($_POST['beneficiaire']))
      $colonnesortie =$colonnesortie."beneficiaire|";
    if(isset($_POST['notes']))
      $colonnesortie =$colonnesortie."notes|";
    if(isset($_POST['acomptabilise']))
      $colonnesortie =$colonnesortie."acomptabilise|";
    if(isset($_POST['justifie']))
      $colonnesortie =$colonnesortie."justifie|";
    if(isset($_POST['documents']))
      $colonnesortie =$colonnesortie."documents|";
    if(isset($_POST['caissier']))
      $colonnesortie =$colonnesortie."caissier|";

    $req = $bdd->prepare('UPDATE users  set  colonnesortie=:colonnesortie where id=:id');
    $req->execute(array('colonnesortie' => $colonnesortie, 'id' => $_SESSION['idcaisse']));

    header('location:sortie.php#');    
    
}




if(isset($_POST['filtred']))
{
  if(empty($_POST['ste']) and empty($_POST['ref']) and empty($_POST['fournisseur']) and empty($_POST['concerne']) and empty($_POST['libelle']) and empty($_POST['montant']) and empty($_POST['du1']) and empty($_POST['au1']) and empty($_POST['choixf']) and empty($_POST['choixst'])  and empty($_POST['choixstcom']) and empty($_POST['caissier']) and !isset($_POST['deleteditems'] )) 
  {
  header('location:sortie.php#');
  }  
}



?>





<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>sorties ..</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="ctm.css" />
  <link rel="icon" type="image/ico" href="logo.ico" />
  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  
  
  <script type="text/javascript" src="jquery.min.js"></script>
 <script src="xlsx.full.min.js"></script>


<script>
$(window).click(function() {
  $('#country_list_id4').hide();
  $('#country_list_id8').hide();
  $('#country_list_id5').hide();
  $('#country_list_idlibelletsortiefiltre').hide();
  $('#country_list_idlibelletsortie').hide();
  $('#country_list_idlibelletsortiemodif').hide();
});
</script>

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
<a class="menu" href="#" style="background:url(crossword.png) ;color:#00BA84;border-bottom:3px solid #00BA84;" ><i class="fa fa-arrow-up" aria-hidden="true"></i> Sorties</a>
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

<a class="menu" href="decon.php" style="color:#00BA84"><i class="fa fa-power-off"></i></a>

</div>

<div style="width:1250px;margin:auto;color:#444;font-size:20px;margin-top:-20px;font-family: 'Oswald',serif;">Bienvenu: <strong style="color:#00BA84;margin-left:5px;text-transform : capitalize;"><?php echo $user_detail['login']; ?></strong></div>





















































<?php

if(isset($_POST['affichertt']))
{

    unset($_POST['fournisseur']);
    unset($_POST['concerne']);
    unset($_POST['choixv']);
    unset($_POST['montant']);
    unset($_POST['ref']);
    unset($_POST['choixf']);
    unset($_POST['choixst']);
    unset($_POST['choixstcom']);

    unset($_POST['du1']);
    unset($_POST['au1']);


    echo "<script type='text/javascript'>document.location.href='sortie.php';</script>";

}


?>





<script>
function societe(ste)
{


 if(ste!="")
 {
 location.href="sortie.php?ste="+ste; 
 }
 else
 {
 location.href="sortie.php";
 }


}

</script>


<script>
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

<form method="post" id="form_av" action=""> 

<div style="width:1250px;margin:20px auto">
<div style="width:300px;display:inline-block;" >
<a id="sarr" href="#addsortie" ><i class="fa fa-pencil-square-o"></i> Effectuer une sortie </a>
</div>
<div style="width:80px;display:inline-block" >
<a id="sarr" href="sortie.php"  style="margin-left:10px"><i class="fa fa-refresh" ></i></a>
</div>


<input type="button"  id="affichertt" onclick="location.href='#colonnesortie';" value="Personnaliser les colonnes" style="width:270px;padding:0px;display:inline-block;height:67px;position:relative;top:-2px;font-size:20px;cursor:pointer;margin-left:10px" />


<div style="width:300px;display:inline-block;float:right" >
<a id="sarr" href="sortie.php#etatfamille" > Etat de dépenses par famille </a>
</div>

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
            <li id="filtrec1"><input style="padding:9px;width:115px" name="ref" type="text"  id="country_idref" onkeyup="autocompletref();apost('country_idref')"  autocomplete="off"  <?php if(isset($_POST['ref'])){echo ' value="'.$_POST["ref"].'" ';}elseif(isset($_GET['ref'])){echo ' value="'.$_GET["ref"].'" ';}?>/></li> 
            <ul style="width:128px;color:#333;font-size:15px;margin-left:6px;" id="country_list_idref"></ul>
        </fieldset>


        <fieldset style="width:150px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;margin-right:2px;height:70px;">
        <legend>Bénéficiaire</legend>
            <li id="filtrec1"><input style="padding:9px;width:115px" name="fournisseur" type="text"  id="country_id5" onkeyup="autocomplet5();apost('country_id5')"  autocomplete="off"  <?php if(isset($_POST['fournisseur'])){echo ' value="'.$_POST["fournisseur"].'" ';}elseif(isset($_GET['fournisseur'])){echo ' value="'.$_GET["fournisseur"].'" ';}?>/></li> 
            <ul style="width:128px;color:#333;font-size:15px;margin-left:4px;" id="country_list_id5"></ul>
        </fieldset>





        <fieldset style="width:190px;text-align:center;margin:0px;padding-bottom:12px;display:inline-block;margin-right:2px;height:70px;">
        <legend>Libellé </legend>
            <li id="filtrec1"><input style="padding:9px;width:158px" name="libelle" type="text"  id="country_idlibelletsortiefiltre" onkeyup="autocompletlibelletsortiefiltre();apost('country_idlibelletsortiefiltre')"  autocomplete="off"  <?php if(isset($_POST['libelle'])){echo ' value="'.$_POST["libelle"].'" ';}elseif(isset($_GET['libelle'])){echo ' value="'.$_GET["libelle"].'" ';}?>/></li>
            <ul style="width:172px;color:#333;font-size:15px;margin-left:8px;" id="country_list_idlibelletsortiefiltre"></ul> 
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


function defaut()
{       


        <?php if (isset($_GET['ste'])) {?>
          document.getElementById('id_ste').value=<?php echo $_GET['ste'];?>;
        <?php }else{?>
          document.getElementById('id_ste').value=""; 
        <?php }?>

}

</script>




        <fieldset style="width:224px;text-align:center;margin:0px;padding:5px 0px 10px 0px;display:inline-block;margin-right:2px;height:65px;">
          <legend>Famille</legend>
             <li >
             <select  name="choixf" id="choixf" style="width:204px;padding:8px" >  
             <option value=""  <?php if (isset($_POST['choixf'])){ if($_POST['choixf']=="") echo "selected" ;}elseif (isset($_GET['choixf'])){ if($_GET['choixf']=="") echo "selected" ;} ?> > </option>  

                    <?php

                      $reqf = $bdd->query("SELECT id,famille from famille order by famille asc ");
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

            

        <fieldset style="width:170px;text-align:center;margin:0px;padding:5px 0px 10px 0px;display:inline-block;margin-right:2px;height:65px;">
          <legend>Justifiée</legend>
            <li ><select  name="choixst" id="choixst" style="width:150px;padding:8px;">  
            <option value=""  <?php if (isset($_POST['choixst'])){ if($_POST['choixst']=="") echo "selected" ;}elseif (isset($_GET['choixst'])){ if($_GET['choixst']=="") echo "selected" ;} ?> > </option> 
           
            <option  value="en cours"  <?php if (isset($_POST['choixst'])){ if($_POST['choixst']=="en cours") echo "selected" ;}elseif (isset($_GET['choixst'])){ if($_GET['choixst']=="en cours") echo "selected" ;} ?> >Non</option>  
            
            <option  value="valide"  <?php if (isset($_POST['choixst'])){ if($_POST['choixst']=="valide") echo "selected" ;}elseif (isset($_GET['choixst'])){ if($_GET['choixst']=="valide") echo "selected" ;} ?> >Oui</option>  

            </select> </li> 
        </fieldset>




        <fieldset style="width:170px;text-align:center;margin:0px;padding:5px 0px 10px 0px;display:inline-block;margin-right:2px;height:65px;">
          <legend>à comptabiliser</legend>
            <li ><select  name="choixstcom" id="choixstcom" style="width:150px;padding:8px;">  
            <option value=""  <?php if (isset($_POST['choixstcom'])){ if($_POST['choixstcom']=="") echo "selected" ;}elseif (isset($_GET['choixstcom'])){ if($_GET['choixstcom']=="") echo "selected" ;} ?> > </option> 
           
            <option  value="oui"  <?php if (isset($_POST['choixstcom'])){ if($_POST['choixstcom']=="oui") echo "selected" ;}elseif (isset($_GET['choixstcom'])){ if($_GET['choixstcom']=="oui") echo "selected" ;} ?> >oui</option>  
            
            <option  value="non"  <?php  if (isset($_POST['choixstcom'])){ if($_POST['choixstcom']=="non") echo "selected" ;}elseif (isset($_GET['choixstcom'])){ if($_GET['choixstcom']=="non") echo "selected" ;} ?> >non</option>  

            </select> </li> 
        </fieldset>





        <fieldset style="width:375px;text-align:center;margin:0px;padding-bottom:11px;display:inline-block;margin-right:2px;height:70px;position:relative;top:-2px">
          <legend>Date opération</legend>
            <li >

            <input style="padding:9px;margin-right:5px;width:155px;padding:6.5px" name="du1" type="date" id="du1" <?php if(isset($_POST['du1'])){echo ' value="'.$_POST["du1"].'" ';}elseif(isset($_GET['du1'])){echo ' value="'.$_GET["du1"].'" ';}?> /><input style="padding:9px;margin-right:2px;width:155px;padding:6.5px" name="au1" type="date" id="au1" <?php if(isset($_POST['au1'])){echo ' value="'.$_POST["au1"].'" ';}elseif(isset($_GET['au1'])){echo ' value="'.$_GET["au1"].'" ';}?> /></li> 
        </fieldset>
        
        <input type="submit" name="filtred" id="filtred" value="Filtrer"  style="width:250px;padding:21px 2px;font-size:18px;position:relative;top:2px;margin-right:4px"/>



 
        </form>
    </ul>
</div>









































  <?php

    $reqcolonne = $bdd->query('SELECT colonnesortie FROM users where id='.$_SESSION['idcaisse']);
    $colonnesortie_detail = $reqcolonne->fetch();
    $reqcolonne->closecursor(); 

  ?>


<div style="overflow-x: scroll;margin:auto">
<table id="testTable" summary="Code page support in different versions of MS Windows." rules="groups" frame="hsides" border="2" >
        <tr>

            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'caisse') !== false){ ?>
                <th>Caisse</th>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'caissier') !== false){ ?>
                <th>Caissier</th>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'reference') !== false){ ?>
                <th>Numéro</th>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'dateop') !== false){ ?>
                <th>Date opération</th>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'famille') !== false){ ?>
                <th>Famille</th>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'libelle') !== false){ ?>
                <th>Libellé</th>
            <?php }?>

            <th>Montant</th>

            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'beneficiaire') !== false){ ?>
                <th>Bénéficiaire</th>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'notes') !== false){ ?>
                <th>Notes</th>
            <?php }?>



            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'acomptabilise') !== false){ ?>
                <th>à compta.</th>
            <?php }?>



            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'justifie') !== false){ ?>
                <th>Justifiée</th>
            <?php }?>



            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'documents') !== false){ ?>
                <th>Documents</th>
            <?php }?>




                <?php if( $user_detail['boncaisse']=="oui" ){ ?>
                <th>Ordre de Dépense</th>
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



     if(!empty($_POST['fournisseur']))
     {
        $champs=$champs." AND fournisseur='".$_POST['fournisseur']."' ";
        $filtre=$filtre."fournisseur=".$_POST['fournisseur']."&";
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


     if(!empty($_POST['choixf']))
     {
        $champs=$champs." AND idfamille=".$_POST['choixf']." ";
        $filtre=$filtre."choixf=".$_POST['choixf']."&"; 
     }


     if(!empty($_POST['choixst']))
     {
        if($_POST['choixst']=="en cours")
        {
          $champs=$champs." AND valide='non'";
          $filtre=$filtre."choixst=".$_POST['choixst']."&"; 
        }
        elseif($_POST['choixst']=="valide")
        {
          $champs=$champs." AND valide='oui'";
          $filtre=$filtre."choixst=".$_POST['choixst']."&"; 
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


     if(isset($_POST['deleteditems']))
     {
        $champs=$champs." AND deleted='oui' ";
        $filtre=$filtre."deleted=oui&"; 
     }
     else
     {
         $champs=$champs." AND deleted='non' ";
     }


     if( !empty($_POST['du1']) and !empty($_POST['au1']) )
     {
        $champs=$champs." AND dateop BETWEEN '".$_POST['du1']."' AND '".$_POST['au1']."'";
        $filtre=$filtre."du1=".$_POST['du1']."&";
        $filtre=$filtre."au1=".$_POST['au1']."&";
     }





     //echo $champs;

     $req = $bdd->query("SELECT * from caisse where type='sortie'  ".$champs."  ".$societe." order by  dateop desc,id desc  ");



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


     if(!empty($_GET['fournisseur']))
     {
        $champs=$champs." AND fournisseur='".$_GET['fournisseur']."' ";
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


     if(!empty($_GET['choixf']))
     {
        $champs=$champs." AND idfamille=".$_GET['choixf']." ";
     }


     if(!empty($_GET['choixst']))
     {
        if($_GET['choixst']=="en cours")
        {
          $champs=$champs." AND valide='non'";
        }
        elseif($_GET['choixst']=="valide")
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

     $req = $bdd->query("SELECT * from caisse where  type='sortie'  ".$champs."  ".$societe." order by  dateop desc,id desc  ");



}
else
{


    $req = $bdd->query("SELECT * from caisse where  deleted='non' and  type='sortie'  ".$societe." order by  dateop desc,id desc  LIMIT ".$sf.",".$nb);


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

    $famille11 = $bdd->query("SELECT famille FROM famille WHERE id=".$cheque['idfamille']);
    $famille11_detail = $famille11->fetch();

    ?>
  



            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'caisse') !== false){ ?>
                <td ><?php echo $succursale11_detail['succursale'] ?></td>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'caissier') !== false){ ?>
                <td style="min-width:80px"> <?php echo $cheque['caissier'] ?></td>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'reference') !== false){ ?>
                <td style="min-width:110px" ><?php echo $cheque['ref'] ?></td>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'dateop') !== false){ ?>
                <td ><?php echo changedate($cheque['dateop']) ?></td>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'famille') !== false){ ?>
                <td ><?php echo $famille11_detail['famille'] ?></td>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'libelle') !== false){ ?>
                <td style="min-width:120px" ><?php echo $cheque['libelle'] ?></td>
            <?php }?>

                <td style="min-width:100px;text-align:right;font-weight:bold;font-size:13px" ><?php if($cheque['montant']!="") echo  number_format( $cheque['montant'], 2, ',', ' '); ?></td>

            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'beneficiaire') !== false){ ?>
                <td style="min-width:120px" ><?php echo $cheque['fournisseur'] ?></td>
            <?php }?>


            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'notes') !== false){ ?>
                <td style="min-width:120px;white-space: pre-wrap;" ><?php echo $cheque['concerne'] ?></td>
            <?php }?>





























                 
                                        

            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'acomptabilise') !== false){ ?>

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

        







            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'justifie') !== false){ ?>

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





            <?php if(strpos($colonnesortie_detail['colonnesortie'], 'documents') !== false){ ?>

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
                  <form action="sortie.php" method="post" enctype="multipart/form-data" name="importdoc" id="importdoc<?php echo $cheque['id'] ?>" >
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









                <?php if( $user_detail['boncaisse']=="oui" ){ ?>
                <td> <a style="min-width:130px" href="boncaisse.php?type=sortie&id=<?php echo $cheque['id'] ?>" id="passer"  target="_blank"> <i class="fa fa-print"></i> Ordre de Dépense</a></td> 
                <?php }?>





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
echo "<p style='width:1230px;margin:auto'>* NB sorties : <strong style='color:#00BA84'>".$count."</strong></p>";
echo "<p style='width:1230px;margin:auto'>* Total sorties : <strong style='color:#00BA84'>".number_format($total, 2, ',', ' ')." DH</strong></p>";
?> 


      <?php
      if($user_detail['exportexcel']=='oui')
      {
      ?>
      <div style="width:1230px;margin:auto;margin-top:10px">
<a href="#" style="padding:5px 10px" id="export" onclick="tableToExcel('testTable', 'Sorties');">Exporter en excel-csv ..</a>
      </div>
      <?php
      }
      ?>


</br>
<!-- Pagination PHP ------------------------------------------------------------------------------------------>




        <?php if ( !isset($_POST['filtred']) and  !isset($_GET['filtre']) ) {?>
                        
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

                          $req = $bdd->query("SELECT COUNT(*) AS nb FROM caisse where  deleted='non' and type='sortie' ".$societe );

                        



                        $m = 'sortie.php?nb='.$nb.'&amp;';
                        

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




















































<!------------------------------popup  detail Affichage ------------------------------>
<div id="etatfamille" class="modalDialog" >

  <div class="popup" style="width:1000px">


  <form method="post" id="etatfamilleform" action="#etatfamille" > 
  
    <h1>Etat de dépenses par famille</h1>
    </br>
    <div class="cont" style="width:1000px;text-align:center">



<div id="champsright"  style="display:inline-block;vertical-align:top;margin-top: 20px;overflow: auto;vertical-align: top;" >


        <fieldset style="width:375px;text-align:center;margin:0px;padding-bottom:11px;display:inline-block;margin-right:2px;height:70px;position:relative;top:-2px">
          <legend>Date opération</legend>

            <input required style="padding:9px;margin-right:5px;width:155px;padding:6.5px" name="du2" type="date" id="du2" <?php if(isset($_POST['du2'])){echo ' value="'.$_POST["du2"].'" ';}elseif(isset($_GET['du2'])){echo ' value="'.$_GET["du2"].'" ';}?> /><input required style="padding:9px;margin-right:2px;width:155px;padding:6.5px" name="au2" type="date" id="au2" <?php if(isset($_POST['au2'])){echo ' value="'.$_POST["au2"].'" ';}elseif(isset($_GET['au2'])){echo ' value="'.$_GET["au2"].'" ';}?> /> 
        </fieldset>
        


        <fieldset style="height: 500PX;border:1px solid black;overflow-y: scroll;" >
          <legend>Familles</legend>
          <table style="width:375px;margin-bottom:20px">
            <?php

              $req1 = $bdd->query("SELECT famille,id FROM famille order by famille asc");
              while ($famille1 = $req1->fetch())
              {
              ?>
                  <tr>

                    <td style="padding:10px;width:50px">
                      <input style="cursor:pointer" type="checkbox" id="<?php echo $famille1['id'] ;?>" name="<?php echo $famille1['id'] ;?>" value="<?php echo $famille1['id'] ;?>"  <?php if(isset($_POST[$famille1["id"]])) echo "checked"; ?> > 
                    </td> 

                    <td style="font-size:16px;text-align:left;padding-left:30px"> 
                      <p><?php echo $famille1['famille'] ;?></p>
                    </td>

                  </tr>
            <?php
              }
            ?>  
          </table>
        </fieldset>


      <div style="margin:auto;margin:20px 0px">
        <input type="submit" name="filtred2" id="filtred2" value="Filtrer"  style="width:375px;padding:21px 2px;font-size:18px;position:relative;"/>
      </div>


</div>


<div id="champsleft" style="display:inline-block;margin-left:40px;width:500px" > 

      <table style="width:500px" id="etatfamilletable">

          <tr>
              <td colspan="2" style="background:white;font-size:16px;text-align:center;padding:10px;padding-right:30px;font-weight:bold" >Etat de dépenses par famille 
              <?php 
                if(isset($_POST['filtred2']))
                {
                  echo "Du: ".changedate($_POST['du2'])." Au: ".changedate($_POST['au2']);
                }
                else
                {
                  echo "Global";
                }
              ?>
              </td>
          </tr>

          <tr>
            <th >
              Famille
            </th> 
            <th >
              Total dépenses
            </th>
          </tr>

        <?php

        $listefamille="";
        $req3 = $bdd->query("SELECT famille,id FROM famille order by famille asc");
        while ($famille3 = $req3->fetch())
        {
          if(isset($_POST[$famille3["id"]]))
          {
            $listefamille=$listefamille.$famille3["id"].",";
          }

        }
        $listefamille = rtrim($listefamille, ",");
        //echo $listefamille;




        if($listefamille<>"")
        {
          $reqetatfamille = $bdd->query("SELECT famille,id FROM famille where id IN(".$listefamille.")  order by famille asc");
        }
        else
        {
          $reqetatfamille = $bdd->query("SELECT famille,id FROM famille order by famille asc");
        }        
        while ($etatfamille = $reqetatfamille->fetch())
        {
          if(isset($_POST['filtred2']))
          {

              $reqfamillenom = $bdd->query("SELECT sum(montant) as summantant FROM caisse where idfamille=".$etatfamille['id']." and type='sortie' and dateop BETWEEN '".$_POST['du2']."' AND '".$_POST['au2']."' group by idfamille");
            
          }
          else
          {
          $reqfamillenom = $bdd->query("SELECT sum(montant) as summantant FROM caisse where idfamille=".$etatfamille['id']." and type='sortie' ");
          }

          $familletotal = $reqfamillenom->fetch();  
          $reqfamillenom->closecursor();
        ?>

          <tr>
            <td style="font-size:16px;padding:10px;min-width: :150px">
              <?php echo $etatfamille['famille']; ?>
            </td> 
            <td style="font-size:16px;text-align:right;padding:10px;padding-right:30px;font-weight:bold">
                <?php 
                echo $familletotal && isset($familletotal['summantant']) 
                    ? number_format((float)$familletotal['summantant'], 2, ',', ' ') 
                    : '0,00';
                ?>
            </td>
          </tr>

        <?php
        }
        $reqetatfamille->closecursor(); 
        ?>

      </table>

        </br>


      <?php
      if($user_detail['exportexcel']=='oui')
      {
      ?>
      <div style="margin:auto;margin-top:10px">
      <a href="#" style="padding:5px 10px" id="export" onclick="tableToExcel('etatfamilletable', 'Etat de dépenses')">Exporter en excel-csv ..</a>
      </div>
      <?php
      }
      ?>

      </br>

</div>




</div>




      <a href="sortie.php#etatfamille" onclick=" document.getElementById('du2').value=''; document.getElementById('au2').value='';document.getElementById('etatfamilleform').submit();" style="margin-right:50px" id="x" title="refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>

      <a href="sortie.php"  id="x" title="quitter"><i class="fa fa-times" aria-hidden="true"></i></a>

     </form> 
  </div>

</div>












<!------------------------------popup  documents option ------------------------------>



<div id="docoption" class="modalDialog">

  <div class="popup">
   
    <h1>Document</h1>
    <form action="" method="post"  >
    <div class="cont" style="" >

        <a onclick="popupCenter('documents/<?php echo $_GET["urldoc"] ?>', 'Ajbon',500,500);" style="border:1px solid #14B214;border-radius: 3px;margin:30px;color:#14B214;font-weight:bold;display:block;text-align:center;padding:10px;font-size:18px;background:#FEFEFE;cursor:pointer"  > <i class="fa fa-eye"></i> Afficher</a></p>


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










<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="script-autocomplete.js"></script>



<script> 
$( document ).ready(function() {document.getElementById("error").style.display="none"}); 
</script>









<!------------------------------Effectuer une sortie ------------------------------>

<div id="addsortie" class="modalDialog">

  <div class="popup">
   
    <h1>Effectuer une sortie</h1>
<form action="" method="post" id="formaddsortie" onsubmit="verifycaisse()" >
      <div class="cont">


        <fieldset >
          <legend>Caisse</legend>
            <select required name="Succursaleadd" id="Succursaleadd" style="margin:10px 0px" onchange="soldecaisse(this)" >
              <option  value=""></option>

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
              $totalespeceall = $bdd->query("SELECT sum(montant) as totalespeceall from caisse  where  deleted='non' and  type='entree'  and idsuccursale=".$ste1['id']);
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

                document.getElementById(<?php echo $ste1['id']; ?>+'c').style.display = "none";

              <?php
              }
            }
          ?>  

    document.getElementById(soldecaisse+'c').style.display = "block";
}

</script>







      <?php
      if($user_detail['editdateop']=='oui')
      {
      ?>
        <fieldset>
          <legend>Date d'opération</legend>
          <p><input required type="date" name="dateopadd" value="<?php echo date('Y-m-d', strtotime(date('Y-m-d')));?>"></p>        
        </fieldset>
      <?php
      }
      else
      {
      ?>
        <input required type="hidden" name="dateopadd" value="<?php echo date('Y-m-d', strtotime(date('Y-m-d')));?>">
      <?php
      }
      ?>





        <fieldset>

          <legend id="familleadd1"  >famille</legend>
             <select required name="familleadd" id="familleadd" style="margin:5px 0px 10px 0px"  >  
             <option value="" > </option>  

                    <?php

                      $reqf = $bdd->query("SELECT id,famille from famille order by famille asc ");
                      while ($famille = $reqf->fetch())
                      {
                    ?> 

                        <option  value="<?php echo $famille['id'] ;?>"  > <?php echo $famille['famille'] ;?> </option>

                    <?php
                      }
                    ?>  

              </select>
        </fieldset>





        <fieldset>
          <legend>Libellé</legend>
          <p> <input  required type="text"  name="libelleadd" id="country_idlibelletsortie" autocomplete="off" value=""  onKeyUp="autocompletlibelletsortie();apost('country_idlibelletsortie')" >
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_idlibelletsortie"></ul></p>
        </fieldset>



        <fieldset>
          <legend>Montant</legend>
          <p> <input onpaste="return false;" required type="text"  name="montantadd" id="montantadd" autocomplete="off" value=""  onKeyUp="nombre('montantadd')" ></p>
        </fieldset>


        <fieldset>
          <legend>Bénéficiaire</legend>
          <p> <input  required type="text"  name="fournisseuradd" id="country_id4" onkeyup="autocomplet4();apost('country_id4')"  autocomplete="off"  value="">
          <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_id4"></ul> </p>
        </fieldset>




        <fieldset>
          <legend>Notes</legend>
          <p> 
          <textarea  rows="8" name="concerneadd" id="concerneadd" onKeyUp="apost('concerneadd')"  ></textarea>
          </p>
        </fieldset>



        <p><input type="submit" name="add" value="Ajouter" ></p>
      </div>
    </form>

    <ul id="error">

        <?php
                                if(isset($_POST['add']))
                                {
                                    if($verifycaissevalue=='1'){
                                        echo'<script> $( document ).ready(function() {document.getElementById("error").style.display="block"});
                                        document.getElementById("num").focus(); </script>';
                                        echo '<li style="color:#fb065c;font-weight:bold">le montant de sortie ne devrait pas être supérieur au solde de caisse</li>';
                                    }
                                        
                                }
        ?>

    </ul>


  <a href="#" id="x" title="quitter">x</a>
  </div>
       
    
</div>





<?php
if(!isset($_GET["idc"])) $_GET["idc"]=0;
$req = $bdd->query("SELECT * from caisse  where deleted='non' and id=".$_GET["idc"]);
$modf = $req->fetch();
$req->closecursor();
?>





<!------------------------------popup  detail Affichage ------------------------------>
<div id="colonnesortie" class="modalDialog" >

  <div class="popup" style="width:400px" >
 
  <?php

    $reqcolonne = $bdd->query('SELECT colonnesortie FROM users where id='.$_SESSION['idcaisse']);
    $colonnesortie_detail = $reqcolonne->fetch();
    $reqcolonne->closecursor(); 

  ?>

  <form method="post" id="colonnesortie" action=""> 
  
    <h1>Personnaliser l'affichage des colonnes...</h1>
    </br>
    <div class="cont">
      <table style="width:100%">

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'caisse') !== false) echo "checked"; ?> type="checkbox" id="caisse" name="caisse">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Caisse </td>
        </tr>



        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'caissier') !== false) echo "checked"; ?> type="checkbox" id="caissier" name="caissier">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Caissier </td>
        </tr>



        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'reference') !== false) echo "checked"; ?>  type="checkbox" id="reference"  name="reference">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Numéro </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'dateop') !== false) echo "checked"; ?>  type="checkbox" id="dateop" name="dateop">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Date opération </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'famille') !== false) echo "checked"; ?>  type="checkbox" id="famille" name="famille">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Famille sortie </td>
        </tr>


        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'libelle') !== false) echo "checked"; ?>  type="checkbox" id="libelle" name="libelle">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Libellé </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'beneficiaire') !== false) echo "checked"; ?>  type="checkbox" id="beneficiaire" name="beneficiaire">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Bénéficiaire </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'notes') !== false) echo "checked"; ?>  type="checkbox" id="notes" name="notes">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Notes </td>
        </tr>


        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'acomptabilise') !== false) echo "checked"; ?>  type="checkbox" id="acomptabilise" name="acomptabilise">
          </td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> à compta. </td>
        </tr>

        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'justifie') !== false) echo "checked"; ?>  type="checkbox" id="justifie" name="justifie" ></td> 
          <td style="font-size:16px;text-align:left;padding-left:30px"> Justifiée </td>
        </tr>


        <tr>
          <td style="padding:10px;width:100px">
            <input <?php if(strpos($colonnesortie_detail['colonnesortie'], 'documents') !== false) echo "checked"; ?>  type="checkbox" id="documents" name="documents">
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





<script> 
$( document ).ready(function() {document.getElementById("error1").style.display="none"}); 
</script>

<!------------------------------Effectuer une sortie ------------------------------>

<div id="modif" class="modalDialog">

  <div class="popup">
   
    <h1>Modification sortie </h1>
    <form action="" method="post">
      <div class="cont">


        <fieldset >
          <legend>Caisse</legend>
            <select required name="Succursalemodif" id="Succursalemodif" style="margin:10px 0px" >

          <?php

            $req1 = $bdd->query("SELECT id,succursale from succursale where etat='encours' and  id=".$modf['idsuccursale']." order by succursale asc ");
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

            $req1 = $bdd->query("SELECT id,succursale from succursale where etat='encours' and id=".$modf['idsuccursale']." order by succursale asc ");
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








        <fieldset>
          <legend id="famillemodif1"  >famille</legend>
             <select required name="famillemodif" id="famillemodif" style="margin:5px 0px 10px 0px"  >  
             <option value="" > </option>  

                    <?php

                      $reqf = $bdd->query("SELECT id,famille from famille order by famille asc ");
                      while ($famille = $reqf->fetch())
                      {
                    ?> 

                        <option  value="<?php echo $famille['id'] ;?>"  <?php if($modf['idfamille']==$famille['id']) echo "selected"; ?> > <?php echo $famille['famille'] ;?> </option>

                    <?php
                      }
                    ?>  

              </select>
        </fieldset>



        <fieldset>
          <legend>Libellé</legend>
          <p> <input  required type="text"  value="<?php echo $modf['libelle'] ; ?>" name="libellemodif" id="country_idlibelletsortiemodif" autocomplete="off" value=""  onKeyUp="autocompletlibelletsortiemodif();apost('country_idlibelletsortiemodif')" >
            <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_idlibelletsortiemodif"></ul></p>
        </fieldset>






        <fieldset>
          <legend>Montant</legend>
          <p> <input onpaste="return false;" required type="text"  name="montantmodif" id="montantmodif" autocomplete="off" value="<?php echo $modf['montant'] ; ?>" onKeyUp="nombre('montantmodif')" ></p>
        </fieldset>


        <fieldset>
          <legend>Bénéficiaire</legend>
          <p> <input  required type="text"  name="fournisseurmodif" id="country_id8" onkeyup="autocomplet8();apost('country_id8')"  autocomplete="off" value="<?php echo $modf['fournisseur'] ; ?>">
          <ul style="width:286px;color:#333;font-size:15px;margin-left:11px;" id="country_list_id8"></ul> </p>
        </fieldset>





        <fieldset>
          <legend>Notes</legend>
          <p> 
          <textarea  rows="8" name="concernemodif" id="concernemodif" onKeyUp="apost('concernemodif')"  ><?php echo $modf['concerne'] ; ?></textarea>
          </p>
        </fieldset>



        <p><input type="submit" name="modif" value="Modifier"></p>
      </div>
    </form>

    <ul id="error1">

        <?php
                                if(isset($_POST['modif']))
                                {
                                    if($verifycaissevalue1=='1'){
                                        echo'<script> $( document ).ready(function() {document.getElementById("error1").style.display="block"});document.getElementById("num").focus(); </script>';
                                        echo '<li style="color:#fb065c;font-weight:bold">le montant de sortie ne devrait pas être supérieur au solde de caisse</li>';
                                    }
                                        
                                }
        ?>

    </ul>

  <a href="#" id="x" title="quitter">x</a>
  </div>
       
    
</div>



















<br/>

</body>
</html>