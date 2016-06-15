<?php

session_start();

class Item
{
	private $id;
	
	function __construct($id)
	{
		$this->id = $id;
	}
	
	function get_id()
	{
		return($this->id);
	}
	
	function get_name()
	{
		if($this->get_id() == 0)
		{
			return("box");
		} else
		{
			return("key");
		}
	}
	
	function get_price_each()
	{
		if($this->get_id() == 0)
		{
			return("$0.10");
		} else
		{
			return("$5.25");
		}
	}
	
	function get_image_location()
	{
		if($this->get_id() == 0)
		{
			return("http://innovationzealot.typepad.com/photos/uncategorized/2007/11/28/open_box.jpg");
		} else
		{
			return("http://images.wisegeek.com/brass-key.jpg");
		}
	}
	
	function get_description()
	{
		if($this->get_id() == 0)
		{
			return("a box.");
		} else
		{
			return("a key.");
		}
	}
}

function get_featured_items()
{
	$one = new Item(0);
	$two = new Item(1);
	return array($one, $two);
}

if(!isset($_SESSION["cart"]))
{
	$_SESSION["cart"] = array();
}

?>