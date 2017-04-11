<?php
require_once('./Duck.php');
require_once('./FlyWithWings.php');
require_once('./Quack.php');

class MallardDuck extends Duck {

	public function MallardDuck() {
// 	function __construct() {	// Optionally use generic constructor function name
// 		parent::__construct();	// Not allowed, hmm? Why?
		$this->quackBehavior = new Quack();
		$this->flyBehavior = new FlyWithWings();
// 		return $this;
	}

	public function display() {
		print("I'm a real Mallard duck");
	}
}
?>
