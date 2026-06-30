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



if(isset($_GET['sup']))
{

 $req = $bdd->prepare('DELETE FROM users  where id=:iduser');
 $req->execute(array('iduser' => $_GET['iduser']));


 header('location:admin.php#');

}








if(isset($_POST['add'])) 
{




    $pages="";


    if(isset($_POST['famille']))
        $pages.="famille, ";
    if(isset($_POST['societe']))
        $pages.="societe, ";
    if(isset($_POST['sortie']))
        $pages.="sortie, ";
    if(isset($_POST['entree']))
        $pages.="entree, ";





    $societe="";

    $exportexcel="non";
    $editdateop="non";
    $suppression="non";
    $modification="non";
    $justification="non";
    $acomptabilise="non";
    $editdocument="non";
    $boncaisse="non";
    $cloturercaisse="non";
    $fermeturercaisse="non";

    $transfertcaisse="non";
    $showdeletedoperations="non";
    $affichersolde="non";



   if(isset($_POST['exportexcel']))
    $exportexcel="oui";
   if(isset($_POST['editdateop']))
    $editdateop="oui";
    if(isset($_POST['suppression']))
    $suppression="oui";
    if(isset($_POST['modification']))
    $modification="oui";
    if(isset($_POST['justification']))
    $justification="oui";
    if(isset($_POST['acomptabilise']))
    $acomptabilise="oui";
    if(isset($_POST['editdocument']))
    $editdocument="oui";
    if(isset($_POST['boncaisse']))
    $boncaisse="oui";
    if(isset($_POST['cloturercaisse']))
    $cloturercaisse="oui";
    if(isset($_POST['fermeturercaisse']))
    $fermeturercaisse="oui";
    if(isset($_POST['transfertcaisse']))
    $transfertcaisse="oui";
    if(isset($_POST['showdeletedoperations']))
    $showdeletedoperations="oui";
    if(isset($_POST['affichersolde']))
    $affichersolde="oui";

    $req = $bdd->query("SELECT id from succursale order by succursale asc ");
        while ($ste = $req->fetch())
        {
            if(isset($_POST[$ste['id']]))
                $societe.=strval($ste['id'].",");
        }
        $req->closecursor();

    if($societe<>"")
        $societe=rtrim($societe,", ");

    $req = $bdd->prepare('INSERT INTO users(login,password,ste,supression,modification,justification,acomptabilise,editdocument,boncaisse,cloturercaisse,fermeturercaisse,transfertcaisse,showdeletedoperations,affichersolde,editdateop,exportexcel,pages,colonnecaisse,colonneentree,colonnesortie) VALUES(:login,:password,:ste,:supression,:modification,:justification,:acomptabilise,:editdocument,:boncaisse,:cloturercaisse,:fermeturercaisse,:transfertcaisse,:showdeletedoperations,:affichersolde,:editdateop,:exportexcel,:pages,:colonnecaisse,:colonneentree,:colonnesortie)');
    $req->execute(array('login' => $_POST['login'],'password' => $_POST['password'],'supression' => $suppression,'editdateop' => $editdateop,'exportexcel' => $exportexcel, 'modification' => $modification,'justification' => $justification,'acomptabilise' => $acomptabilise,'editdocument' => $editdocument,'boncaisse' => $boncaisse,'cloturercaisse' => $cloturercaisse,'fermeturercaisse' => $fermeturercaisse,'transfertcaisse' => $transfertcaisse,'showdeletedoperations' => $showdeletedoperations,'affichersolde' => $affichersolde,'ste' => $societe,'pages' => $pages,'colonnecaisse' => 'caisse|reference|dateop|libelle|nature_entree|type_alimentation|famille|client|beneficiaire|' ,'colonneentree' => 'caisse|reference|dateop|libelle|nature_entree|type_alimentation|client|notes|acomptabilise|justifie|documents|','colonnesortie' => 'caisse|reference|dateop|libelle|famille|beneficiaire|notes|acomptabilise|justifie|documents|'));


}








