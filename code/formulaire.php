<!-- <?php
  /*$bdd = new PDO('mysql:host=localhost;dbname=coming_back', 'root', 'root');*/
?> -->

<!doctype html>
<html>
  <head>
<?php require 'head.php'; ?>
	<?php require 'head.php'; 
		$nom_enseigne = htmlspecialchars($_GET['enseigne']);
	?>
	<title>Remboursement chez <?php echo $nom_enseigne; ?></title>
  </head>
  <body>
<?php require 'header.php'; ?>
		<div class="container">
			<div class="row icons">
					<div class="col-sm-4 col-md-4 col-lg-4">
						<figure>
							<p><img src="img/icon/raise-your-hand-to-ask.png" alt="Logo" class="icon_logo2" width="100" height="100"/></p>
							<p><figcaption>1. Testez votre éligilité</figcaption></p>				
						</figure>
					</div>
					<div class="col-sm-4 col-md-4 col-lg-4">
						<figure>
							<p><img src="img/icon/contract.png" alt="Logo" class="icon_logo1" width="100" height="100"/></p>
							<p><figcaption>2. Complétez vos données</figcaption></p>		
						</figure>
					</div>
					<div class="col-sm-4 col-md-4 col-lg-4">
						<figure>
							<p><img src="img/icon/checked.png" alt="Logo" class="icon_logo" width="100" height="100"/></p>
							<p><figcaption class="text-icon">3. Valider la prise en charge</figcaption></p>				
						</figure>
					</div>					
			</div>
			<div class="form_client">
				<form method="post">
          <div class="choix_civilite"><label for="civilite"> Civilité </label>
            <input type="radio" name="civilite" value="M" id="masculin" /> <label for="M">M</label>
            <input type="radio" name="civilite" value="F" id="feminin" /> <label for="F">Mme</label>
          </div>
	        <label for="nom"> Nom </label>
	        <input id="nom" type="text" class="form-control" name="nom">
	        <label for="prenom"> Prénom </label>
	        <input id="prenom" type="text" class="form-control"  name="prenom">
	        <label for="code_postal"> Code postal </label>
	        <input id="code_postal" type="text" class="form-control" name="code_postal">
	        <label for="mail"> Adresse email </label>
	        <input id="mail" type="mail" class="form-control" name="mail">
	        <label for="telephone"> Numéro de téléphone </label>
	        <input id="telephone" type="text" class="form-control" name="telephone">
	        <label for="num_commande"> Numéro de commande </label>
	        <input id="num_commande" type="text" class="form-control" name="num_commande" required="false">

	        <!-- <button class="btn btn-link btn-lg" type="submit" name="rembourser"><h4><span class="glyphicon glyphicon-star-empty"></span></h4></button>
 -->				</form>				
			</div>
	</div>

    <footer>
<?php require 'footer.php'; ?>
    </footer>

  </body>    
</html>