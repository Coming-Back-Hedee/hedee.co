


  <div class="container-fluid header">
    <div class="navbar-header">
      <a class="navbar-brand" href="accueil.php"><img src="img/coming-back-logo-fond-blanc-horizontal.png" alt="Logo" class="brand-logo" width="150" height="50"></a>
    </div>

    <form class="navbar-form navbar-right" action="test_eligibilite.php" name="search_enseigne">
      <div class="input-group barre_recherche">
        <input list="enseignes1" type="text" class="form-control" placeholder="ex: Darty" name="enseigne">
        <datalist id="enseignes1">
					<?php
						  
						/*$enseignes = $bdd->query('SELECT * FROM enseignes');*/
						while($enseigne = $enseignes->fetch()){
					?>
							<option value="<?php echo htmlspecialchars($enseigne['nom_enseigne']) ?>"> </option>
						<?php
						 }
						?>
			</datalist>
        <div class="input-group-btn">
          <button class="btn btn-default" type="submit">
            <i class="glyphicon glyphicon-search"></i>
          </button>
        </div>
      </div>
    </form>
  </div>

	<nav class="navbar navbar-inverse">
		<div class="navigation1">
	    <ul class="nav navbar-nav">
	      <li class="dropdown">
	        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Catégories
	        <span class="caret"></span></a>
	        <ul class="dropdown-menu">
						<?php
						  
						  /*$categories = $bdd->query('SELECT * FROM categories LIMIT 0, 10');*/
						  while($categorie = $categories->fetch()){
						  	?>
						  	<li><a href="#"><?php echo htmlspecialchars($categorie['nom_categorie']) ?></a></li>
						  <?php
						  }
						?>
<!-- 	          <li><a href="#">Page 1-1</a></li>
	          <li><a href="#">Page 1-2</a></li>
	          <li><a href="#">Page 1-3</a></li> -->
	        </ul>
	      </li>
	      <li><a href="#">Avantages Coming-Back</a></li>
	    </ul>
	  </div>
	  <div class="navigation2">
	    <ul class="nav navbar-nav navbar-right">
	      <li><a href="#">FAQ</a></li>
	      <li><a href="#">Blog</a></li>
	    </ul>
    </div>
 	</nav>