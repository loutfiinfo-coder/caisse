<?php
include("config.php");



if(isset($_SESSION['idcaisse']))
{
$login = $bdd->query('SELECT login from users where id='.$_SESSION['idcaisse']);
$admin_login = $login ->fetch();
$login->closecursor();
if($admin_login['login']!="admin")
header('location:conn.php');
}else
{
header('location:conn.php');
}



?>





<?php





if(isset($_POST['miseadisposition'])) 
{

    $reqm = $bdd->prepare('UPDATE miseadisposition  set  miseadisposition=:miseadisposition');
    $reqm->execute(array('miseadisposition' => $_POST['miseadisposition'])); 
}



?>



<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Administration</title>
    <link rel="icon" type="image/ico" href="logo.ico" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="ckeditor_4.11.4_basic/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">



<script type="text/javascript" src="widgEditor_1.0.1/scripts/widgEditor.js"></script>

<style type="text/css" media="all">
    @import "widgEditor_1.0.1/css/widgEditor.css";

    #lettreWidgToolbarSelectBlock,#lettreWidgToolbarButtonHTML,#lettreWidgToolbarButtonImage,#lettreWidgToolbarButtonLink
    {
        display:none;
    }

    #lettreWidgToolbar
    {
        width:600PX
        margin:auto;
        margin-bottom:10px;
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
    }

</style>



</head>

<body>




<div class="container" style="width:1250px" >


        <div class="header">
            <a href="decon.php" style="color:#424242">[ Se déconnecter ]</a>
        </div><!-- header -->
        <h1 class="main_title">Administration</h1>
        <div class="content">

        <div class="menu">
            
                <ul>
                    <li><a href="admin.php" id="p1" >Utilisateurs</a></li>
                    <li><a href="#" id="p1" class="active" >Mise à disposition</a></li>
                    <li><a href="index.php" id="p2" class="">Application</a></li>
                </ul>
            
        </div>

<div class="profile" id="pro1" style="width:1050px" >

<script>

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

    <script src="ckeditor_4.11.4_basic/ckeditor/ckeditor.js"></script>
    <script src="ckeditor_4.11.4_basic/ckeditor/samples/js/sample.js"></script>





<?php
$reqmiseadisposition = $bdd->query('SELECT miseadisposition from miseadisposition');
$miseadisposition = $reqmiseadisposition ->fetch();
$reqmiseadisposition->closecursor();
?>





<form action="adminmiseadisposition.php"  method="post"  >

    <textarea id="lettre" name="miseadisposition" class="widgEditor nothing" ><?php echo $miseadisposition['miseadisposition'];?></textarea>

    <br/>

    <input type="submit" value="Enregistrer" style="display:inline-block;width:200px;margin:5px;cursor:pointer;text-align:center;float:right" />

</form>






</div>

<div style="background:#ddd;text-align:center;padding:10px;font-size:14px;margin-top:50px;border-radius:3px">
Développée par : <strong>A.loutfi</strong>  |  Email: <strong>Loutfi.info@gmail.com</strong>  |  Téléphone: <strong>0601-810237</strong>   
</div>

<div style="background:#ddd;text-align:center;padding:10px;font-size:14px;margin-top:10px;border-radius:3px">
 <strong>IMPORTANT: </strong> L'application gestion des espèces multi-caisses est uniquement concédée sous licence aux fins d'une utilisation non commerciale pour vos besoins opérationnels internes. Le terme « Utilisation non commerciale » signifie que vous ne pouvez pas vendre, louer, donner à bail, ni prêter ce qui est produit au moyen de l'application. Toute autre forme d'utilisation nécessite l'achat d'une licence gestion des espèces multi-caisses
</div>


<br/>

<script>
    initSample();
</script>


</body>
</html>