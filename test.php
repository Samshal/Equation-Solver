<form style = "margin-left: 2%; text-align: center" action = "<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
	<p style = "font-size: 1.5em">Enter an equation using any variable of choice except e and E. Represent Exponents using either of e or E</p><br/>
	<textarea style = "width: 70%; height: 30%" name = "equation"></textarea><br/>
	<br/>Show error messages: <input type = "radio" name = "error" value = "true">true</input>&nbsp;&nbsp;<input type = "radio" name = "error" value = "false">false</input>
	<br/><br/><input type = "Submit" style = "width: 50%; height: 10%">
</form>
<?php
require_once("EquationSolver.php");
	if (isset($_POST["equation"]))
	{
		$equation = $_POST["equation"];
		$errors = $_POST["error"];
		$solution = new Solve($equation, $errors);
	}
	else
	{
echo <<<_END
	<p style = "font-size: 1.5em">Examples of equations you can try out</p>
	<ol style = "font-size: 1.5em">
		<li> -1019b + 14b - 81b - 2000 + 48b = 800b - 8080 + 41b</li>
		<li> 8x + 4x - 3x - 100 = -99x + 11 </li>
		<li> 9m + 7 = 80m + 1 </li>
		<li> 4a + 2a = 3a + 1 </li>
	</ol>
_END;
	}
?>