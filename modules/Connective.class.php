<?php
	/**
	 * Class Connective
	 * 
	 * @param string $content
	 * @param integer $arity
	 * @param integer $order
	 */
	class Connective{
		public $content;
		public $arity;
		public $order;
		public $value;
		
		function Connective($content, $arity, $order){
			$this->content = $content;
			$this->arity = $arity;
			$this->order = $order;
			$this->value = 0;
		}
	}
?>