<?php
require_once("formulaConverter2.class.php");
require_once("Check.class.php");

$tester = new formulaConverter("t", "");
$test = new Check();
//$exp1 = "or(forall(x,R (x)), R(z))";
//$exp1 = " [P]y A R(x) K R(y) [S]x T(x)";
//$exp1 = "a<->~b";
//$exp2 = "(poxa | q )& merda";
$exp1 = "Ax R(x)";

//$exp1 = $tester -> functionalToPrefix($exp1);
/*$test = $tester->ambiguityParser($exp1,false);
$tester->printArrayAmbiguityParser($test);
//echo "du";
//echo $tester->expressionComparator("-->(R(z),|(R(z),&(R(y),R(x))))","-->(R(z),&(|(R(z),R(y)),R(x))))";
//echo "-->(R(z),&(|(R(z),R(y)),R(x))))" == "-->(R(z),|(R(z),&(R(y),R(x))))";
//echo "a-->" == "a-->";

//echo $tester->removeSpaces($exp1);

echo "<b>Parenthese Saver: <br /></b>";
$test = $tester->parenthesesSaver($exp1,false);
echo "<DD><i>Best expression: </i> $test<br />"; */
echo "<br /><b>Tree: <br /></b>";
$test = $tester->infixToTree($exp1,true);
//$test->checkFormula($exp1, $tester->connectivesArray);
//$test->checkFormula($exp2, $tester->connectivesArray);
//print_r($test);
$tester->printTree($test,"");

//echo "alan" == "aalan";

?>