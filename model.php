<?php

function bddConnect(){
    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=coming_back;charset=utf8', 'root', 'root');
        return $bdd;
    }
    catch(Exception $e)
    {
        die('Erreur : '.$e->getMessage());
    }
}

function getAllCategories(){
		$bdd = bddConnect();
		$req =  $bdd->query('SELECT * FROM categories');

		return $req;
}

function getAllEnseignes(){
		$bdd = bddConnect();
		$req =  $bdd->query('SELECT * FROM enseignes');

		return $req;
}