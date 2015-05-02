<?php
/**
 * Equation Solver
 *
 * Copyright (c) 2015, Samuel Adeshina <samueladeshina73@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Samuel Adeshina nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   Equation Solver
 * @author    Samuel Adeshina <samueladeshina73@gmail.com>
 * @copyright 2015 Samuel Adeshina <samueladeshina73@gmail.com>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since     File available since Release 1.0.0
 *//*
--------------Please consult the test.php file to find out how this class works---------
*/
class Solve
{
	public $equation;
	public $variable;
	public $equationType;
	public static $possible_variables = "abcdfghijklmnopqrstuvwxyzABCDFGHIJKLMNOPQRSTUVWXYZ";
	public static $possible_equationTypes = array(
													 "0"=>"Polynomial",
													 "1"=>"Linear",
													 "2"=>"Quadratic",
													 "3"=>"Trinomial"
													);
		/*---------------------
		 *instantiation of the constructor for this class.
		 *It takes two parameters:
		 *1. The equation to be process and
		 *2. A boolean parameter indicating whether error should
		 *	 be displayed (true, 1 or "") or not (false, 0 or "[a-zA-Z0-9]")
		---------------------*/
	public function __construct($parameter, $show_errors)
	{
		if ($show_errors)
		{
			error_reporting(0);
		}
		$this->equation = self::purify($parameter);
		if (!self::verify())
		{
			return self::process_error("Your equation contains some characters which makes it invalid and unsolvable. Please remove this character(s).", true);
		}
		$this->variable = self::get_variable()[0];
		$this->equationType = self::get_equationType();
		//$this->variable." = ".$solution[$this->variable];
	}
	public function solution()
	{
		$solution = self::assign_solver();
		$returnVal = array($this->variable, $solution);
		return $returnVal;
	}
	/*-------------------------------------
	 * Instantiation of the purify method.
	 * It accepts a single parameter which is the equation
	 * to be purified (trim and stripped of whitespaces)
	--------------------------------------*/
	function purify($parameter)
	{
		return $purified = str_replace(
										 " ",
										 NULL,
										 trim($parameter)
									  );
	}
	/*-------------------------------------
	 * Instantiation of the process_error class
	 * which handles the display of errors.
	 * It accepts two parameters, the message
	 * to be displayed and a boolean value
	 * indicating whether or not program
	 * execution should be stopped/killed
	--------------------------------------*/
	public function process_error($msg, $bool)
	{
		if ($bool)
		{
			die ($msg);
		}
		else
		{
			echo $msg;
		}
	}
	/*-------------------------------------
	 * Instantiation of the verify method
	 * It accepts no arguments and performs a 
	 * series of tests on an equation to determine
	 * it is solve-able or not (valid or not)
	--------------------------------------*/
	public function verify()
	{
		$sides = explode("=", $this->equation);
		$lIndex = $this->equation[strlen($this->equation)-1];
		$rIndex = $this->equation[0];
		if (count($sides) != 2)
		{
			//we have a problem: either no equals sign was provided or too many were supplied
			return false;
		}
		else if (count(self::get_variable()) != 1)
		{
			return false;
		}
		else if (strstr("*-+/\\&^%$#@!()[]{}<>.,_", $lIndex) || strstr("*/\\&^%$#@!<>.,_", $rIndex))
		{
			return false;
		}
		for ($i = 0; $i < strlen($this->equation); $i++)
		{
			if (strstr("\\&^%$@!<>,_", $this->equation[$i]))
			{
				return false;
				break;
			}
			if (strstr("+", $this->equation[$i]))
			{
				if (strstr("+-*/", $this->equation[$i+1]) || strstr("+-*/", $this->equation[$i-1]))
				{
					return false;
					break;
				}
			}
			if (strstr(".", $this->equation[$i]))
			{
				if (!is_numeric($this->equation[$i-1]) || !is_numeric($this->equation[$i+1]))
				{
					return false;
					break;
				}
			}
		}
		return true;
	}
	/*-------------------------------------
	 * Instantiation of the get_variable method
	 * This method accepts no parameter/argument
	 * Its sole responsibility to intelligently 
	 * determine the variable to be solved
	 * for from the supplied equation
	--------------------------------------*/
	public function get_variable()
	{
		$variables = array();
		for ($i = 0, $j = strlen($this->equation); $i < $j; $i++)
		{
			if (strstr(self::$possible_variables, $this->equation[$i]))
			{
				//echo $this->equation[$i]."<br/>";
				if (!in_array($this->equation[$i], $variables))
				{
					$variables[] = $this->equation[$i];
				}
				else
				{
					continue;
				}
			}
		}
		return $variables;
	}
	/*-------------------------------------
	 * The get_equationType method accepts no
	 * parameter. It is responsible for determining
	 * the type of equation that was supplied
	--------------------------------------*/
	public function get_equationType()
	{
		$indexes = array();
		for ($i = 0, $j = strlen($this->equation); $i < $j; $i++)
		{
			if (strstr("eE", $this->equation[$i]))
			{
				$index = $this->equation[$i + 1];
				$indexes[] = $index;
			}
			else
			{
				$indexes[] = "1";
			}
		}
		if (max($indexes) > 3){ $index = "0"; } else { $index = max($indexes); }
		$power = self::$possible_equationTypes[$index];
		return $power;
	}
	public function assign_solver()
	{
		switch ($this->equationType)
		{
			case "Linear":
				return self::solve_linear();
				break;
			case "Quadratic":
				return self::solve_quadratic();
				break;
			case "Trinomial":
			case "Polynomial":
				return self::process_error("The equation supplied is either a trinomial or polynomial. Algorithms for solving this type of equations are still under construction", false);
				break;
			default:
				return self::process_error("An error Occurred, please try again or refresh this page", true);
		}
	}
	public function solve_linear()
	{
		for ($i = 0, $j = strlen($this->equation); $i < $j; $i++)
		{
			if (strstr("eE", $this->equation[$i]))
			{
				$index = $this->equation[$i].$this->equation[$i + 1];
				$this->equation = str_replace($index, NULL, $this->equation);
			}
		}
		$sides = array();
		$lhs = explode("=", $this->equation)[0];
		if (is_numeric(substr($lhs, 0, 1)))
		{
			$lhs = "+".$lhs;
		}
		else if (!is_numeric(substr($lhs, 0, 1)))
		{
			$lhs = "+1".$lhs;
		}
		for ($i = 0, $j = strlen($lhs); $i < $j; $i++)
		{
			if (strstr("+-", $lhs[$i]))
			{
				//$pos = strpos($lhs, $lhs[$i]);
				//echo $i."<br/>";
				$positions[] = $i;
			}
		}
		$sides = array();
		for ($counter = 0; $counter < count($positions); $counter++)
		{
			if (isset($positions[$counter + 1]))
			{
				$sides[] = substr($lhs, $positions[$counter], $positions[$counter+1] - $positions[$counter]);
			}
			else
			{
				$sides[] = substr($lhs, $positions[$counter]);
			}
		}
		$rhs = explode("=", $this->equation)[1];
		if (is_numeric(substr($rhs, 0, 1)))
		{
			$rhs = "+".$rhs;
		}
		else if (!is_numeric(substr($rhs, 0, 1)))
		{
			$rhs = "+1".$rhs;
		}
		for ($i = 0, $j = strlen($rhs); $i < $j; $i++)
		{
			if (strstr("+-", $rhs[$i]))
			{
				//$pos = strpos($lhs, $lhs[$i]);
				//echo $i."<br/>";
				$positions_rhs[] = $i;
			}
		}
		if (!isset($positions_rhs))
		{
			$positions_rhs[] = "0";
		}
		for ($counter = 0; $counter < count($positions_rhs); $counter++)
		{
			if (isset($positions_rhs[$counter + 1]))
			{
				$string = substr($rhs, $positions_rhs[$counter], $positions_rhs[$counter+1] - $positions_rhs[$counter]);
			}
			else
			{
				$string = substr($rhs, $positions_rhs[$counter]);
			}
			if (substr($string, 0, 1) == "+")
			{
				$string = substr_replace($string, "-", 0, 1);
			}
			else if (substr($string, 0, 1) == "-")
			{
				$string = substr_replace($string, "+", 0, 1);
			}
			$sides[] = $string;
		}
		for ($counter = 0, $count = count($sides); $counter < $count; $counter++)
		{
			$number = $sides[$counter];
			$contains_var = 0;
			for ($i = 0, $j = strlen($number); $i < $j; $i++)
			{
				if (strstr($this->variable, $number[$i]))
				{
					$contains_var += 1;
				}
			}
			if ($contains_var > 0)
			{
				$with_var[] = substr($number, 0, -1);
			}
			else
			{
				$without_var[] = $number;
			}
		}
		$with_variable = 0;
		$without_variable = 0;
		foreach ($with_var as $var)
		{
			//echo $var."<br/>";
			$with_variable += $var;
		}
		foreach ($without_var as $var)
		{
			//echo $var."<br/>";
			$without_variable += $var;
		}
		$final_solution = $without_variable/$with_variable;
		if (substr($final_solution, 0, 1) == "+")
		{
			$final_solution = substr_replace($final_solution, "-", 0, 1);
		}
		else if (substr($final_solution, 0, 1) == "-")
		{
			$final_solution = substr_replace($final_solution, "+", 0, 1);
		}

		if (is_numeric(substr($final_solution, 0, 1)))
		{
			$final_solution = "-".$final_solution;
		}
		$toArray = array($final_solution);
		return $toArray;
	}
	public function solve_quadratic()
	{
		$sides = array();
		$lhs = explode("=", $this->equation)[0];
		if (is_numeric(substr($lhs, 0, 1)))
		{
			$lhs = "+".$lhs;
		}
		else if (!is_numeric(substr($lhs, 0, 1)))
		{
			$lhs = "+1".$lhs;
		}
		for ($i = 0, $j = strlen($lhs); $i < $j; $i++)
		{
			if (strstr("+-", $lhs[$i]))
			{
				//$pos = strpos($lhs, $lhs[$i]);
				//echo $i."<br/>";
				$positions[] = $i;
			}
		}
		$sides = array();
		for ($counter = 0; $counter < count($positions); $counter++)
		{
			if (isset($positions[$counter + 1]))
			{
				$sides[] = substr($lhs, $positions[$counter], $positions[$counter+1] - $positions[$counter]);
			}
			else
			{
				$sides[] = substr($lhs, $positions[$counter]);
			}
		}
		$rhs = explode("=", $this->equation)[1];
		if (is_numeric(substr($rhs, 0, 1)))
		{
			$rhs = "+".$rhs;
		}
		else if (!is_numeric(substr($rhs, 0, 1)))
		{
			$rhs = "+1".$rhs;
		}
		for ($i = 0, $j = strlen($rhs); $i < $j; $i++)
		{
			if (strstr("+-", $rhs[$i]))
			{
				//$pos = strpos($lhs, $lhs[$i]);
				//echo $i."<br/>";
				$positions_rhs[] = $i;
			}
		}
		if (!isset($positions_rhs))
		{
			$positions_rhs[] = "0";
		}
		for ($counter = 0; $counter < count($positions_rhs); $counter++)
		{
			if (isset($positions_rhs[$counter + 1]))
			{
				$string = substr($rhs, $positions_rhs[$counter], $positions_rhs[$counter+1] - $positions_rhs[$counter]);
			}
			else
			{
				$string = substr($rhs, $positions_rhs[$counter]);
			}
			if (substr($string, 0, 1) == "+")
			{
				$string = substr_replace($string, "-", 0, 1);
			}
			else if (substr($string, 0, 1) == "-")
			{
				$string = substr_replace($string, "+", 0, 1);
			}
			$sides[] = $string;
		}
		for ($counter = 0, $count = count($sides); $counter < $count; $counter++)
		{
			$number = $sides[$counter];
			for ($i = 0, $j = strlen($number); $i < $j; $i++)
			{
				if (strstr($this->variable, $number[$i]))
				{
					if (strstr("eE", $number[$i+1]))
					{
						$a[] = substr($number, 0, -3);
						unset($sides[$counter]);
					}
					else
					{
						$b[] = substr($number, 0, -1);
						unset($sides[$counter]);
					}
				}
			}
		}
		$c = $sides;
		//return array($a, $b, $c);
		$acoeff = 0;
		$bcoeff = 0;
		$ccoeff = 0;
		foreach ($a as $elem)
		{
			$acoeff += $elem;
		}

		foreach ($b as $elem)
		{
			$bcoeff += $elem;
		}

		foreach ($c as $elem)
		{
			$ccoeff += $elem;
		}
		$proc = sqrt(($bcoeff * $bcoeff) - (4 * $acoeff * $ccoeff));
		$sol1 = ((-1 * $bcoeff) + $proc) / (2 * $acoeff);
		$sol2 = ((-1 * $bcoeff) - $proc) / (2 * $acoeff);
		return array($sol1, $sol2);
	}
}
?>