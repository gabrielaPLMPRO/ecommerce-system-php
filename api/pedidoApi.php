<?php
header("Content-Type: application/json; charset=UTF-8");

require "../fachada.php"; 

$dao = $factory->getPedidoDao();
$daoItensPedido = $factory->getItensPedidoDao();

$request_method=$_SERVER["REQUEST_METHOD"];
	
switch($request_method){
   case 'GET':
      if (!empty($_GET["numero"]) || !empty($_GET["nome"])) 
      {
         $numero = isset($_GET["numero"]) ? intval($_GET["numero"]) : null;
         $nome = isset($_GET["nome"]) ? $_GET["nome"] : null;

         $pedidoJSON = $dao->buscaPedidosFiltradosJSON( $daoItensPedido, $numero, $nome);

         if($pedidoJSON!=null) {
            echo $pedidoJSON;
            http_response_code(200); // 200 OK
         } else {
               http_response_code(404); // 404 Not Found
         }
      }
      else
      {
         echo $dao->buscaPedidosJSON($daoItensPedido);
         http_response_code(200); // 200 OK
      }
      break;
   case 'OPTIONS':
      if(!empty($_GET["id"]))
      {
         $id=intval($_GET["id"]);
         $dao->remove($id);
         http_response_code(204); // 204 Deleted
      }
      break;
   default:
      // Invalid Request Method
      http_response_code(405); // 405 Method Not Allowed
      break;
}
 