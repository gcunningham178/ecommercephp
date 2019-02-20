<?php

require_once ("DB.class.php");


//Creates the navbar with appropriate links and returns the string 
function createNavbar()
{
        return " <div id='container'>
        <!--Navigation bar-->
                <nav class='navbar w3-top w3-container'>
                  <div class='container-fluid'>
                    <div class='navbar-header'>
                      <a class='navbar-brand w3-text-shadow' href='/'>Buy the Way</a>
                    </div>
                    <div id='healthNav'>
                      <ul class='nav navbar-nav navbar-right'>
                        <li class='active'><a href='index.php'>Home</a></li>
                        <li><a href='cart.php'>Cart</a></li>
                        <li><a href='admin.php'>Admin</a></li>
                      </ul>
                    </div>
                  </div>
                </nav>";
}

//Creates the header section for the pages and returns the string for it
function createHeader($title)
{
     return "<head>
      <title>$title</title>
     <meta charset='utf-8'>
     <meta name='viewport' content='width=device-width, initial-scale=1'>
     <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
     <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
     <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
     <link rel ='stylesheet' href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css'>
     <link rel='stylesheet' href='https://www.w3schools.com/lib/w3.css'>
     <link rel='stylesheet' href='https://www.w3schools.com/lib/w3-colors-vivid.css'>
     <link rel='stylesheet' type='text/css' href='css/ecommerce.css'>
     </head>";   
}

//Creates the footer 
function createFooter()
{
      return "<!--Footer section-->
     <footer class='w3-container footer'>
        <h6>Copyright &copy; All Rights Reserved.</h6>
    </footer>
    </div>";  
}

//Creates the content section 
function createContentSection($pageStr)
{
      $contentStr = "";
      if($pageStr == 'index')
      {
         $contentStr .= "<div id='content' class='w3-container'>
                           <div class='w3-cell-row'>" .
                           createCatalogSection('left') .
                           createSalesSection('right') .
                           "</div>
               </div>";
      }
      elseif($pageStr == 'cart')
      {
         $contentStr .= "<div id='content' class='w3-container'>
                           <div class='w3-cell-row'>" .
                           createCartSection();
                           "</div>
               </div>";
      }
      elseif($pageStr == 'edit')
      {
         $contentStr .= createEditSection();
      }
      return $contentStr;
}

//Creates the product table in the edit section with products that are not on sale
function createEditSection()
{
    return "<div class='w3-cell-row'>" .
        createProductEditTable() .
        //createSaleEditTable() .
    "</div>";       
}

//Creates the product table in the edit section with products that are not on sale
function createProductEditTable()
{
    return "<div class='w3-cell'>" .
        getProductsWithEditLinks() .
    "</div>";       
}

//Creates the product table in the edit section with products that are  on sale
function createSaleEditTable()
{
    return "<div class='w3-cell'>" .
        getSalesProductsWithEdit() .
    "</div>";       
}


//Creates the catalog section with products that are not on sale
function createCatalogSection()
{
    return "<div class='w3-cell'>" .
        "<h2 class='w3-black'>Catalog Section</h2>" .
        getProducts() .
    "</div>";       
}

//creates the sales section for the sales items
function createSalesSection()
{
     return "<div class='w3-cell'>" .
        "<h2 class='w3-black'>Sales Section</h2>" .
        getSalesProducts() .
     "</div>";
}

//creates the cart section for the cart.php
function createCartSection()
{
     return "<div class='w3-cell'>" . 
        getCartProducts() .
     "</div>";
}

//Returns an HTML table of products that are not on sale
function getProducts()
{
        $page = $_REQUEST['p'];
        $max = 5;
        
        if($page=='')
        {
         $page=1;
         $current = 0;
        }
        
        else
        {
         $current = $max * ($page-1);
        }
        
        $dbp = new DB();
        $data = $dbp->retrieveAllProducts($current, $max);
        
        if(count($data) > 0)
        {
                $contentString = "<table class='w3-table w3-striped w3-card-4'>\n 
                                                <tr class='w3-green'><th>Product Name</th><th>Product Description</th><th>Product Price</th>" .
                                                "<th>Quantity</th><th>Preview</th><th>Sales Price</th></tr>\n";
                                                
                foreach($data as $row)
                {
                        $proName = $row['name'];
                        $proDesc = $row['desc'];
                        $proPrice = $row['prodPrice'];
                        $proQty = $row['qty'];
                        $proSalesPrice = $row['salesPrice'];
                        
                        $contentString .= "<tr><td>{$row['name']}</td>
                                                <td>{$row['desc']}</td><td>{$row['prodPrice']}</td><td>{$row['qty']}</td>
                                                <td><img src='images/{$row['imgName']}.png' width='50' height='50' alt='picture'/><td>
                                                <td>{$row['salesPrice']}</td>
                                                <td><form method='post'>
                                                <input type='submit' name='submitCart' class='w3-btn w3-ripple w3-black' value='Add to Cart'/>
                                                </form></td></tr>\n";
                                                
                                                if(isset($_POST['submitCart']))
                                                {
                                                        $dbp->insertIntoCart($row['name'], $row['desc'], $row['prodPrice'], $row['qty'], $row['salesPrice']);
                                                        
                                                }
                                                
                }
                
                $contentString .= "</table>\n";
                $contentString .= paginateResults($page, 3);
        }
        else
        {
                $contentString = "<h2>No available products</h2>";
        }
        return $contentString;
}

