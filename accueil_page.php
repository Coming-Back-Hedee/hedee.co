<!-- <?php
  $bdd = new PDO('mysql:host=localhost;dbname=coming_back', 'root', 'root');
?> -->

<!doctype html>
<html>
  <head>
    <title>Coming-Back</title>
<?php require 'head.php'; ?>
  </head>
  <body>
<?php require 'header.php'; ?>
    <div class="container">
      <div class="accueil">
        <h1>Demander un remboursement de la différence</h1>
        <div class="row">
          <div class="col-md-4 col-md-offset-4">
            <div class="etapes">
              <ol>
                <li>TESTEZ votre éligibilité</li>
                <li>COMPLETER vos données</li>
                <li>VALIDER la demande</li>
              </ol>              
            </div>  
          </div>
        </div>
        <form action="test_eligibilite.php" class="navbar-form" name="search_enseigne2">
          <div class="input-group">
            <input list="enseignes2" type="text" class="form-control" placeholder="ex: Darty" name="enseigne">
            <div class="input-group-btn">
              <datalist id="enseignes2">
                <?php                    
                  $enseignes = $bdd->query('SELECT * FROM enseignes');
                  while($enseigne = $enseignes->fetch()){
                ?>
                    <option value="<?php echo htmlspecialchars($enseigne['nom_enseigne']) ?>"> </option>
                <?php
                  }
                ?>
              </datalist>
              <?php 
                if(isset($_POST['enseigne2'])){
                  $nom_enseigne = htmlspecialchars($_POST['search_enseigne']);
                  echo $nom_enseigne;
                }
              ?>
              <button id="form_submit" class="btn btn-default" type="submit">
                Demander
              </button>
            </div>
          </div>
        </form>
        <p>Les enseignes partenaires : <?php $enseignes_query = $bdd->query('SELECT * FROM enseignes'); 
                                        while($enseigne = $enseignes_query->fetch()){ echo '<a = href=test_eligibilite.php?enseigne=' . $enseigne['nom_enseigne'] . '>' . htmlspecialchars($enseigne['nom_enseigne']) . '</a> '; }?>
        </p>           
      </div>

      <div class="liste_enseignes">
        <div class="row">
          <div class="col-md-4">
            <h3>Electroménager</h3>
          </div>
          <div class="col-md-4">
            <h3>TV</h3>
          </div>
          <div class="col-md-4">
            <h3>Téléphonie</h3>
          </div>
        </div> 
      </div>      
    </div>

    <footer>
<?php require 'footer.php'; ?>
    </footer>

  </body>
  <script type="text/javascript">
    
    var recherche_enseigne = document.getElementsByName("enseigne");
    for(i=0;i<recherche_enseigne.length;i++){
      console.log(recherche_enseigne[i]);
      recherche_enseigne[i].addEventListener("change", function(){
        console.log(this)
        this.form.submit();
      })
    }
    
  </script> 
</html>