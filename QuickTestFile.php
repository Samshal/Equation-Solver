<?php
require_once("EquationSolver.php");
		$equation = "xe2 + 5x - 20 = 8x + 20";
		$errors = true;
		$equation = new Solve($equation, $errors);
		//$var = $equation->solution()[0];
		$solutions = $equation->solution()[1];
		print_r($solutions);
?>