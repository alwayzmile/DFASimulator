<?php
class DFA {
  public $trans;
  public $num_of_states;
  public $cur_state;
  
  public function __construct() {
    $this->num_of_states = 0;
    $this->trans = array(array());
    $this->cur_state = 0;
  }
  
  // create DFA from file with file name $fName
  public function getFromFile($fName) {
    $contents = file_get_contents($fName);
    $this->getFromString($contents);
  }
  
  // create DFA from string $contents
  public function getFromString($contents) {
    while ($contents != '') {
      $line = substr($contents, 0, strpos($contents, "\n"));
      $this->trans[$this->num_of_states] = explode(' ', $line);
      $this->num_of_states++;
      $contents = substr($contents, strpos($contents, "\n") + 1);
    }
  }
  
  public function displayTransition() {
    for ($i = 0; $i < $this->num_of_states; $i++) {
      echo $this->trans[$i][0] , ' ' , $this->trans[$i][1] , '<br />';
    }
  }
  
  public function transitionToTr() {
    $output = "";
    for ($i = 0; $i < $this->num_of_states; $i++) {
      if ($i == 0)
        $output .= "<tr id=\"s$i\" class=\"cur_state\">";
      else
        $output .= "<tr id=\"s$i\">";
      $output .= "<td><strong>$i</strong></td>";          // state name
      $output .= "<td>" . $this->trans[$i][0] . "</td>";  // if input 0
      $output .= "<td>" . $this->trans[$i][1] . "</td>";  // if input 1
      $output .= "</tr>";
    }
    
    return $output;
  }
  
  public function isAccepted($inputs) {
    for ($i = 0; $i < strlen($inputs); $i++) {
      $input = $inputs[$i];
      if ($this->isValidInput($input))
        $this->cur_state = $this->nextState($input);
      else
        return false;
    }
    
    $fin_state = $this->num_of_states - 1;
    if ($this->cur_state == $fin_state)
      return true;
    else
      return false;
  }
  
  public function getStateSequence($inputs) {
    $seq = "0";
    $this->cur_state = 0;
    for ($i = 0; $i < strlen($inputs); $i++) {
      $input = $inputs[$i];
      $this->cur_state = $this->nextState($input);
      $seq .= " > " . $this->cur_state;
    }
    
    return $seq;
  }
  
  // update the current state after reading an input
  // $input is 0 or 1
  private function nextState($input) {
    if ($input == '0')
      return (int)$this->trans[$this->cur_state][0];
    elseif ($input == '1')
      return (int)$this->trans[$this->cur_state][1];
  }
  
  private function isValidInput($input) {
    return ($input == '0' || $input == '1');
  }
}
?>