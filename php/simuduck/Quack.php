<?php
require_once('./QuackBehavior.php');

class Quack implements QuackBehavior {
		// 091017 Sheffel Change from quack() to quackOnce(), since a function
		// with the same name as the class implements a constructor.
	public function quackOnce() {
		print("Quack\n");
	}
}
?>
