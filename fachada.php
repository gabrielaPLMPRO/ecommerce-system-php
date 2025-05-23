<?php

include_once('models/Fornecedor.php');
include_once('models/Endereco.php');
include_once('models/Produto.php');
include_once('dao/DaoFactory.php');
include_once('dao/PostgresDaoFactory.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$factory = new PostgresDaofactory();

?>
