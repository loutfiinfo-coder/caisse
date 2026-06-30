<?php
include("config.php");




if(isset($_SESSION['idcaisse']))
{
header('location:index.php'); 
}








if(isset($_POST['submit']))
{
  if(!empty($_POST['log']) and !empty($_POST['pw']))

  {



		$req= $bdd->prepare('select id from users where login=:log and password=:pw');
		$req->execute(array(
			'log'=>($_POST['log']),
			'pw'=>($_POST['pw'])
			));
		$id=$req->fetch();


		if(!empty($id['id']))
		{




			session_start();
			$_SESSION['idcaisse']=$id['id'];

			//cookie
			if(isset($_POST['memo']))
			{
				setcookie('logcaisse',$_POST['log'] , time() + 365*24*3600);
				setcookie('pwcaisse' ,$_POST['pw'], time() + 365*24*3600);
			}	
			else
			{
				setcookie('logcaisse','');
				setcookie('pwcaisse' ,'');
			}
			if($_POST['log']=="admin")
			header('location:admin.php');
			else
			header('location:index.php');


			

		}
		$req->closecursor();
  }


}
?>
<!DOCTYPE html>

<!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="fr"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>log in</title>
<link rel="icon" type="image/ico" href="logo.ico" />
<link rel="stylesheet" type="text/css" href="conn.css" />
</head>
<body>

<img src="logo1.png" style="display:block;margin: 50px auto -50px auto;width:281px" >
<div class="container">
	<section id="content">
		<form action="" method="post">
			<h1>Login Form</h1>
		<?php
			if(isset($_POST['submit']))
			{
				if(empty($id['id'])){
					echo '<div  class="error">Username ou login est incorrect</div>';	
				}
			
			}

		?>
			<div>
				<input type="text" name="log" placeholder="Username" required="" id="username" <?php if(isset($_COOKIE['logcaisse'])){echo ' value="'.$_COOKIE["logcaisse"].'" ';} else {if(isset($_POST['log'])){echo ' value="'.$_POST["log"].'" ';}}?>  />
			</div>

			<div>
				<input type="password" name="pw" placeholder="Password" required="" id="password" <?php if(isset($_COOKIE['pwcaisse'])){echo ' value="'.$_COOKIE["pwcaisse"].'" ';} else {if(isset($_POST['pw'])){echo ' value="'.$_POST["pw"].'" ';}}?>  />
			</div>
			<div class="memo">
				<input type="checkbox" name="memo"  id="login-check" <?php if(isset($_COOKIE['logcaisse'])){echo ' checked ';}?> /><label for="login-check">Remember me</label>
			</div>
			<div>
				<input type="submit" name="submit" value="Log in" />
			</div>
		</form><!-- form -->
	</section><!-- content -->
</div><!-- container -->
</body>
</html>