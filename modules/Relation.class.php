<?php
class Relation extends Node{
	public $content;
	public $arity;
	public $value;

	public function __construct($content){
		$this->content = $content;

	}
}
?>