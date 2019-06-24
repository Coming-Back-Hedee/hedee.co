<?php
try {
  $bdd = new PDO('mysql:host=localhost;dbname=coming_back', 'root', 'root');
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	require_once 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
	
	$spreadsheet_url = '"https://docs.google.com/spreadsheets/d/e/2PACX-1vQxJnlj_jCAwyl-FP6Vct5z5fquF-WJyRmQ7SEQMkBsB9sBLRI-2wC7dykKUwsd9YmlgjbBTeaEO8iN/pub?output=xlsx"';
	$file_url = '"C:/UwAmp/www/c-b2018/test.xlsx"';

	$curl_commande = "curl $spreadsheet_url > $file_url ";
	//$curl_commande = 'curl --output "C:\UwAmp\www\c-b2018\test.xlsx" $spreadsheet_url';
	shell_exec($curl_commande);

	/*exec("curl $spreadsheet_url > 'C:\UwAmp\www\c-b2018\bdd\bdd_categorie.xlsx'");*/
	// Chargement du fichier Excel
	$objPHPExcel = PHPExcel_IOFactory::load("bdd/bdd_categorie.xlsx");
	 
	// Sélection de la première feuille du fichier Excel
	$current_sheet = 0;
	$test = true;

	
	while($test){
		// Test sur l'existence de la feuille d'index $current_sheet à chaque incrémentation
		try{
			$sheet = $objPHPExcel->getSheet($current_sheet);

		// Le nom de la base de données se trouve sur la 1ère cellule de la feuille
			$nom_bdd = $sheet->getCell('A1')->getValue();

			$sql = "SHOW TABLES FROM coming_back LIKE '$nom_bdd'";
			$result = $bdd->query($sql);
			$donnees = $result->rowCount();
			
			// Si la base de données n'existe pas encore : création
			if($donnees == 0){
				create_table_from_xlsx($bdd, $sheet, $nom_bdd);
				$fin_maj = $sheet->getHighestRow();
				insert_into_table_from_xlsx($bdd, $sheet, $nom_bdd, 3, $fin_maj);
			}
			// Si la base de données existe déjà : mise à jour 
			if($donnees == 1){
				$taille_bdd_req = "SELECT * from $nom_bdd";
				$taille_bdd_query = $bdd->query($taille_bdd_req);
				$taille_bdd = $taille_bdd_query->rowCount();
				$debut_maj = $taille_bdd + 3;
				$fin_maj = $sheet->getHighestRow();
				insert_into_table_from_xlsx($bdd, $sheet, $nom_bdd, $debut_maj, $fin_maj);							
			}
		}

		catch(PHPExcel_Exception $e){
			//echo $e->getMessage();
			$test = false;
		}
		// Incrémentation de l'index de la feuille
		$current_sheet += 1;
	}

}
catch(PDOException $e){
  echo $e->getMessage();
  }

 
function create_table_from_xlsx($bdd, $sheet, $nom_bdd){
	$row = $sheet->getRowIterator(2)->current();
	$create_table = "CREATE TABLE $nom_bdd (";
	foreach ($row->getCellIterator() as $cell) {
		$cell_value = explode(";", $cell->getValue());
		$item = $cell_value[0];
		$item .= " " ;
		for($i=1;$i<count($cell_value);$i++){
			$item .= strtoupper($cell_value[$i]);
			$item .= " " ;				
		}
		$create_table .= $item;		
	}
	$create_table .= ")" ;
	print_r($create_table);
	$bdd->exec($create_table);	
}

function insert_into_table_from_xlsx($bdd, $sheet, $nom_bdd, $start, $end){
	for($num_row=$start; $num_row<=$end; $num_row++){
		$row_maj = $sheet->getRowIterator($num_row)->current();
		$tab_attributs = array();
		foreach ($row_maj->getCellIterator() as $cell_maj) {
			$cell_push = "'". $cell_maj->getValue() . "'";
			array_push($tab_attributs, $cell_push);
		}
		$row_values = "(";
		$row_values .= implode(",", $tab_attributs);
		$row_values .= ")";
		$insert_table = "INSERT INTO $nom_bdd VALUES $row_values";
		//print_r($insert_table);
		try{
			$bdd->exec($insert_table);
			echo $row_values . " a bien été ajouté à la table ". $nom_bdd . "<br>";
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}					
	}
}

?>