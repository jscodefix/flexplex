<?php
require_once('./FlyBehavior.php');

class FlyNoWay implements FlyBehavior {
	public function fly() {
		print("I can't fly\n");
	}
}
?>
