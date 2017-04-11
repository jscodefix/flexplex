<?php
require_once('./FlyBehavior.php');

class FlyWithWings implements FlyBehavior {
	public function fly() {
		print("I'm flying!!\n");
	}
}
?>
