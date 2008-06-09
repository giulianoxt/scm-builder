<?php
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

	class SCMBuilder {
		public function SCMBuilder($signature,$univ_size) {
			$this->models = array();
			$this->univ_size = $univ_size;
 			$this->generateModels($signature,0,array());
		}

		public function generateModels($signature,$pos,$cur_int) {
			if ($pos == sizeof($signature)) {
				$this->models[] = $cur_int;
				return;
			}

			$element = $signature[$pos];

			if ($element instanceof Variable || $element instanceof Constant) {
				for ($i = 0; $i < $this->univ_size; $i++) {
					$cur_int[$element->content] = $i;
					$this->generateModels($signature,$pos+1,$cur_int);
				}
			}
			elseif ($element instanceof Relation) {
				# Pegando todas as possíveis relações com aquela aridade
				$poss_rel = $this->genPossRelations($element->arity);

				# Para cada possibilidade de relação..
				foreach ($poss_rel as $rel) {
					# A relação atual ($element) vai ser essa possibilidade
					$cur_int[$element->content] = $rel;
					# Gera novos modelos
					$this->generateModels($signature,$pos+1,$cur_int);
				}
			}
			elseif ($element instanceof Func) {
				$poss_func = $this->genPossFunctions($element->arity);

				foreach($poss_func as $func) {
					$cur_int[$element->content] = $func;
					$this->generateModels($signature,$pos+1,$cur_int);
				}
			}
		}

		public function genPossFunctions($arity) {
			if ($arity == 0) {
				$ret = array();
				for ($i = 0; $i < $this->univ_size; $i++)
					$ret[] = $i;
				return $ret;
			}
	
			$poss_next = $this->genPossFunctions($arity-1);
			$poss_now = $this->genAllAssignments($poss_next,array(),0);
			
			return $poss_now;
		}

		# Retorna todas as possibilidades de relação com aquela aridade
		# O retorno é um array de arrays, onde cada array é uma possível relação
		public function genPossRelations($arity) {
			if ($arity == 0) {
				# Caso a aridade seja 0, só existem duas relações possíveis (0 ou 1)
				return array(true,false);
			}
			else {
				# Primeiro, geramos todas as possíveis relações com aridade ($arity - 1)
				$poss_next = $this->genPossRelations($arity-1);
				
				# Depois, pega todas as possibilidades de relações com ($arity-1)
				# e monta todos os possíveis arrays de tamanho $univ_size em que
				# os seus elementos pertencem à $poss_next
				$poss_now = $this->genAllAssignments($poss_next,array(),0);

				# Este último array é a resposta atual
				return $poss_now;
			}
		}

		# Gera todos os possíveis arrays de tamanho $univ_size tais que
		# os seus elementos pertencem à $poss
		# poss : todos os possíveis elementos que podem estar no array
		# cur_assign : vetor montado até agora
		# pos : posição atual que está montando
		public function genAllAssignments($poss,$cur_assign,$pos) {
			if ($pos == $this->univ_size) {
				# Caso não tenha mais nenhum elemento para marcar
				# retorna o array atual
				return array($cur_assign);
			}

			# Array que irá guardar todas as possibilidades atuais
			$ret = array();

			# Para cada possível valor dos elementos
			foreach($poss as $poss_value) {
				# Marca o elemento atual com esse valor
				$cur_assign[$pos] = $poss_value;
				# Gera todas as possibilidades de arrays
				# com aquela posição valendo aquele valor
				# e dá um merge no vetor de retorno atual
				$ret = array_merge($ret,$this->genAllAssignments($poss,$cur_assign,$pos+1));
			}

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