if(isset($_POST['modif'])) 
{


    $pages="";


    if(isset($_POST['famille']))
        $pages.="famille, ";
    if(isset($_POST['societe']))
        $pages.="societe, ";
    if(isset($_POST['sortie']))
        $pages.="sortie, ";
    if(isset($_POST['entree']))
        $pages.="entree, ";


    $societe="";

    $exportexcel="non";
    $editdateop="non";
    $suppression="non";
    $modification="non";
    $justification="non";
    $acomptabilise="non";
    $editdocument="non";
    $boncaisse="non";
    $cloturercaisse="non";
    $fermeturercaisse="non";

    $transfertcaisse="non";
    $showdeletedoperations="non";
    $affichersolde="non";



    if(isset($_POST['exportexcel']))
    $exportexcel="oui";
    if(isset($_POST['editdateop']))
    $editdateop="oui";
    if(isset($_POST['suppression']))
    $suppression="oui";
    if(isset($_POST['modification']))
    $modification="oui";
    if(isset($_POST['justification']))
    $justification="oui";
    if(isset($_POST['acomptabilise']))
    $acomptabilise="oui";
    if(isset($_POST['editdocument']))
    $editdocument="oui";
    if(isset($_POST['boncaisse']))
    $boncaisse="oui";
    if(isset($_POST['cloturercaisse']))
    $cloturercaisse="oui";
    if(isset($_POST['fermeturercaisse']))
    $fermeturercaisse="oui";
    if(isset($_POST['transfertcaisse']))
    $transfertcaisse="oui";
    if(isset($_POST['showdeletedoperations']))
    $showdeletedoperations="oui";
    if(isset($_POST['affichersolde']))
    $affichersolde="oui";


    $req = $bdd->query("SELECT id from succursale order by succursale asc ");
        while ($ste = $req->fetch())
        {
            if(isset($_POST[$ste['id'].'m']))
                $societe.=strval($ste['id'].",");
        }
        $req->closecursor();

    if($societe<>"")
        $societe=rtrim($societe,", ");


    $reqm = $bdd->prepare('UPDATE users  set  login=:login,password=:password,ste=:ste,supression=:supression,modification=:modification,boncaisse=:boncaisse,cloturercaisse=:cloturercaisse,fermeturercaisse=:fermeturercaisse,transfertcaisse=:transfertcaisse,showdeletedoperations=:showdeletedoperations,affichersolde=:affichersolde,editdateop=:editdateop,exportexcel=:exportexcel,justification=:justification,acomptabilise=:acomptabilise,editdocument=:editdocument,pages=:pages where id=:id');
    $reqm->execute(array('login' => $_POST['loginm'],'password' => $_POST['passwordm'],'id' =>$_POST['iduser'],'supression' => $suppression,'exportexcel' => $exportexcel,'editdateop' => $editdateop,'modification' => $modification,'justification' => $justification,'acomptabilise' => $acomptabilise,'editdocument' => $editdocument,'boncaisse' => $boncaisse,'cloturercaisse' => $cloturercaisse,'fermeturercaisse' => $fermeturercaisse,'transfertcaisse' => $transfertcaisse,'showdeletedoperations' => $showdeletedoperations,'affichersolde' => $affichersolde,'ste' => $societe,'pages' => $pages)); 
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
    
</head>

<body>




<div class="container" style="width:1550px" >


        <div class="header">
            <a href="decon.php" style="color:#424242">[ Se déconnecter ]</a>
        </div><!-- header -->
        <h1 class="main_title">Administration</h1>
        <div class="content">

        <div class="menu">
            
                <ul>
                    <li><a href="#" id="p1" class="active">Utilisateurs (caissiers)</a></li>
                    <li><a href="adminmiseadisposition.php" id="p1"  >Modèle mise à disposition</a></li>
                    <li><a href="index.php" id="p2" class="">Application</a></li>
                </ul>
            
        </div>

<div class="profile" id="pro1" style="width:1350px" >

    <a href="admin.php?add=ok" class="btn" style="display:inline-block;width:75px;margin:5px"><i class="fa fa-plus-circle"></i>  Ajouter </a> 
    <a href="admin.php" class="btn"  style="display:inline-block;width:15px;margin:5px"><i class="fa fa-refresh"></i></a>
<script>
function adminv(login)
{
    if(document.getElementById(login).value=="admin")
    {
        document.getElementById(login).value="";
        alert("entrez un login différent que 'admin' ");
        document.getElementById(login).focus();
    }
}
</script>

<?php  if(isset($_GET['add'])) { ?>
    <div style="margin:5px 10px; " id="add">

    <form action="admin.php" method="post">
    <h2 style="margin:20px 0px 0px 5px" >Ajouter un nouveau utilisateur(caissier) :</h2>
    <input type="text" name="login" id="login" placeholder="Login(caissier)" onblur="adminv(this.id)" required>
    <input type="text" name="password" id="password" placeholder="Password" required>





         <fieldset style="text-align:center;;padding:10px 0px 15px 10px;display:block;margin:10px 0px">
          <legend style="font-size:16px" >Accès aux pages </legend>

            <input type="checkbox" name="societe" id="societe" value="societe"  > <label for="societe" >Caisses</label>
            <input type="checkbox" name="famille" id="famille" value="famille"  > <label for="famille" >Familles et types</label>
            <input type="checkbox" name="sortie" id="sortie" value="sortie"  > <label for="sortie" >Sorties</label>
            <input type="checkbox" name="entree" id="entree" value="entree" > <label for="entree" >Entrées</label> 

        </fieldset>



         <fieldset style="text-align:center;;padding:10px 0px 15px 10px;display:block;margin:10px 0px">
          <legend style="font-size:16px" >Permissions </legend>
            
            <input type="checkbox" name="editdateop" id="editdateop" value="oui" > <label for="editdateop" >Modification date d'opération</label> 


            <input type="checkbox" name="exportexcel" id="exportexcel" value="oui" > <label for="exportexcel" >Export excel</label> 

            <input type="checkbox" name="suppression" id="suppression" value="oui" > <label for="suppression" >suppression</label> 
            <input type="checkbox" name="modification" id="modification" value="oui" > <label for="modification" >Modification</label> 
            <input type="checkbox" name="justification" id="justification" value="oui" > <label for="justification" >Justification d'operations</label> 
            <input type="checkbox" name="acomptabilise" id="acomptabilise" value="oui" > <label for="acomptabilise" >Marquer une operation comme "à comptabiliser ou non"  </label> 
            <input type="checkbox" name="editdocument" id="editdocument" value="oui" > <label for="editdocument" >Update documents</label> 
            <input type="checkbox" name="boncaisse" id="boncaisse" value="oui" > <label for="boncaisse" >Impression bon de caisse</label> 

            <input type="checkbox" name="cloturercaisse" id="cloturercaisse" value="oui" > <label for="cloturercaisse" >clôturer une caisse</label> 

            <input type="checkbox" name="fermeturercaisse" id="fermeturercaisse" value="oui" > <label for="fermeturercaisse" >Fermer une caisse</label> 

            <input type="checkbox" name="transfertcaisse" id="transfertcaisse" value="oui" > <label for="transfertcaisse" >Transfert entre caisses</label> 


            <input type="checkbox" name="affichersolde" id="affichersolde" value="oui" > <label for="affichersolde" >Afficher le solde</label> 


        </fieldset>



         <fieldset style="text-align:center;;padding:10px 0px 15px 10px;display:block;margin:10px 0px">
          <legend style="font-size:16px" >Caisses affectées </legend>
            

            <?php
            $req = $bdd->query("SELECT id,succursale from succursale order by succursale asc ");
            while ($ste = $req->fetch())
            {
             ?> 

                <input type="checkbox" name="<?php echo $ste['id'] ;?>" id="<?php echo $ste['id'] ;?>" value="<?php echo $ste['id'] ;?>" > <label for="<?php echo $ste['id'] ;?>" ><?php echo $ste['succursale'] ;?></label> 

              <?php
            }
            $req->closecursor();
            ?>  


        </fieldset>




    <input type="submit" value="ajouter" name="add" style="width:200px">
    </form>
    </div>
<?php  }  ?>

<?php 

if(isset($_GET['modif'])) { 
$users = $bdd->query('SELECT * from users where id='.$_GET['iduser']);
$user = $users ->fetch();
$users->closecursor();
    ?>
    <div style="margin:10px;" id="modif">
    <h2 style="margin:20px 0px 0px 5px" >Modifier un utilisateur(caissier) :</h2>
    <form action="admin.php" method="post">
    <?php if($user['login']!="admin") { ?>
    <input type="text" name="loginm" id="loginm" placeholder="Login(caissier)" onblur="adminv(this.id)" required value="<?php echo $user['login'] ; ?>">
    <?php }else {?>
    <input type="text" name="loginm" id="loginm" placeholder="Login(caissier)"  readonly value="<?php echo $user['login'] ; ?>">
    <?php }?>  
    <input type="text" name="passwordm" id="passwordm" placeholder="Password" required value="<?php echo $user['password'] ; ?>">
    <input type="hidden" name="iduser" value="<?php echo $user['id'] ; ?>">






         <fieldset style="text-align:center;;padding:10px 0px 15px 10px;display:block;margin:10px 0px">
          <legend style="font-size:16px" >Accès aux pages </legend>

            <input type="checkbox" name="societe" id="societe" value="societe"   <?php if( stristr($user['pages'], 'societe')){ echo "checked" ;} ?> > <label for="societe" >Caisses</label>
            <input type="checkbox" name="famille" id="famille" value="famille"    <?php if( stristr($user['pages'], 'famille')){ echo "checked" ;} ?>> <label for="famille" >Familles et types</label>
            <input type="checkbox" name="sortie" id="sortie" value="sortie"   <?php if( stristr($user['pages'], 'sortie')){ echo "checked" ;} ?> > <label for="sortie" >Sorties</label>
            <input type="checkbox" name="entree" id="entree" value="entree"  <?php if( stristr($user['pages'], 'entree')){ echo "checked" ;} ?> > <label for="entree" >Entrées</label> 

        </fieldset>




         <fieldset style="text-align:center;;padding:10px 0px 15px 10px;display:block;margin:10px 0px">
          <legend style="font-size:16px" >Permissions </legend>
            

            <input type="checkbox" name="editdateop" id="editdateopm" value="oui"  <?php if($user['editdateop']=="oui"){ echo "checked" ;} ?>  > <label for="editdateopm" >Modification date d'opération</label> 


            <input type="checkbox" name="exportexcel" id="exportexcelm" value="oui"  <?php if($user['exportexcel']=="oui"){ echo "checked" ;} ?>  > <label for="exportexcelm" >Export excel</label> 

            <input type="checkbox" name="suppression" id="suppressionm" value="oui"  <?php if($user['supression']=="oui"){ echo "checked" ;} ?>  > <label for="suppressionm" >suppression</label> 
            <input type="checkbox" name="modification" id="modificationm" value="oui" <?php if($user['modification']=="oui"){ echo "checked" ;} ?>> <label for="modificationm" >Modification</label> 
            <input type="checkbox" name="justification" id="justificationm" value="oui" <?php if($user['justification']=="oui"){ echo "checked" ;} ?>> <label for="justificationm" >Justification d'operations</label> 


            <input type="checkbox" name="acomptabilise" id="acomptabilisem" value="oui" <?php if($user['acomptabilise']=="oui"){ echo "checked" ;} ?>> <label for="acomptabilisem" >Marquer une operation comme "à comptabiliser ou non" </label> 


            <input type="checkbox" name="editdocument" id="editdocumentm" value="oui" <?php if($user['editdocument']=="oui"){ echo "checked" ;} ?>> <label for="editdocumentm" >Update documents</label> 


            <input type="checkbox" name="boncaisse" id="boncaissem" value="oui" <?php if($user['boncaisse']=="oui"){ echo "checked" ;} ?>> <label for="boncaissem" >Impression bon de caisse</label> 



            <input type="checkbox" name="cloturercaisse" id="cloturercaissem" value="oui" <?php if($user['cloturercaisse']=="oui"){ echo "checked" ;} ?>> <label for="cloturercaissem" >clôturer une caisse</label> 



            <input type="checkbox" name="fermeturercaisse" id="fermeturercaissem" value="oui" <?php if($user['fermeturercaisse']=="oui"){ echo "checked" ;} ?>> <label for="fermeturercaissem" >Fermer une caisse</label> 


            <input type="checkbox" name="transfertcaisse" id="transfertcaissem" value="oui" <?php if($user['transfertcaisse']=="oui"){ echo "checked" ;} ?>> <label for="transfertcaissem" >Transfert entre caisses</label> 




            <input type="checkbox" name="affichersolde" id="affichersoldem" value="oui" <?php if($user['affichersolde']=="oui"){ echo "checked" ;} ?>> <label for="affichersoldem" >Afficher le solde</label> 



        </fieldset>




         <fieldset style="text-align:center;;padding:10px 0px 15px 10px;display:block;margin:10px 0px">
          <legend style="font-size:16px" >Caisses affectées </legend>
            

            <?php

            $stes= explode( ',', $user['ste']);
            $steuser= "";
            $lines_total = count($stes);
            for ($i = 0; $i < $lines_total; $i++) 
            {
            $steuser = $steuser."'".$stes[$i]."',";
            }
            $steuser=rtrim($steuser,",");



            $req = $bdd->query("SELECT id,succursale from succursale order by succursale asc ");
            while ($ste = $req->fetch())
            {
             ?> 

                <input type="checkbox" name="<?php echo $ste['id'].'m' ;?>" id="<?php echo $ste['id'] ;?>" value="<?php echo $ste['id'] ;?>"  <?php if(stristr($steuser, "'".$ste['id']."'")){ echo "checked" ;} ?>  > <label for="<?php echo $ste['id'] ;?>" ><?php echo $ste['succursale'] ;?></label> 

              <?php
            }
            $req->closecursor();
            ?>  


        </fieldset>
        


    <input type="submit" value="Modifier" name="modif" style="width:200px">
    </form>
    </div>
<?php  }  ?>
    <hr/>

    <table id="matable" style="max-width:1200px"  >
                            <tr>
                                <th >Login(caissier)</th>
                                <th >Password</th>
                                <th >Accés aux pages</th>
                                <th >Caisses affectées</th>
                                <th >Modification date opération</th>
                                <th >Export excel</th>
                        
                                <th >suppression</th>
                                <th >Modification</th>
                                <th >Justification d'operations</th>
                                <th >à comptabiliser</th>
                                <th >Update documents</th>
                                <th >Impression bon de caisse</th>
                                <th >clôturer une caisse</th>
                                <th >Fermer une caisse</th>
                                <th >Transfert entre caisses</th>
                                <th >Afficher le solde</th>

                                <th >Modifier</th>
                                <th >Supprimer</th>                                                                                       
                                
                            </tr>

                            <?php

                             

                                $req = $bdd->query('SELECT * from users order by id desc');
                                


                                while ($donnees = $req->fetch())
                                {
                            ?>
                            <tr>
                                <td><?php echo $donnees['login']; ?></td>
                                <td><?php echo $donnees['password']; ?></td>
                                <td ><?php echo $donnees['pages']; ?></td>

                                <td styl="max-width:150px">
                                <?php 

                                    $stes = explode(",", $donnees['ste']);

                                    foreach($stes as $steid) 
                                    {

                                        if($steid<>"")
                                        {
                                            $reqs = $bdd->query("SELECT succursale from succursale where id=".trim($steid));
                                            $ste = $reqs->fetch();
                                            $reqs->closecursor();  
                                            echo $ste['succursale'].", ";
                                        }
                                      
                                    }
                                    
                                    
                                ?>
                                </td>



                                <td><?php echo $donnees['editdateop']; ?></td>
                                <td><?php echo $donnees['exportexcel']; ?></td> 
                                <td><?php echo $donnees['supression']; ?></td> 
                                <td><?php echo $donnees['modification']; ?></td> 
                                <td><?php echo $donnees['justification']; ?></td> 
                                <td><?php echo $donnees['acomptabilise']; ?></td> 
                                <td><?php echo $donnees['editdocument']; ?></td> 
                                <td><?php echo $donnees['boncaisse']; ?></td> 
                                <td><?php echo $donnees['cloturercaisse']; ?></td> 
                                <td><?php echo $donnees['fermeturercaisse']; ?></td> 
                                <td><?php echo $donnees['transfertcaisse']; ?></td>
                                <td><?php echo $donnees['affichersolde']; ?></td>
                                 

                                <td><a href="?iduser=<?php echo $donnees['id'] ; ?>&amp;modif=ok" id="val" ><i class="fa fa-pencil-square-o"></i> Modifier</a></td> 

                                <?php if($donnees['login']!="admin"){ ?>
                                <td><a href="#<?php echo $donnees['id']; ?>" id="sup" onclick="document.getElementById('<?php echo $donnees['id']; ?>c').style.display='block';" >Supprimer</a>
                                        <div class="del" id="<?php echo $donnees['id']; ?>c" style="width:85px">  
                                            <a href="#" onclick="location.href='admin.php?iduser=<?php echo $donnees['id']; ?>&amp;sup=ok';"  style="display:inline;" >Oui</a>&nbsp;&nbsp;-&nbsp;
                                            <a href="#" onclick="document.getElementById('<?php echo $donnees['id']; ?>c').style.display='none';" style="display:inline;" >Non</a>
                                        </div>
                                </td>
                                <?php }else{ ?>
                                <td>Supprimer</td>
                                <?php } ?>
                            </tr>
                            <?php
                                }   
                                $req ->closeCursor();                 
                            ?>
    </table> 
</div>

<div style="background:#ddd;text-align:center;padding:10px;font-size:14px;margin-top:50px;border-radius:3px">
Développée par : <strong>A.loutfi</strong>  |  Email: <strong>Loutfi.info@gmail.com</strong>  |  Téléphone: <strong>0601-810237</strong>   
</div>

<div style="background:#ddd;text-align:center;padding:10px;font-size:14px;margin-top:10px;border-radius:3px">
 <strong>IMPORTANT: </strong> L'application gestion des espèces multi-caisses est uniquement concédée sous licence aux fins d'une utilisation non commerciale pour vos besoins opérationnels internes. Le terme « Utilisation non commerciale » signifie que vous ne pouvez pas vendre, louer, donner à bail, ni prêter ce qui est produit au moyen de l'application. Toute autre forme d'utilisation nécessite l'achat d'une licence gestion des espèces multi-caisses
</div>


<br/>
</body>
</html>