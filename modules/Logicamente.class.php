<?php
require_once("WFFGenerator.class.php");
require_once("formulaConverter2.class.php");
require_once("ResolutionGame.class.php");
require_once("PatternMatcher.class.php");
require_once("TruthTable.class.php");
require_once("MiniDiagnoser.class.php");

$counter = 0;

class Logicamente{

	function showFormula($formula){
		if ( count($formula->children) > 0){
			echo ("(");
			for ($i = 0; $i < count ($formula->children); $i++){
				if ($formula->content->arity == 1)
					echo (" ".$formula->content->content." ");
				$this->showFormula ($formula->children[$i]);
				if ($i < (count ($formula->children) - 1))
					echo (" ".$formula->content->content." ");			
			}
			echo (")");
		} else
			echo (" ".$formula->content->content." ");
	}
	
	function generateFormulas($nConnectives, $nAtoms, $connectives){
		/*
		$connectives = array();
		
		foreach($symbol as $key => $value)
			array_push ($connectives, new Connective($value, $arity[$key]));
		*/
				
		$gerador = new WFFGenerator($nConnectives, $nAtoms, $connectives);
		$form = $gerador->getFormula();
		
		$this->showFormula( $form->root );
		return $form->root;
	}
	
	function checkFormula($formula, $connectives){
		$checker = new readFormula();
		$checker->checkFormula($formula, $connectives);
	}

	function readFormula($formula,$array) {
		$formulaConverter = new formulaConverter("T",$array);		
		return $formulaConverter->infixToTree($formula, false);
	}
	
	function printFormula($node,$array) {
		//print_r($node);
		$formulaConverter = new formulaConverter("T",$array);		
		$formulaConverter -> printTree($node,"");
	}
	
	function startGame($tree) {
		$game = new ResolutionGame();
		$game->addConvertedTree($tree);
		return $game;
	}
	
	function normalizeDisjunctions($disjunction) {
		$patternMatcher = new PatternMatcher();
		return $patternMatcher->normalizeDisjunctions($disjunction);
	}
	
	public function getTruthTable($formula){
		$this->modifyConnectives( $formula );
		//die();
		$objTable = new TruthTable($formula, 0);
		$objTable->printTable( 1 );		
	}
	
	public function getTreeInteraction($formula){
		global $counter;
		$f = "<ul>";
		if (count($formula->children) > 0){
			$f .= "<li><p id='c".$formula->content->content."' class='connective' index='".$counter."'>".$formula->content->content."</p><script></script>";
			$counter++;
			for ($i = 0; $i < count ($formula->children); $i++){
				$f .= $this->getTreeInteraction ($formula->children[$i]);
			}
			$f .= "</li>";
		} else {
			$f .= "<li><p class='atom' index='".$counter."'>".$formula->content->content."</p></li>";
			$counter++;
		}

		return $f."</ul>";
	}
	
	private function modifyConnectives($formula){
		//print $formula->content->content." <br/>";
		switch( $formula->content->content ){
			case '~': $formula->content->content = '&not;'; print " NEG "; break;
			case '&': $formula->content->content = '&and;'; print " AND "; break;
			case '|': $formula->content->content = '&or;'; print " OR "; break;
			case '-->': $formula->content->content = '&rarr;'; print " IMP "; break;
			case '<->': $formula->content->content = '&harr;'; print " EQ "; break;
		}	
		foreach( $formula->children as $child ){ 
			$this->modifyConnectives( $child );	
		}
	}	
}
?>
