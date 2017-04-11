<?php
require_once('./Duck.php');
require_once('./FlyNoWay.php');
require_once('./Quack.php');

class ModelDuck extends Duck {
	public function ModelDuck() {
		$this->flyBehavior = new FlyNoWay();
		$this->quackBehavior = new Quack();
	}

	public function display() {
		print("I'm a model duck\n");
	}
}
?>
