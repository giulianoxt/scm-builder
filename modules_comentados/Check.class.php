<?php
//require ("Connective.class.php");
/**
 * class Check 
 * Checar se uma dada string eh uma fbf 
 */
class Check{
	
	/**
	 * funcao para checar se uma string eh uma fbf
	 *
	 * @param string $formula
	 * @param array Connective $connectives
	 */
	function checkFormula($formula, $connectives){	
		
		$auxUc = array(); //connectives with arity 1
		$auxZc = array(); //connectives with arity 0
		$auxC = array();  //connectives with arity >= 2
				
		//preencher os vetores acima com os simbolos dos conectivos do array dado
		foreach ($connectives as $c){
			if ($c->arity == 0) array_push($auxZc,$c->content);
			else if ($c->arity == 1) array_push($auxUc,$c->content);
			else array_push($auxC,$c->content);
		}
		
		//concatena em $ucs todos os conectivos unario separado pelo "|", que representa o "ou"
		$auxOr = ""; $ucs = "(";
		foreach($auxUc as $uc){ $ucs .=$auxOr.$uc; $auxOr = "|"; }
		$ucs .= ")";

		//concatena em $zcs todos os conectivos 0-arios separado pelo "|", que representa o "ou"
		$auxOr = ""; $zcs = "(";
		foreach($auxZc as $zc){ $zcs .=$auxOr.$zc; $auxOr = "|"; }
		$zcs .= ")";

		//concatena em $cs todos os conectivos com aridade >=2 separado pelo "|", que representa o "ou"
		$auxOr = ""; $cs = "(";
		foreach($auxC as $c){ $cs .=$auxOr.$c; $auxOr = "|"; }
		$cs .= ")";
	
		//exibe conectivos contidos na formula
		echo ($ucs."<br>");
		echo($zcs."<br>");
		echo($cs."<br>");
	
		//gera a expressao regular da formula
		$v = "(\(* *|".$ucs.")*([a-z]+[A-Z]*[0-9]*|[a-z]*[A-Z]+[0-9]*|".$zcs.")( *\)*)*";
		
		echo ($formula." -> ");
		
		//verifica se a formula estah no padrao da expressao regular
		if (ereg("^".$v."(".$cs.$v.")*$",$formula)) echo ("OK!");
		else echo ("ERROR!");
	}
}
?>