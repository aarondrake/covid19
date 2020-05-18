<?php

namespace src;
require_once (__DIR__ .'/jpgraph/jpgraph.php');
require_once (__DIR__ .'/jpgraph/jpgraph_line.php');

class graphing
{
	protected $width = 1000;
	protected $height = 600;
	protected $x_axis_title;
	protected $y_axis_title;

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @param int $width
	 */
	public function setWidth($width)
	{
		$this->width = $width;
	}

	/**
	 * @return int
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * @param int $height
	 */
	public function setHeight($height)
	{
		$this->height = $height;
	}

	/**
	 * @return mixed
	 */
	public function getXAxisTitle()
	{
		return $this->x_axis_title;
	}

	/**
	 * @param mixed $x_axis_title
	 */
	public function setXAxisTitle($x_axis_title)
	{
		$this->x_axis_title = $x_axis_title;
	}

	/**
	 * @return mixed
	 */
	public function getYAxisTitle()
	{
		return $this->y_axis_title;
	}

	/**
	 * @param mixed $y_axis_title
	 */
	public function setYAxisTitle($y_axis_title)
	{
		$this->y_axis_title = $y_axis_title;
	}

	public function createGraph($title , $datasets = [], $name = '', $path = "./")
	{
		// Create a graph instance
		$graph = new \Graph($this->getWidth(),$this->getHeight());
		// Specify what scale we want to use,
		// int = integer scale for the X-axis
		// int = integer scale for the Y-axis
		$graph->SetScale('intint');

		$graph->title->Set($title);

		$graph->xaxis->title->Set($this->getXAxisTitle());
		$graph->yaxis->title->Set($this->getYAxisTitle());

		foreach ($datasets as $dataset)
		{
			$lineplot=new \LinePlot($dataset);
			$graph->Add($lineplot);
		}
		// Create the linear plot
		$gdImgHandler = $graph->Stroke(_IMG_HANDLER);

		$fileName = ($name) ? "${name}.png" : str_replace(" ", "_", $title).".png";
		$graph->img->Stream($path.$fileName);
		return $path.$fileName;
	}
}