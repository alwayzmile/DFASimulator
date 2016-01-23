<?php
session_start();
require_once("DFA.php");

if (isset($_POST['submit'])) {
  $_SESSION['states'] = file_get_contents($_FILES['inputFile']['tmp_name']);
  
  $objDFA = new DFA;
  $objDFA->getFromFile($_FILES['inputFile']['tmp_name']);
  //echo $objDFA->getStateSequence("011");
  //$objDFA->displayTransition();
  //echo '<br />';
  /*
  if ($objDFA->isAccepted("011"))
    echo "ACCEPTED";
  else
    echo "REJECTED";
  */
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>DFA Simulator</title>
  </head>

  <body>
    <div class="container">

      <form method="post" enctype="multipart/form-data">
        <h2>DFA Simulator</h2>
        <label for="inputFile">Load transition table from file: </label>
        <input name="inputFile" type="file" id="fileTrans" class="form-control" placeholder="Upload File" required autofocus>
        <button class="btn btn-md btn-primary btn-block" id="btnLoadFile" name="submit" type="submit">Load File</button>
      </form>

      <div class="alert alert-info" id="fName"></div>
      <table class="table table-sm table-nonfluid center-table table-bordered">
        <thead class="thead-inverse">
          <tr>
            <th>State</th>
            <th>0</th>
            <th>1</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (isset($_POST['submit'])) {
            echo $objDFA->transitionToTr();
          }
          ?>
        </tbody>
      </table>
      
      <form id="formInputString">
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon3">Input string</span>
          <input type="text" class="form-control" id="inputString" aria-describedby="basic-addon3" />
        </div>
        <div class="alert alert-info" id="outputDFA">
          Type the input string on the textbox above
        </div>
      </form>
      
    </div>
    
    <script type="text/javascript" src="jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
      <?php
      if (isset($_POST['submit'])) {
        echo '$("table").show();';
        echo '$("#fName").html("File <strong>' . $_FILES['inputFile']['name'] . '</strong> successfully loaded with the following data");';
        echo '$("#fName").show();';
        echo '$("#formInputString").show();';
        echo '$("#outputDFA").show();';
      } else {
        echo '$("table").hide();';
        echo '$("#fName").hide();';
        echo '$("#formInputString").hide();';
        echo '$("#outputDFA").hide();';
      }
      ?>
      
      jQuery('#inputString').on('input', function() {
        $(this).val($(this).val().replace(/[^0-1]/g,'') );
        
        $.ajax({
          type	   : 'get',
          url		   : 'act_ajax.php',
          data     : 'input=' + $('#inputString').val(),
          dataType : 'json',
          success	 : function(response) {
            var output = $('#outputDFA');
            
            output.html("<strong>" + response['output'] + "</strong>");
            output.show();
            $('tr').removeClass('cur_state');
            $('#s' + response['cur_state']).addClass('cur_state');
        
            if ($('#inputString').val().length == 0)
              output.html('Type the input string on the textbox above');
            else {
              var sequence = "<br />Sequences: " + response['sequence'];
              output.append(sequence);
            }
          }
        });
      });
    </script>
  </body>
</html>