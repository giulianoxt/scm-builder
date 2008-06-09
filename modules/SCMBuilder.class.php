<?php
	/**
	* SCMBuilder.class.php
	*
	* @author Max Rosan
	* @author Thales Galdino
	* @author Giuliano Vilela
	* @author Lucas Araújo
	*/

	require_once("WFFTranslator.class.php");
	require_once("Node.class.php");
	require_once("Term.class.php");
	require_once("Constant.class.php");
	require_once("Variable.class.php");
	require_once("Function.class.php");
	require_once("Relation.class.php");
	require_once("Signature.class.php");
	require_once("formulaConverter2.class.php");

	error_reporting(E_ALL);

	/**
	* class SCMBuilder
	*
	* Classe utilizada para gerar todos os modelos possíveis para
	* uma certa assinatura.
	*/
	class SCMBuilder {
		/**
		* Cria um SCMBuilder relativo à assinatura dada,
		* trabalhando em um universo de discurso com um
		* dado número de objetos.
		*
		* @param $signature Assinatura relativa à fórmula.
		* @param $univ_size Número de objetos.
		*/
		public function SCMBuilder($signature,$univ_size) {
			$this->models = array();
			$this->univ_size = $univ_size;
 			$this->generateModels($signature,0,array());
		}

		/**
		* Gera todos os modelos possíveis para à assinatura.
		* É uma recursão sobre a lista $signature.
		* @param $signature Assinatura da fórmula.
		* @param $pos Funciona como parâmetro da recursão. $signature[$pos]
		*			  é o elemento da assinatura sendo analisado nesta
					  chamada recursiva.
		* @param $cur_int Interpretação que vem sendo montada nas chamadas
		* 			  recursivas.
		*/
		public function generateModels($signature,$pos,$cur_int) {
			// Caso não tenha mais nenhum elemento para analisars
			if ($pos == sizeof($signature)) {
				// Armazena a interpretação atual no array de todos os modelos
				$this->models[] = $cur_int;
				return;
			}

			// Elemento da assinatura que será analisado agora
			$element = $signature[$pos];

			// Caso seja uma Variável livre ou um termo Constante
			if ($element instanceof Variable || $element instanceof Constant) {
				// Então, este elemento pode ser interpretado como qualquer
				// elemento do domínio de interpretação.
				for ($i = 0; $i < $this->univ_size; $i++) {
					$cur_int[$element->content] = $i;
					// Monte todos os modelos correspondentes à cada atribuição para
					// este elemento, recursivamente
					$this->generateModels($signature,$pos+1,$cur_int);
				}
			}
			// Caso seja uma relação
			elseif ($element instanceof Relation) {
				// Pega o conjunto de todas as possíveis relações
				// que tenham aquela aridade
				$poss_rel = $this->genPossRelations($element->arity);

				// Para cada possível relação, monta os modelos correspondentes
				// quando a relação atual for interpretada como essa possibilidade
				foreach ($poss_rel as $rel) {
					// Mapeia a relação atual para essa 
					$cur_int[$element->content] = $rel;
					// Gera todos os modelos em que a relação
					// $element esteja mapeada para a interpretação $rel
					$this->generateModels($signature,$pos+1,$cur_int);
				}
			}
			// Caso seja uma função
			elseif ($element instanceof Func) {
				// Pega o conjunto de todas as possíveis funções
				// que tenham aquela aridade
				$poss_func = $this->genPossFunctions($element->arity);

				// Para cada possível função, monta os modelos correspondentes
				// quando a função atual for interpretada com essa possibilidade
				foreach($poss_func as $func) {
					// Mapeia a função atual para essa
					$cur_int[$element->content] = $func;
					// Gera mais modelos em que a função atual
					// está sendo mapeada para essa interpretação particular
					$this->generateModels($signature,$pos+1,$cur_int);
				}
			}
		}

		/** 
		* Retorna todas as possibilidades de função com aquela aridade
		* @param $arity Aridade das funçõe
		* @return Array de arrays, onde cada array é uma possível função
		*/
		public function genPossFunctions($arity) {
			if ($arity == 0) {
				// Caso a aridade seja 0, a função
				// pode ser interpretada como uma constante.
				// Nesse caso, existem $univ_size funções possíveis,
				// uma para cada elemento do universo de discurso
				$ret = array();
				for ($i = 0; $i < $this->univ_size; $i++)
					$ret[] = $i;
				return $ret;
			}
			// Primeiro, geramos todas as possíveis funções com aridade ($arity - 1)
			$poss_next = $this->genPossFunctions($arity-1);

			// Depois, pegamos todas as possibilidades de funções com ($arity-1)
			// e montamos todos os possíveis arrays de tamanho $univ_size em que
			// os seus elementos pertencem à $poss_next
			$poss_now = $this->genAllAssignments($poss_next,array(),0);
			
			return $poss_now;
		}

		/** 
		* Retorna todas as possibilidades de relação com aquela aridade
		* @param $arity Aridade das relações
		* @return Array de arrays, onde cada array é uma possível relação
		*/
		public function genPossRelations($arity) {
			if ($arity == 0) {
				// Caso a aridade seja 0, só existem duas relações possíveis (0 ou 1)
				return array(true,false);
			}
			else {
				// Primeiro, geramos todas as possíveis relações com aridade ($arity - 1)
				$poss_next = $this->genPossRelations($arity-1);
				
				// Depois, pegamos todas as possibilidades de relações com ($arity-1)
				// e montamos todos os possíveis arrays de tamanho $univ_size em que
				// os seus elementos pertencem à $poss_next
				$poss_now = $this->genAllAssignments($poss_next,array(),0);

				// Este último array é a resposta atual
				return $poss_now;
			}
		}

		/**
		* Gera todos os possíveis arrays de tamanho $univ_size tais que
		* os seus elementos pertencem ao conjunto $poss
		* @param $poss : Todos os possíveis elementos que podem estar nos arrays
		* @param $cur_assign : Array montado até agora pela recursão
		* @param $pos : Posição que irá ser montada para o array $cur_assign na recursão atual.
		*/
		public function genAllAssignments($poss,$cur_assign,$pos) {
			if ($pos == $this->univ_size) {
				// Caso não tenha mais nenhum elemento para marcar
				// retorna um conjunto com o array atual
				return array($cur_assign);
			}

			// Array que irá guardar todas as possibilidades atuais
			$ret = array();

			// Para cada possível valor de $cur_assign[$pos]
			foreach($poss as $poss_value) {
				// Marca o elemento atual com esse valor
				$cur_assign[$pos] = $poss_value;
				// Gera todas as possibilidades de arrays
				// com aquela posição valendo aquele valor
				// e dá um merge no vetor de retorno atual
				$ret = array_merge($ret,$this->genAllAssignments($poss,$cur_assign,$pos+1));
			}

			// Retorna a solução atual
			return $ret;
		}

		/**
		* Array de mapeamentos (String do Elemento -> Interpretação)
		*/
		public $models;
		/**
		* Cardinalidade do domínio.
		*/
		public $univ_size;
	}


	// Testes de debug

	$univ_size = 2;
	$formula = "((Ax (Ay R(x,y))) --> (Ay (Ex R(y,x)))) & G(a,b)";

	$converter = new formulaConverter("T","");
	$tree = $converter->infixToTree($formula,true);

 	$sig = new Signature($tree);

	$scm_builder = new SCMBuilder($sig->signature,$univ_size);

	echo "Todas as possibilidades de modelos para:<br>";
	echo "-> Formula: <strong>".$formula."</strong><br>";
	echo "-> Numero de objetos no dominio de interpretacao: <strong>".$univ_size;
	echo "</strong><br><br>";

	foreach($scm_builder->models as $key => $model) {
		echo "--<strong>Modelo ".$key." =</strong><br>";
		foreach($model as $element => $interpret) {
			echo "----Elemento: ";
			print_r($element);
			echo "<br>----Valor: ";
			print_r($interpret);
			echo "<br><br>";
		}
		echo "<br><br>";
	}

?>