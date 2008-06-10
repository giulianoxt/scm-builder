<?php
require_once("WFFGenerator.class.php");
require_once("readFormula.class.php");

/**
 * class Logicamente
 * toda e qualquer funcionalidade do sistema deve ser representado por um metodo nesta classe, ou seja, 
 * essa classe representa toda a logica de negocio do sistema
 *
 */
class Logicamente{

	/**
	 * dada uma arvore que representa uma formula, ela exibe seu conteudo numa linha
	 *
	 * @param Node $formula
	 */
	function showFormula($formula){
		//verifica a quantidade de filhos. Caso haja, ele eh node interno e deve descer ate as folhas para exibir o conteudo
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
	
	/**
	 * gera formula randomicamentes
	 *
	 * @param integer $nConnectives
	 * @param integer $nAtoms
	 * @param array Connective $connectives
	 */
	function generateFormulas($nConnectives, $nAtoms, $connectives){
		/*
		$connectives = array();
		
		foreach($symbol as $key => $value)
			array_push ($connectives, new Connective($value, $arity[$key]));
		*/
				
		$gerador = new WFFGenerator($nConnectives, $nAtoms, $connectives);
		$form = $gerador->getFormula();
		
		$this->showFormula( $form->root );
	}
	
	/**
	 * check se um aformula eh fbf
	 *
	 * @param string $formula
	 * @param array Connective $connectives
	 */
	function checkFormula($formula, $connectives){
		$checker = new readFormula();
		$checker->checkFormula($formula, $connectives);
	}
}
?>