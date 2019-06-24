<?php

require('model.php');

$bdd = bddConnect();

$enseignes = getAllEnseignes();
$categories = getAllCategories();

require('headerView.php');
