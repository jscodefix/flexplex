<?php
require_once('./FlyBehavior.php');
require_once('./QuackBehavior.php');

abstract class Duck {
// 	FlyBehavior flyBehavior;
	var $flyBehavior;
// 	QuackBehavior quackBehavior;
	var $quackBehavior;

	public function Duck() {
	}

	public function setFlyBehavior (FlyBehavior $fb) {
		$this->flyBehavior = $fb;
	}

	public function setQuackBehavior(QuackBehavior $qb) {
		$this->quackBehavior = $qb;
	}

	abstract function display();

	public function performFly() {
		$this->flyBehavior->fly();
	}

	public function performQuack() {
		$this->quackBehavior->quackOnce();
	}

	public function swim() {
		print("All ducks float, even decoys!");
	}
}
?>
