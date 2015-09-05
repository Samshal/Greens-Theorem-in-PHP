<?php
	require_once("point.class.php");
	class Polygon
	{
		protected $vertices = array();
		public function __construct($vertice = null)
		{
			$pointObj = new Point();
			try
			{
				foreach (func_get_args() as $vertice)
				{
					if (!is_null($vertice))
					{
						self::setVertice($vertice);
					}
				}
			}
			catch(Exception $e)
			{
				return self::displayPointInvalidError();
			}
		}

		private function displayPointInvalidError($empty = false)
		{
			echo "Invalid point detected in supplied parameters";
			if ($empty)
			{
				$this->vertices = array();
			}
		}

		public function setVertice($vertice)
		{
			try
			{
				if (!is_array($vertice))
				{
					$this->vertices[] = $vertice;
				}
				else if (is_object($vertice) && ($vertice instanceof $pointObj))
				{
					$this->vertices[] = $vertice->point();
				}
				else
				{
					throw new \Exception();
				}
			}
			catch(Exception $e)
			{
				return self::displayPointInvalidError();
			}
		}

		public function setVertices()
		{
			$pointObj = new Point();
			try
			{
				foreach (func_get_args() as $vertice)
				{
					if (!is_null($vertice))
					{
						self::setVertice($vertice);
					}
				}
			}
			catch(Exception $e)
			{
				return self::displayPointInvalidError();
			}
		}

		private function isPolygon()
		{
			//need to check if we've got a valid irregular polygonal shape from the supplied vertices
			return true;
		}

		public function area()
		{
			if (self::isPolygon())
			{
				$points = count($this->vertices);
				$verticeSigma = 0;
				for ($i = 0; $i < $points - 1; $i++)
				{
					if (isset($this->vertices[$i]) && isset($this->vertices[$i+1]))
					{
						$xCurrent = $this->vertices[$i]->point[0];
						$yCurrent = $this->vertices[$i]->point[1];
						$xNext = $this->vertices[$i+1]->point[0];
						$yNext = $this->vertices[$i+1]->point[1];
						$verticeSigma += (($xNext + $xCurrent)*($yNext - $yCurrent)) / 2;
					}
				}
				$xLast = (($this->vertices[0]->point[0] + $this->vertices[$points-1]->point[0]));
				$yLast = (($this->vertices[0]->point[1] - $this->vertices[$points-1]->point[1]));
				$verticeSigma += ($xLast * $yLast) / 2;
				return $verticeSigma;
			}
		}

		public function perimeter()
		{
			if (self::isPolygon())
			{
				$points = count($this->vertices);
				$verticeSigma = 0;
				for ($i = 0; $i < $points - 1; $i++)
				{
					if (isset($this->vertices[$i]) && isset($this->vertices[$i+1]))
					{
						$edgeOneCoords = $this->vertices[$i];
						$edgeTwoCoords = $this->vertices[$i+1];
						$xedgeOne = $edgeOneCoords->point[0];
						$xedgeTwo = $edgeTwoCoords->point[0];
						$yedgeOne = $edgeOneCoords->point[1];
						$yedgeTwo = $edgeTwoCoords->point[1];
						$lineType = self::checkLineType(array($xedgeOne, $xedgeTwo), array($yedgeOne, $yedgeTwo));
						if ($lineType == 1)
						{
							if ($xedgeOne == $xedgeTwo)
							{
								$distance = abs($yedgeOne - $yedgeTwo);
							}
							else
							{
								$distance = abs($xedgeOne - $xedgeTwo);
							}
						}
						else if ($lineType == 0)
						{
							$distance = sqrt(pow(($xedgeTwo - $xedgeOne), 2) + pow(($yedgeTwo - $yedgeOne), 2));
						}
						else
						{
							throw new \Exception("A coordinate resulted in an invalid line type, please review the supplied parameters");
						}

						print_r(array(array($xedgeOne, $xedgeTwo), array($yedgeOne, $yedgeTwo)));
						echo "<br/>".$distance."<br/>";
						$verticeSigma += $distance;
					}
				}
				$firstSide = $this->vertices[0];
				$lastSide = $this->vertices[$points - 1];
				$lineType = self::checkLineType(
									array($firstSide->point[0], $lastSide->point[0]),
									array($firstSide->point[1], $lastSide->point[1])
								);
				if ($lineType == 1)
				{
					if ($xedgeOne == $xedgeTwo)
					{
						$distance = abs($yedgeOne - $yedgeTwo);
					}
					else
					{
						$distance = abs($xedgeOne - $xedgeTwo);
					}
				}
				else if ($lineType == 0)
				{
					$distance = sqrt(pow(($xedgeTwo - $xedgeOne), 2) + pow(($yedgeTwo - $yedgeOne), 2));
				}
				else
				{
					throw new \Exception("A coordinate resulted in an invalid line type, please review the supplied parameters");
				}
				$verticeSigma += $distance;

				return $verticeSigma;
			}
		}

		private function checkLineType($x, $y)
		{
			if (!is_array($x) || !is_array($y))
			{
				throw new \Exception("Array values were expected as parameters to the checkLineType method");
			}
			else
			{
				if (self::isStraightLine($x, $y))
				{
					return 1;
				}
				else if (self::isDiagonalLine($x, $y))
				{
					return 0;
				}
				else
				{
					return -1;
				}

			}
		}

		public function isStraightLine($x, $y)
		{
			if ($x[0] == $x[1] || $y[0] == $y[1])
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function isDiagonalLine($x, $y)
		{
			if (!$this->isStraightLine($x, $y))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function getVertices()
		{
			return $this->vertices;
		}
	}
?>
