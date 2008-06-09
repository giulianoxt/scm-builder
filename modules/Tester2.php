<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Tester2 Project P4</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <div align="center">
    <table width="177" border="1" bordercolor="#0000FF" bgcolor="#FFFFFF">
      <tr>
        <td width="74">Express&atilde;o:</td>
        <td width="87"><label>
          <input name="exp" type="text" id="exp" value = "<?php if (!empty($_POST['exp'])) echo $_POST['exp']?>" />
        </label></td>
      </tr>
       <tr>
        <td>Ordem:</td>
        <td><label>
          <select name="ordem" id="ordem">
            <option value="false" selected="selected">Proposicional</option>
            <option value="true">1 Ordem</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td colspan="2"><p>
          <label></label>
          <label>
          <input name="formato" type="radio" value="txt" checked="checked" />
TXT</label>
          <br />
          <label>
          <input type="radio" name="formato" value="func" />
Funcional</label>
          <br />
          <label>
          <input type="radio" name="formato" value="pre" />
Prefixo</label>
          <label><br />
                  </label>
        </p>        </td>
      </tr>
      <tr>
        <td colspan="2"><label>
          <div align="center">
            <input type="submit" name="Submit" value="Submit" />
          </div>
        </label></td>
      </tr>
      </table>
  </div>
  <label></label>
</form>
<?php
require_once("formulaConverter2.class.php");


if (!empty($_POST['exp'])) {
	$ordem = $_POST['ordem'];
	$formato = $_POST['formato'];
	$exp = $_POST['exp'];
	if ($ordem == "true") $ordem = true; else $ordem = false;

	echo "<br /><b>Tree: <br /></b>";
	if ($formato == "txt") {
		$tester = new formulaConverter("T","");
		$test = $tester->infixToTree($exp,$ordem);
		$tester->printTree($test,"");
	} else if ($formato == "func"){
		$tester = new formulaConverter("F");
		$test = $tester->functionalToTree($exp,$ordem);
		$tester->printTree($test,"");
	} else if ($formato == "pre"){
		$tester = new formulaConverter("P");
		$test = $tester->prefixToTree($exp,$ordem);
		$tester->printTree($test,"");
	}
	
}



?>


</body>
</html>
