<?php
	/**
	 * Class Quantifier
	 * 
	 * @param string $content
	 * @param Variable $bound_variable
	 */
	class Quantifier extends Node{
		public $content;
		public $bound_variable;
		
		function Quantifier($content, $variable){
			$this->content = $content;
			$this->bound_variable = $variable;
		}
	}
?>