//Returns an HTML table of products that are not on sale with edit links
function getProductsWithEditLinks()
{
        $page = $_REQUEST['p'];
        $max = 5;
        
        if($page=='')
        {
         $page=1;
         $current = 0;
        }
        
        else
        {
         $current = $max * ($page-1);
        }
        
        $dbp = new DB();
        $data = $dbp->retrieveAllProducts($current, $max);
        
        if(count($data) > 0)
        {
                $contentString = "<table class='w3-table w3-striped w3-card-4'>\n 
                                                <tr class='w3-green'><th>Product Name</th><th>Product Description</th></tr>\n";
                                                
                foreach($data as $row)
                {
                        $proName = $row['name'];
                        $proDesc = $row['desc'];
                        $proPrice = $row['prodPrice'];
                        $proQty = $row['qty'];
                        $proSalesPrice = $row['salesPrice'];
                        
                        $contentString .= "<tr><td>{$row['name']}</td>
                                                <td>{$row['desc']}</td>
                                                <td><a href='edit.php?id={$row['id']}'>Edit Item</a></td></tr>\n";
                                                
                }
                
                $contentString .= "</table>\n";
                $contentString .= paginateResults($page, 3);
                
        }
        else
        {
                $contentString = "<h2>No available products</h2>";
        }
        return $contentString;
}

//Paginate results for sales items 
function paginateResults($page, $totalResults)
{
  $pageNumstr = '<ul class="pagenum">';
        for($i=1; $i<=$totalResults; $i++)
        {
           if($i==$page)
           {
                  $pageNumstr .= '<li">'.$i.'</li>';
           }
           else
           {
                         $pageNumstr .= '<li><a href="index.php?p='.$i.'">'.$i.'</a></li>';
           }
        }
                $pageNumstr .= '</ul>';
                return $pageNumstr ;
}
        
//Inserts new product into the database
function insertNewItem()
{
    if(isset($_POST["submit"]))
    {
        
    
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            //Initialize form variables
            $productName = $productDesc = $productPrice = $prodQty = $sPrice = $pass = "";
            $imageName = "testimg";
            //Checks and validates the product name
            if (empty($_POST['prodName']))
            {
               echo "<p>Please enter valid product name</p>";
               exit();
            }
            else
            {
               $productName = sanitizeInput($_POST['prodName']);    
            }
            
            //Checks and validates the product description
            if (empty($_POST['prodDesc']))
            {
               echo "<p>Please enter valid product description</p>";
               exit();
            }
            else
            {
               $productDesc = sanitizeInput($_POST['prodDesc']);    
            }
            
            //Checks and validates the product price
            if (empty($_POST['prodPrice']))
            {
               echo "<p>Please enter valid product price</p>";
               exit();
            }
            else
            {
               $productPrice = sanitizeInput($_POST['prodPrice']);    
            }
            
            //Checks and validates the quantity
            if (empty($_POST['qty']))
            {
               echo "<p>Please enter valid product quantity</p>";
               exit();
            }
            else
            {
               $prodQty = sanitizeInput($_POST['qty']);    
            }
            
            //Checks and validates the sales price of the product 
            if (empty($_POST['salesPrice']))
            {
               echo "<p>Please enter the valid sales price of the product</p>";
               exit();
            }
            else
            {
               $sPrice = sanitizeInput($_POST['salesPrice']);    
            }
            
            //Validates admin password 
            if (empty($_POST['pswd']) || $_POST['pswd'] != 'buyMeThings')
            {
               echo "<p>Please enter valid password before submitting</p>";
               exit();
            }
            else
            {
               $pass = sanitizeInput($_POST['pswd']);    
            }
            
            $dbp = new DB();
            $dbp->insert($productName, $productDesc, $productPrice, $prodQty, $imageName, $sPrice);
            
            header('Location: index.php'); 
        }
    }
}


//Creates an HTML table of all Cart objects
function getCartProducts()
{
        $dbp = new DB();
        $data = $dbp->retrieveAllCartProducts();
        
        if(count($data) > 0)
        {
                $contentString = "<table class='w3-table w3-striped w3-card-4'>\n 
                                                <tr><th>Product Name</th><th>Product Description</th>" .
                                                "<th>Quantity</th><th>Price</th></tr>\n";
                                                
                foreach($data as $row)
                {
                        if($row['salesPrice'] > 0)
                        {
                                $contentString .="<tr><td>{$row['name']}</td>
                                                <td>{$row['desc']}</td><td>{$row['qty']}</td>
                                                <td>{$row['salesPrice']}</td></tr>\n";
                        }
                        else
                        {
                                $contentString .="<tr><td>{$row['name']}</td>
                                                <td>{$row['desc']}</td><td>{$row['qty']}</td>
                                                <td>{$row['prodPrice']}</td></tr>\n";
                        }
                        
                                                
                }
                
                $contentString .= "</table>\n";
                $contentString .= "<td><form>
                <input type='submit' class='w3-btn w3-ripple w3-black' name='clearCart' value='Clear Cart'/>
                </form></td></tr>\n";
                
        }
        if(isset($_POST['clearCart']))
        {
             $dbp->clearCart();
             header('Location: cart.php'); 
        }
        else
        {
             $contentString = "<h2>Empty Cart</h2>";
        }
        return $contentString;
}
        
