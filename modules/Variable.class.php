<?php
class Variable extends Term {
	public $isLinked;
	function Variable($content) {
		$this->content = $content;
		$this->isLinked = false;
	}
	
}
?>