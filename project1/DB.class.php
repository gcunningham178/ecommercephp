<?php
class DB
{
	private $connection;
	
	//Constructs a DB object
	function __construct()
	{
		require_once("../../../dbinfo.php");
		$this->connection = new mysqli('localhost', 'gtc5091', 'fr1end', 'gtc5091');
		
		if($this->connection->connect_error)
		{
			echo "connection failed: " .mysqli_connect_error();
			die();
		}
	}
	
	//Retrieves all products that are not on sale and returns an array of these records
	function retrieveAllProducts($current, $max)
	{
		$data = array();
		$queryStr = "SELECT Id, ProdName, ProdDesc, ProdPrice, Quantity, ImageName, SalesPrice from product WHERE SalesPrice = ? LIMIT $current, $max";
		
		if($stmt = $this->connection->prepare($queryStr))
		{
			$stmt->bind_param("d", $sPrice);
			$sPrice = 0.0;
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id, $name, $desc, $prodPrice, $qty, $imgName, $salesPrice);
			
			if($stmt->num_rows > 0)
			{
				while($stmt->fetch())
				{
					$data[] = array('id'=>$id,
						            'name'=>$name,
									'desc'=>$desc,
									'prodPrice'=>$prodPrice,
									'qty'=>$qty,
									'imgName'=>$imgName,
									'salesPrice'=>$salesPrice
									);
				}//while
			}//if num rows
		}//if stmt
		return $data;
	}
	
	
	//Retrieves product that is not on sale based on id
	function retrieveProductBasedOnId($pid)
	{
		$data = array();
		$queryStr = "SELECT Id, ProdName, ProdDesc, ProdPrice, Quantity, ImageName, SalesPrice from product WHERE Id = ? ";
		
		if($stmt = $this->connection->prepare($queryStr))
		{
			$stmt->bind_param("i", $pid);
			$sPrice = 0.0;
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id, $name, $desc, $prodPrice, $qty, $imgName, $salesPrice);
			
			if($stmt->num_rows > 0)
			{
				while($stmt->fetch())
				{
					$data[] = array('id'=>$id,
						            'name'=>$name,
									'desc'=>$desc,
									'prodPrice'=>$prodPrice,
									'qty'=>$qty,
									'imgName'=>$imgName,
									'salesPrice'=>$salesPrice
									);
				}//while
			}//if num rows
		}//if stmt
		return $data;
	}
	
	
	
	//Selects all rows in the Cart table and returns an array of these records
	function retrieveAllCartProducts()
	{
		$data = array();
		$queryStr = "SELECT ProdName, ProdDesc, ProdPrice, Quantity, SalesPrice from cart";
		$sPrice = 0.0;
		if($stmt = $this->connection->prepare($queryStr))
		{
			//$stmt->bind_param("d", $sPrice);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($name, $desc, $prodPrice, $qty, $salesPrice);
			
			if($stmt->num_rows > 0)
			{
				while($stmt->fetch())
				{
					$data[] = array(
						            'name'=>$name,
									'desc'=>$desc,
									'prodPrice'=>$prodPrice,
									'qty'=>$qty,
									'salesPrice'=>$salesPrice
									);
				}//while
			}//if num rows
		}//if stmt
		return $data;
	}
	
	
	
	//Retrieves all products that are on sale and returns an array of these records
	function getAllProductsOnSale()
	{
		$data = array();
		$queryStr = "SELECT ProdName, ProdDesc, ProdPrice, Quantity, ImageName, SalesPrice from product WHERE salesPrice > ?";
		if($stmt = $this->connection->prepare($queryStr))
		{
			$stmt->bind_param("d", $sPrice);
			$sPrice = 0.0;
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($name, $desc, $prodPrice, $qty, $imgName, $salesPrice);
			
			if($stmt->num_rows > 0)
			{
				while($stmt->fetch())
				{
					$data[] = array(
						            'name'=>$name,
									'desc'=>$desc,
									'prodPrice'=>$prodPrice,
									'qty'=>$qty,
									'imgName'=>$imgName,
									'salesPrice'=>$salesPrice
									);
				}//while
			}//if num rows
		}//if stmt
		return $data;
	}
	
	
	//Insert a row in the Product table
    function insert($name, $desc, $prodPrice, $qty, $imgName, $salesPrice)
	{
		$queryString = "INSERT INTO product (ProdName, ProdDesc, ProdPrice, Quantity, ImageName, SalesPrice) VALUES (?, ?, ?, ?, ?, ?)";
		$insertId = -1;
		
		$pname = $name;
		$pdesc = $desc;
		$proPrice = $prodPrice;
		$pqty = $qty;
		$pimgName = $imgName;
		$psalesPrice = $salesPrice;
		
		if($stmt = $this->connection->prepare($queryString))
		{
			$stmt->bind_param("ssdisd", $pname, $pdesc, $proPrice, $pqty, $pimgName, $psalesPrice );
			
			$stmt->execute();
			$stmt->store_result();
			$insertId = $stmt->insert_id;
			$stmt->close();
		}
		else
		{
			trigger_error("there was an error....".$this->connection->error, E_USER_WARNING);
		}
		return $insertId;		
	}//insert
	
	//Insert a row in the Cart table
	function insertIntoCart($name, $desc, $prodPrice, $qty, $salesPrice)
	{
		$queryString = "INSERT INTO cart (ProdName, ProdDesc, ProdPrice, Quantity, SalesPrice) VALUES (?, ?, ?, ?, ?)";
		
		$insertId = -1;
		
		if($stmt = $this->connection->prepare($queryString))
		{
			$stmt->bind_param("ssdid", $name, $desc, $prodPrice, $qty, $salesPrice );
			$stmt->execute();
			$stmt->store_result();
			$insertId = $stmt->insert_id;
		}
		else
		{
			trigger_error("there was an error....".$this->connection->error, E_USER_WARNING);
		}
		return $insertId;		
	}//insert
	
	
	//Removes all records from the Cart table and returns the number of rows affected
	function clearCart()
	{
		$queryString = "DELETE FROM cart";
		$rowsDeleted = 0;
		if($stmt = $this->connection->prepare($queryString))
		{
			$stmt->execute();
			$rowsDeleted = $stmt->affected_rows;
		}
		else
		{
			trigger_error("there was an error....".$this->connection->error, E_USER_WARNING);
		}
	}//delete
	
	function updateProduct($name, $desc, $prodPrice, $qty, $salesPrice, $id)
	{
		$queryString = "UPDATE product SET ProdName = ?, ProdDesc = ?, ProdPrice = ?, Quantity = ?, SalesPrice = ? WHERE Id = ?";
		
		$insertId = -1;
		
		if($stmt = $this->connection->prepare($queryString))
		{
			$stmt->bind_param("ssdidi", $name, $desc, $prodPrice, $qty, $salesPrice, $id );
			$stmt->execute();
			$stmt->store_result();
			$numRows = $stmt->affected_rows;
		}
		else
		{
			trigger_error("there was an error....".$this->connection->error, E_USER_WARNING);
		}
		return $numRows;
	}

}