insertNewItem();

//Returns a HTML table of sales items 
function getSalesProducts()
{
        $dbp = new DB();		
        $data = $dbp->getAllProductsOnSale();
        
        
        if(count($data) > 0)
        {
                $contentString = "<table class='w3-table w3-striped w3-card-4'>\n 
                                                <tr class='w3-green'>
                                                <th>Product Name</th><th>Product Description</th><th>Product Price</th>" .
                                                "<th>Quantity</th><th>Preview</th><th>Sales Price</th></tr>\n";
                                                
                foreach($data as $row)
                {
                        $contentString .="<tr><td>{$row['name']}</td>
                                                <td>{$row['desc']}</td><td>{$row['prodPrice']}</td><td>{$row['qty']}</td>
                                                <td><img src='images/{$row['imgName']}.png' width='50' height='50' alt='picture'/><td>
                                                <td>{$row['salesPrice']}</td>
                                                <td><input type='submit' class='w3-btn w3-ripple w3-black' name='addToCart' value='Add to Cart'/></td></tr>\n";
                                                
                                                if(isset($_POST['addToCart']))
                                                {
                                                        $dbp->insertIntoCart($row['name'], $row['desc'], $row['prodPrice'], $row['qty'], $row['salesPrice']);
                                                        
                                                }
                                                
                }
                
                $contentString .= "</table>\n";
                
        }
        else
        {
                $contentString = "<h2>No available products</h2>";
        }
        return $contentString;
}

//Returns a HTML table of sales items 
function getSalesProductsWithEdit()
{
        $dbp = new DB();		
        $data = $dbp->getAllProductsOnSale();
        
        
        if(count($data) > 0)
        {
                $contentString = "<table class='w3-table w3-striped w3-card-4'>\n 
                                                <tr class='w3-green'>
                                                <th>Product Name</th><th>Product Description</th></tr>\n";
                                                
                foreach($data as $row)
                {
                        $contentString .="<tr><td>{$row['name']}</td>
                                                <td>{$row['desc']}</td>
                                                <td><a href='edit.php?id={$row['id']}'>Edit Item</a></td></tr>\n";
                                               
                                                
                }
                
                $contentString .= "</table>\n";
                
        }
        else
        {
                $contentString = "<h2>No available products</h2>";
        }
        return $contentString;
}

/*
 *  Sanitizes form input and returns the sanitized input 
 */
function sanitizeInput($input)
{
  $input = trim($input);
  $input = stripslashes($input);
  $input = strip_tags($input);
  $input = htmlspecialchars($input);
  //$input = mysqli_real_escape_string($input);
  return $input;
}

//Creates edit form and populates with appropriate values
function createEditForm()
{
        $id = $_GET['id'];
        $dbp = new DB();
        $data = $dbp->retrieveProductBasedOnId($id);
        $formStr = "";
        foreach($data as $row)
        {
              $formStr .= "<form action='' method='POST' class='w3-container w3-card-4'> 
                                <p>
                                <input class='w3-input' type='text' name='prodName' value='{$row['name']}'>
                                <label>Name</label></p>
                        
                                <p>
                                <input class='w3-input' type='text' name='prodDesc' value='{$row['desc']}'>
                                <label>Description</label></p>
                        
                                <p>
                                <input class='w3-input' type='text' name='prodPrice' value='{$row['prodPrice']}'>
                                <label>Price</label></p>
                                
                                <p>
                                <input class='w3-input' type='text' name='qty' value='{$row['qty']}'>
                                <label>Quantity</label></p>
                                
                                <p>
                                <input class='w3-input' type='text' name='salesPrice' value='{$row['salesPrice']}'>
                                <label>Sale Price</label></p>
                                
                                <p>
                                <input class='w3-input' type='text' name='pswd'>
                                <label>Password</label></p>
                                
                                <span><input type='submit' class='w3-btn w3-green' name='editThis'/>
                                <input type='reset' class='w3-btn w3-green' name='reset'/>
                                </span>
                                
                            </form>";
                            
        }
        
                            if(isset($_POST['editThis']))
                            {
                                $updatedRow = $dbp->updateProduct($_POST['name'], $_POST['prodName'], $_POST['prodDesc'], $_POST['prodPrice'], $_POST['qty'], $_POST['salesPrice'], $id);
                                header('Location: index.php');
                            }
        return $formStr;
}
?>