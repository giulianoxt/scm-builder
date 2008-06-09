<?php
	error_reporting(E_ALL);

	require_once("Node.class.php");
	require_once("Term.class.php");
	require_once("Constant.class.php");
	require_once("Variable.class.php");
	require_once("Function.class.php");
	require_once("Relation.class.php");

	require_once("formulaConverter2.class.php");

	/**
	* @author Max Rosan
	* @author Thales Galdino
	* @author Giuliano Vilela
	* @author Lucas Araújo
	*/

	class Signature {
		public function Signature($formula) {
			$this->signature = array();
			$this->getSignature($formula);
		}

		public function getSignature($formula) {
			if (($formula->content instanceof Constant) or
			    ($formula->content instanceof Variable && !$formula->content->isLinked) or
			    ($formula->content instanceof Func) or
			    ($formula->content instanceof Relation)) {

				$exists = false;
				foreach ($this->signature as $element) {
					if ($element->content == $formula->content->content) {
						$exists = true;
						break;
					}
				}

				if (!$exists) $this->signature[] = $formula->content;
			}

			foreach ($formula->children as $value) {
				$this->getSignature($value);
			}
		}
	
		public function printSignature() {
			echo "Tamanho = ".sizeof($this->signature)."<br>";
			foreach ($this->signature as $value) {
				echo "Elemento = ";
				print_r($value);
				echo "<br>";
			}
		}

		public $signature;
	}

// 	$formula = 'R(x) & Y(c)';
// 	$converter = new formulaConverter("T","");
// 	$tree = $converter->infixToTree($formula,true);
// 
// 	$sig = new Signature($tree);
// 
// 	echo "Formula: ".$formula."<br><br>";
// 
// 	$sig->printSignature();
?>
	
