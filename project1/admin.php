<!DOCTYPE html>

<html lang="en">
    <?php require_once 'LIB_project1.php';?>
    <?php echo createHeader('Admin');?>
<body>

<?php echo createNavbar(); ?>


<div id="content" class="w3-container">
    
            <div class="w3-cell-row">
                        <div class="w3-cell">
                            <div class="w3-container w3-green">
                            <h2>Add Item for Sale</h2>
                            </div>
            
                            <form action="LIB_project1.php" method="POST"class="w3-container w3-card-4"> 
                                <p>
                                <input class="w3-input" type="text" name="prodName">
                                <label>Name</label></p>
                        
                                <p>
                                <input class="w3-input" type="text" name="prodDesc">
                                <label>Description</label></p>
                        
                                <p>
                                <input class="w3-input" type="text" name="prodPrice">
                                <label>Price</label></p>
                                
                                <p>
                                <input class="w3-input" type="text" name="qty">
                                <label>Quantity</label></p>
                                
                                <p>
                                <input class="w3-input" type="text" name="salesPrice">
                                <label>Sale Price</label></p>
                                
                                <p>
                                <input class="w3-input" type="text" name="pswd">
                                <label>Password</label></p>
                                
                                <span><input type="submit" class="w3-btn w3-green" name="submit"/>
                                <input type="reset" class="w3-btn w3-green" name="reset"/>
                                </span>
                                
                            </form>
                      </div>
                </div><br/>
       <?php echo createContentSection('edit'); ?>
</div>

<?php echo createFooter();?>
</body>
</html>