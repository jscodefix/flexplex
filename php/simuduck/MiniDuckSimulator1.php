<?php
/* MiniDuckSimulator1.php
 * This PHP program is converted from a Java sample, the SimUDuck app of
 * "Head First Design Patterns" Chapter 1, that uses the Strategy pattern.
 * http://www.headfirstlabs.com/books/hfdp/
 *
 * MODIFICATIONS:
 * 091017 Sheffel Converted Java to object-oriented PHP, cool!
 */
require_once('./MallardDuck.php');
require_once('./ModelDuck.php');
require_once('./FlyRocketPowered.php');

$mallard = new MallardDuck();
$mallard->performQuack();
$mallard->performFly();

$model = new ModelDuck();
$model->performFly();
$model->setFlyBehavior(new FlyRocketPowered());
$model->performFly();
?>
