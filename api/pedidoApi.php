<?php
header("Content-Type: application/json; charset=UTF-8");

require "../fachada.php"; 

$dao = $factory->getPedidoDao();
$daoItensPedido = $factory->getItensPedidoDao();

$request_method=$_SERVER["REQUEST_METHOD"];

if (
    $request_method === 'POST' &&
    isset($_SERVER['CONTENT_TYPE']) &&
    stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
) {
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($dados)) {
        $_POST = array_merge($_POST, $dados);
    }
}
	
switch($request_method){
   case 'POST':
      if (!empty($_POST["numero"]) || !empty($_POST["nome"])) 
      {
         $numero = isset($_POST["numero"]) ? intval($_POST["numero"]) : null;
         $nome = isset($_POST["nome"]) ? $_POST["nome"] : null;

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
      echo stripslashes(json_encode('POST',JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
      http_response_code(200);
      break;
   default:
      // Invalid Request Method
      http_response_code(405); // 405 Method Not Allowed
      break;
}
 