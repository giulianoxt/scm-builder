<?php
/**
 * class Node
 * node da arvore que representa uma formula
 * 
 * @param Connective | Atom | Quantificador $content
 */
	class Node{
		function Node($content){
			$this->content = $content;
			$this->children = array();
		}

		/**
		 * verifica se o node eh um atom
		 *
		 * @return boolean
		 */
		function isAtom(){
			return is_a($this->content, Atom);
		}
	}
?>
