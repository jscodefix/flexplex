<?php
require_once('./FlyBehavior.php');

class FlyRocketPowered implements FlyBehavior {
	public function fly() {
		print("I'm flying with a rocket\n");
	}
}
?>
