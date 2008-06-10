<?php
/**
 * class WFFTranslator   
 */
	class WFFTranslator{

		function WFFTranslator(){}

		/**
		 * exibe a formula de forma infixa
		 *
		 * @param Node $formula
		 * @return string
		 */
		function showFormulaInfix($formula){
			$f = "";
			
			//verifica se tem filhos, ou seja, se nao eh no interno
			if (count($formula->children) > 0){
				$f .= " (";								
				//para cada filho dentro do noh verifica...
				for ($i = 0; $i < count ($formula->children); $i++){
					
					//se for unario imprime-o antes de seus filhos
					if ($formula->content->arity == 1)
						$f .= "<strong style='color:#000066;'>".$formula->content->content."</strong>";
					
					//concatena as subarvores dos seus filhos
					$f .= $this->showFormulaInfix ($formula->children[$i]);
					
					//antes do ultimo filho imprime o conectivo
					if ($i < (count ($formula->children) - 1))
						$f .= " <strong  style='color:#000066;'>".$formula->content->content."</strong> ";
				}				
				$f .= ") ";
			} else //se for folha exibe				
				$f .= $formula->content->content;
				
			return $f;
		}
	
		/**
		 * exibe a formual de forma prefixa
		 *
		 * @param Node $formula
		 * @return string
		 */
		function showFormulaPrefix($formula){
			$f = "";
			
			//verifica se tem filhos
			if (count($formula->children) > 0){
				//concatena primeiro o conectivo
				$f .= " <strong  style='color:#000066;'>".$formula->content->content."</strong> ";
				
				//concatena o conteudo de cada filho
				for ($i = 0; $i < count ($formula->children); $i++){
					$f .= $this->showFormulaPrefix ($formula->children[$i]);
				}
			} else
				$f .= " ".$formula->content->content." ";
	
			return $f;
		}
	
		/**
		 * exibe a formula de forma posfixa
		 *
		 * @param Node $formula
		 * @return string
		 */
		function showFormulaPostfix($formula){
			$f = "";
			//verifica se tem filhos
			if (count($formula->children) > 0){
				//concatena todo o conteudo dos filhos
				for ($i = 0; $i < count ($formula->children); $i++){
					$f .= $this->showFormulaPostfix ($formula->children[$i]);
				}
				//concatena o conectivo
				$f .= " <strong style='color:#000066;'>".$formula->content->content."</strong> ";
			} else
				$f .= " ".$formula->content->content." ";
	
			return $f;
		}
	
		/**
		 * exibe a formula de forma funcional
		 *
		 * @param Node $formula
		 * @return string
		 */
		function showFormulaFunctional($formula){
			$f = "";
			//verifica se tem filhos
			if (count($formula->children) > 0){
				
				//concatena o conectivo
				$f .= " <strong  style='color:#000066;'>".$formula->content->content."</strong>(";
				$aux = "";
				
				//concatena o conteudo dos filhos entre virgulas
				for ($i = 0; $i < count ($formula->children); $i++){
					$f .= $aux.$this->showFormulaFunctional ($formula->children[$i]);
					$aux = ", ";
				}
				$f .= ") ";
			} else
				$f .= $formula->content->content;
	
			return $f;
		}
	}
	
// Teste -----------------------------------

	/*require_once("WFFGenerator.class.php");

	$conectivos = array();
	array_push($conectivos,new Connective("-->",2));
	array_push($conectivos,new Connective("<->",2));
	array_push($conectivos,new Connective("~",1));
	array_push($conectivos,new Connective("|",2));
	array_push($conectivos,new Connective("+",2));
	array_push($conectivos,new Connective("&&",2));
	
	$g = new WFFGenerator(10,10,$conectivos);
	echo "<h1>Translator Test</h1><table style='font-family:courier;'>";
	$f = $g->getFormula();

	$t = new WFFTranslator();
	
	echo ("<tr><td>Infix:</td><td>".$t->showFormulaInfix($f->root))."</td</tr>";
	echo ("<tr><td>Prefix:</td><td>".$t->showFormulaPrefix($f->root))."</td</tr>";
	echo ("<tr><td>Postfix:</td><td>".$t->showFormulaPostfix($f->root))."</td</tr>";
	echo ("<tr><td>Functional:</td><td>".$t->showFormulaFunctional($f->root))."</td</tr>";
	echo "</table><h2>End Test</h2>";*/
// Fim Teste ---------------------------------
?>