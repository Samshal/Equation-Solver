<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Equation Solver | Solve Your Everyday Maths Equation Problem</title>
	<style>
		body{
				height: 100%;
				background-color: #ADD8E6;
			}
		#equa{
			font-size: 1.5em;
		}
		#equa:hover, #eq:active
		{
			font-size: 2em;
		}
		#down
		{
			background-color: #fff;
			height: auto;
			width: 80%;
			border: 1px solid #000;
			border-radius: 20px;
			margin: 2% 0% 0% 10%;
		}
		#h{
			margin-left: 2%;
			text-align: center;
		}
		#h > h1
		{
			font-variant: small-caps;
			letter-spacing: 20px;
			text-shadow: 1px 1px 10px #fff;
		}
		#m{
			font-size: 1.2em;
		}
	</style>
</head>
<body>
	<div id = "h"><h1>Equation Solver</h1>
	<p>Solve Your Linear, Quadratic, Trinomial or Polynomial Equations All In One Place Without Worrying About Variables!</p></div>
<form style = "margin-left: 0.2%; text-align: center" action = "<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
	<p style = "font-size: 1.5em"></p><br/>
	<input id = "equa" type = "text" style = "width: 100%; height: 40px; text-align: center;" name = "equation" placeholder = "Enter an equation using any variable of choice except e and E. Represent Exponents using either of e or E"/><br/>
	<br/>Show error messages: <input type = "radio" name = "error" value = "true">true</input>&nbsp;&nbsp;<input type = "radio" name = "error" value = "false">false</input>
	<br/><br/><input type = "Submit" style = "width: 50%; height: 50px">
</form>
<?php
require_once("EquationSolver.php");
	if (!isset($_POST["error"]))
	{
		$_POST["error"] = "true";
	}
	if (isset($_POST["equation"]))
	{
		$equation = $_POST["equation"];
		$errors = $_POST["error"];
		$equation = new Solve($equation, $errors);
		$var = $equation->solution()[0];
		$solutions = $equation->solution()[1];
		echo "<div id = \"down\"><br/>";
		echo "<center>The equation <i>\"$equation->equation\"</i> which you asked me to solve is a <i>\"$equation->equationType Equation\"</i><br/>The Solution(s) is/are: <br/>";
		foreach ($solutions as $solution)
		{
			if (is_nan($solution))
			{
				$solution = $solution . "&nbsp;&nbsp;(Solution results in Complex Numbers)";
			}
			if ($solution == $solutions[0])
			{
				echo "<br/><b>$var = $solution</b><br/>";
			}
			else
			{
				echo "or<br/><b>$var = $solution</b><br/>";
			}
		}
		echo "</center><br/></div>";
	}
	else
	{
echo <<<_END
<div id = "down">
	<p style = "font-size: 1.5em; text-align: center">Examples of equations you can try out (Just copy and paste them into the text box)</p>
	<ol style = "font-size: 1.5em">
		<li> -1019b + 14b - 81b - 2000 + 48b = 800b - 8080 + 41b</li>
		<li> 8x + 4x - 3x - 100 = -99x + 11 </li>
		<li> xE2 + 5x - 20 = 8x + 20 </li>
		<li> Ae2 - 1A + 1 = 0 </li>
	</ol>
</div>
_END;
	}
?>
<br/><br/><center><p id = "m">&copy;2015 - <a href = "https://plus.google.com/+SamuelAdeshina73">Samuel Adeshina</a> &lt;samueladeshina73@gmail.com&gt; | <a href = "http://samshaltechs.blogspot.com">samshaltechs.blogspot.com</a> | <a href = "https://twitter.com/SamuelAdeshina6">@samueladeshina6</a></p></center>
</body>
</html>