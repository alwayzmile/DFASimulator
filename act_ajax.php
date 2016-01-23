<?php
session_start();
require_once('DFA.php');

$data = null;
if (isset($_GET['input'])) {
  $objDFA = new DFA;
  $objDFA->getFromString($_SESSION['states']);
  //echo $_SESSION['states'];
  
  if ($objDFA->isAccepted($_GET['input']))
    $data['output'] = "ACCEPTED";
  else
    $data['output'] = "REJECTED";
  
  $data['cur_state'] = $objDFA->cur_state;
  $data['sequence'] = $objDFA->getStateSequence($_GET['input']);
}

echo json_encode($data);
?>