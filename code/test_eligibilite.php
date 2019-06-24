
<!doctype html>
<html>
  <head>
    
	<?php require 'head.php'; 
		$nom_enseigne = htmlspecialchars($_GET['enseigne']);
	?>
		<title>Remboursement chez <?php echo $nom_enseigne; ?></title>
  </head>
<?php
  $nom_enseigne = $_GET['enseigne'];
?>
  <body>
<?php require 'header.php'; ?>
		<div class="container">
			<section class="info_enseigne">
				<h1 class="text-center">Remboursement chez <?php echo $nom_enseigne; ?></h1>
				<p><?php 
					  $enseigne_query = $bdd->prepare('SELECT * FROM enseignes WHERE nom_enseigne = ?');
					  $enseigne_query->execute(array($nom_enseigne));
					  $enseigne = $enseigne_query->fetch();
						echo $enseigne['info_enseigne']; 
						?></p>
			</section>
			<div class="row icons">
					<div class="col-sm-4 col-md-4 col-lg-4">
						<figure>
							<p><img src="img/icon/raise-your-hand-to-ask.png" alt="Logo" class="icon_logo1" width="100" height="100"/></p>
							<p><figcaption>1. Testez votre éligilité</figcaption></p>				
						</figure>
					</div>
					<div class="col-sm-4 col-md-4 col-lg-4">
						<figure>
							<p><img src="img/icon/contract.png" alt="Logo" class="icon_logo" width="100" height="100"/></p>
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
			<div class="form_eligibilite">
				<form id="form_e" method="post">
	        <label for="nom_enseigne"> Enseigne du produit acheté </label>
	        <input list="enseignes2" id="nom_enseigne" type="text" class="form-control eligibilite" name="nom_enseigne">
            <datalist id="enseignes2">
            	<?php                 
              $enseignes_query = $bdd->query('SELECT * FROM enseignes');
              while($enseigne = $enseignes_query->fetch()){
            	?>
              <option value="<?php echo htmlspecialchars($enseigne['nom_enseigne']) ?>"> </option>
              <?php
                  }
            	?>
            </datalist>
	        <label for="date_achat"> Date d'achat </label>
	        <input id="date_achat" type="date" class="form-control eligibilite" name="date_achat">
	        <label for="prix_achat"> Prix d'achat </label>
	        <div class="input-group">
		        <input id="prix_achat" type="text" class="form-control eligibilite"  name="prix_achat">
		        <div class="input-group-btn">
		          <button class="btn btn-default" type="submit">
		            <i class="glyphicon glyphicon-euro"></i>
		          </button>
		        </div>
		      </div>
	        <label for="article_categorie"> Catégorie de l'article </label>
	        <input list="categories" id="article_categorie" type="text" class="form-control eligibilite" name="article_categorie">
            <datalist id="categories">
            	<?php 
						  
						  $categories_query = $bdd->query('SELECT * FROM categories');
						  while($categorie = $categories_query->fetch()){
						  	?>
						  	<option value="<?php echo htmlspecialchars($categorie['nom_categorie']) ?>"></option>
						  <?php
						  }
						?>               
            </datalist>
	        <label for="marque_et_ref"> Marque et référence de l'article </label>
	        <input id="marque_et_ref" type="text" class="form-control eligibilite" name="marque_et_ref">
          <div class="choix_remise"><label for="test_remise"> Avez-vous bénéficié d'une remise ? </label>
            <input type="radio" name="test_remise" value="M" id="oui_remise" class="form-control eligibilite" onchange="boucle();"/> <label for="oui_remise">Oui</label>
            <input type="radio" name="test_remise" value="F" id="non_remise" class="form-control eligibilite" onchange="boucle();"/> <label for="non_remise">Non</label>
          </div>	        
				</form>
				<div id="result_eligibilite" class="result_eligibilite">Veuillez-remplir toutes les informations du formulaire</div>
				<div id="next_step" style="display:none;">
					<a href=formulaire.php?enseigne=<?php echo $nom_enseigne; ?>><button class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Complétez vos données</button></a>
				</div>
			</div>

		</div>

    <footer>
<?php require 'footer.php'; ?>
    </footer>

  </body>
  <script type="text/javascript">

  	var test_remise = false;
  	var test = document.getElementsByClassName("eligibilite");
	  
	  var boucle = function(){
		  if(document.getElementById("non_remise").checked == true){
		  	test_remise = true;
		  	let missing = 0;
				for(i=0;i<test.length;i++){
		  		if(test[i].value == ""){
		  			test_remise = false;
		  			missing += 1;
				  	document.getElementById("result_eligibilite").innerText = "Veuillez-remplir toutes les informations du formulaire";
				  	document.getElementById("result_eligibilite").style.backgroundColor = "red";
				  	test[i].addEventListener("input", function(){missing -=1;});				  		  			
		  		}
		  		console.log(missing);
		  	}  			  	
		  }
		  if(document.getElementById("oui_remise").checked == true){
		  	test_remise = false;
		  	document.getElementById("result_eligibilite").innerText = "Vous n'êtes pas éligible à un remboursement";
		  	document.getElementById("result_eligibilite").style.backgroundColor = "red";
		  	document.getElementById("next_step").style.display = "none";
		  }

			if(test_remise){
				document.getElementById("result_eligibilite").innerText = "Vous êtes éligible à un remboursement";
				document.getElementById("result_eligibilite").style.backgroundColor = "green";
				document.getElementById("next_step").style.display = "block";
			}
		}

  	
  	window.onload = boucle();
  	setInterval(function(){boucle() }, 4000);

  </script> 
</html>