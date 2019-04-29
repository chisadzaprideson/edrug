<?php
	#Call Db Connection
	include('dbconnect.php');
	session_start();
		$cartAlia=$_SESSION['cartAlia'];
		$cartid=$_SESSION['cartID'];
		$cartphon= $_SESSION['cartPhone'];
		$cartCost=$_SESSION['cartCost'];
		
		$preID=$_SESSION['PresID'];
		$preKey=$_SESSION['PresPin'];
		
		//If page accessed without log in
	if(empty($_SESSION['cartAlia'])){
		header("location: home.php");//redirects to another page
	} 
   
	if(isset($_POST['btnSend'])){
		//Bank Details
		$bank=$_POST['cmbBank'];
		$acnt=$_POST['txtAccount'];
		$pin=$_POST['txtAccPin'];
		$regdat=date("d/m/Y");
		//Check Account First
		$query="SELECT * FROM banks WHERE  BnkNam='$bank' AND BnkAcc='$acnt' AND BnkPin='$pin'";
					
		$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)<1){
				echo('<SCRIPT>
					window.alert("Provided bank account details failed to match any bank account on the selected bank name.");
					window.location.href="mycart.php";
					</SCRIPT>');
				
				exit();	
			}else{
				    $row = mysqli_fetch_array($result);
					$total=$cartCost;	
					$balance= $row['BnkBal'];
					$balance=(double)$balance;
					$cost=(double)$total;
					IF ($balance < $cost){
					echo('<SCRIPT>
					window.alert("Bank balance is insufficient for car payment. Your bank account balance is $"+"'.$balance.'");
					window.location.href="mycart.php";
					</SCRIPT>');
					exit();
					}
				}	
			mysqli_free_result($result);
	
		//After checking the balance find receipt number(cart pin)
		$query="SELECT * FROM increments";
		$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
		$row = mysqli_fetch_array($result);
		$recnum=$row['RecNumber'];
		mysqli_free_result($result);
			
			//Bank Deduction
			mysqli_query($myConn,"UPDATE banks SET BnkBal=BnkBal-'$total' WHERE BnkNam='$bank' AND BnkAcc='$acnt' AND BnkPin='$pin'");
			
			//Deduct Goods
			$query="SELECT * FROM cartinfor WHERE NatID='$cartid' AND Paid ='No'";
				
			$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_array($result);
				do{
					$stkID=$row['ProID'];
					$numBot=$row['NumTaken'];
					$numBot=$row['NumTaken'];
				    mysqli_query($myConn,"UPDATE products SET InStk = InStk - $numBot WHERE ID='$stkID'");	
					
				}while ($row = mysqli_fetch_array($result));
			}
			
			mysqli_free_result($result);
			
			//Mark prescription as paid for drugs
			$paidVar="Yes Paid";
			mysqli_query($myConn,"UPDATE prescriptions SET Purchased = '$paidVar' WHERE ID='$preID' AND Purchased ='Yes'");
			
			
			//Update cart infor
			mysqli_query($myConn,"UPDATE cartinfor SET CartPin = '$recnum',Paid = 'Yes',RecNum = '$recnum' WHERE NatID='$cartid' AND Paid ='No'");
			mysqli_query($myConn,"UPDATE increments SET RecNumber = RecNumber + 1");
			unset ($_SESSION['cartAlia']);
					unset ($_SESSION['cartID']);
					unset ($_SESSION['cartPhone']);
					unset ($_SESSION['cartCost']);
			session_destroy();
			echo('<SCRIPT>
				   window.alert("Cart payment successfull. Your receipt number is "+"'.$recnum.'");
				   window.location.href="home.php";
				</SCRIPT>');
			exit();
	       
	}
	//Cart Infor Actions(Add,Remove,Delete)
   	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
		$sentence=$_GET["id"];
		$product=substr($sentence,3,4);
		$actio=substr($sentence,0,3);
		$cartid=$_SESSION['cartID'];
			//First search the product cost
			$sql="SELECT * FROM products WHERE ID='$product'";
			$result=mysqli_query($myConn,$sql);
			$row=mysqli_fetch_assoc($result);//attach row variable to STUDENT table rows
			$price=$row["Cost"];
			$prodNam=$row["Alias"];
			$PERC=0.1;
			$lesPercent=$price - ($PERC * $price);
			$lesPercent=round($lesPercent,2);
		$noooh="No";
		$nuh="No";
		$regDat=date("d/m/Y");
		mysqli_free_result($result);
		
		if ($actio=="Add"){
			
			$cartCost=$_SESSION['cartCost'];
			$_SESSION['cartCost']= $cartCost + $price;
			$cartCost=$_SESSION['cartCost'];	
			
			mysqli_query($myConn,"UPDATE cartinfor SET RegDat = '$regDat',NumTaken = NumTaken + 1,TotCost = TotCost + '$price',FinalAmount = FinalAmount + '$lesPercent' WHERE ProID='$product' AND NatID='$cartid' AND Paid ='$nuh'");
			echo("<SCRIPT LANGUAGE='JavaScript'>
				window.location.href='mycart.php'
				</SCRIPT>");
			exit(); 
		}else if ($actio=="Rem"){
			
			$cartCost=$_SESSION['cartCost'];
			$_SESSION['cartCost']= $cartCost - $price;
			$cartCost=$_SESSION['cartCost'];
			
			//See if its one or more
			$sql="SELECT * FROM cartinfor WHERE ProID='$product' AND NatID='$cartid' AND Paid ='$nuh'";
			$result=mysqli_query($myConn,$sql);
			$row=mysqli_fetch_assoc($result);
			if (mysqli_num_rows($result)>1){
				mysqli_query($myConn,"UPDATE cartinfor SET NumTaken = NumTaken - 1,TotCost = TotCost - '$price',FinalAmount = FinalAmount - '$lesPercent' WHERE ProID='$product' AND NatID='$cartid' AND Paid ='$nuh'");
				echo("<SCRIPT LANGUAGE='JavaScript'>
					window.location.href='mycart.php'
					</SCRIPT>");
				exit();
			}else{
				mysqli_query($myConn,"DELETE FROM cartinfor WHERE ProID='$product' AND NatID='$cartid' AND Paid ='$nuh'");
				
			    mysqli_query($myConn,"UPDATE prescriptions SET Purchased = '$noooh' WHERE DrugNam='$prodNam' AND ID='$preID' ");

				
				echo("<SCRIPT LANGUAGE='JavaScript'>
					window.location.href='mycart.php'
					</SCRIPT>");
				exit();
			}
			
		}else if ($actio=="Del"){
				$sql="SELECT * FROM cartinfor WHERE ProID='$product' AND NatID='$cartid' AND Paid ='$nuh'";
				$result=mysqli_query($myConn,$sql);
				$row=mysqli_fetch_assoc($result);//attach row variable to STUDENT table rows
				$totItmCost=$row["TotCost"];
				mysql_free_result($result);
				$cartCost=$_SESSION['cartCost'];
				$_SESSION['cartCost']= $cartCost - $totItmCost;
				$cartCost=$_SESSION['cartCost'];
				
				
				mysqli_query($myConn,"DELETE FROM cartinfor WHERE ProID='$product' AND NatID='$cartid' AND Paid ='$nuh'");
				
				mysqli_query($myConn,"UPDATE prescriptions SET Purchased = '$noooh' WHERE DrugNam='$prodNam' AND ID='$preID' ");

				echo("<SCRIPT LANGUAGE='JavaScript'>
					window.location.href='mycart.php'
					</SCRIPT>");
					
				exit();
		}
			
		
	}			
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Edrug Online Store</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700,400italic,300' rel='stylesheet' type='text/css'>


    <!-- all css here -->
    <!-- bootstrap v3.3.6 css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"/>
    <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Animated text css -->
    <link rel="stylesheet" href="css/animated.css">
    <!-- owl.carousel css -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.transitions.css">
    <!-- font-awesome css -->
    <link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
    <!-- animate css -->
    <link rel="stylesheet" href="css/animate.css">
    <link href="css/jquery-ui.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <!-- style css -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr css -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <style>
        body {
            <!-- background: url("images/ok2.jpg"); -->
        }

        .well {
            background: #a51600;
            font-size: 14pt;
            color: #fff;
        }

        .thumbnail {
            display: block;
            padding: 4px;
            margin-bottom: 20px;
            line-height: 1.42857143;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 1px 1px 1px #d0d0d0;
            border-radius: 4px;
            -webkit-transition: border .2s ease-in-out;
            -o-transition: border .2s ease-in-out;
            transition: border .2s ease-in-out;
        }

        form {
            margin-bottom: 20px;
        }

        .w-160 {
            width: 160px;
        }

        .btn-active {
            background: #a51600;
            color: #fff;
        }

        .btn-active:hover {
            background: #8a1c0b;
            color: #fff;
        }
        form.form-search input[type=text] {
            padding: 10px;
            font-size: 17px;
            border: 1px solid #5bb85c;
            float: left;
            width: 85%;
            background: #f1f1f1;
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        form.form-search button {
            float: left;
            width: 15%;
            padding: 5px;
            background: #5bb85c;
            color: white;
            font-size: 17px;
            border: 1px solid #469847;
            border-left: none;
            cursor: pointer;
            border-top-left-radius: 0px;
            border-bottom-left-radius: 0px;
        }

            form.example button:hover {
                background: #0b7dda;
            }

        form.form-search::after {
            content: "";
            clear: both;
            display: table;
        }
        .cart-btn{
            width:100%;
            margin-bottom:15px;
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            margin-top: 20px;
        }
      
    </style>
	<SCRIPT LANGUAGE='JavaScript'>
			function myfunc(){
					//Validation Regex
					var IDalphanumeric=/^[-0-9a-zA-Z]+$/;
					var ricc= /^[0-9]+$/ ;
					var alphaspaces=/^[ a-zA-Z]+$/;
					var phon=/^\d{10}$/;
					var whitespaces=/[^a-z|^A-Z|^\s]/;
					var letters=/^[a-zA-Z]+$/;
					
				//Bank validator
				if(document.myFormw.cmbBank.value==""){
					window.alert('Submission failed. Select missing bank name.'); 
					return false;
				}
				//Bank Account validator
				if(document.myFormw.txtAccount.value==""){
					window.alert('Submission failed. Type in your missing bank account number.'); 
					return false;
				}
				//Bank account pin validator
				if(document.myFormw.txtAccPin.value==""){
					window.alert('Submission failed. Type in your missing bank account pin number.'); 
					return false;
				}
			   
				//code below now send the form after validation is complete
				document.getElementsByClassName("subby")[0].click();
			}
		</SCRIPT>
</head>
    <body >
    <!--Modal Registration-->
	<div id="myModPay" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content" style="background-color:#ffffcc;border:2px solid red;">
				<div class="modal-header">
					<h4 class="modal-title">Customer Cart Payment</h4>
					<table class="table table-striped  table-hover table-condensed pull-right" style="border-radius: 5px;">
					   
						<tr>
							<th><label style="color:green;">National ID (Cart ID)</label></th>
							<td><label style="color:green;"><?php echo $cartid; ?></label></td>
						</tr>
					   
						<tr>
							<th><label style="color:green;">Total Cost  $</label></th>
							<td><label style="color:green;"><?php echo $cartCost; ?></label></td>
						</tr>
                    </table>
				</div>
				<form method="POST" action="mycart.php" autocomplete="off" name="myFormw">
					<div class="modal-body">
						<label style="color:black;" for="cmbBank">Select Paying Bank Here</label>
						<select name="cmbBank" class="form-control" style="color:black;">
							<option value="" disabled selected hidden></option>
							<option value="CBZ">CBZ</option>
							<option value="Stanbic Bank">Stanbic Bank</option>
							<option value="Agribank">Agribank</option>	
							<option value="Barclays">Barclays</option>				
							<option value="Standard Chatered">Standard Chatered</option>							
						</select>
						<br><br>
						<input type="text" style="background-color:white;" class="form-control" name="txtAccount" placeholder="type your account number here"><br>
						<br>
						<input type="password" class="form-control" name="txtAccPin" placeholder="type your account pin here"><br>
					</div>
					<div class="modal-footer">
						<button class="btn btn-default btn-success" type="button" onclick="myfunc()">Submit</button>
						<input type="submit" name="btnSend" class="subby" hidden>
						<button type="button" class="btn btn-default btn-active" data-dismiss="modal">Close</button>
					</div>
					<label style="font-size:12px;color:green;left:100px;">NB:Please take note of the receipt number which is going to be provided to you on payment confirmation message to come after details submission.</label>

				</form>
			</div>
		</div>
	</div>
	
	
	
	<div class="container" style="background-color:rgba(0,0,0,0.8);padding:10px 0px;width:100%;">
		<div class="col-md-3">
			<img src="images\log.png" style="height:10%;width:360px;-moz-border-radius:15px;-webkit-border-radius:15px;"/>
		</div>
	
		<div class="col-md-6">
				<table class="table table-hover table-condensed" style="border-radius: 0px;">
				<tr>
					<th><label style="color:white;">Welcome</label></th>  
					<td><label><?php echo$cartAlia; ?></label></td>
				</tr>
			</table>
		</div>
		
		<div class="col-md-3">

		
		</div>
    </div>  
	<br>
    <div class="container" style="background-color:#f5f5f5;padding: 0px 0px;width:100%;">
		<div class="col-md-3">
            <div class="col-md-12">
                <div>
					<br><br><br>
                    <a href="shopping.php" style="text-align:center;height:37px;background-color:#535ba0;border:#535ba0;" class="btn btn-success"><span class='glyphicon glyphicon-arrow-left pull-left'>   Back To Shopping</a>
                </div>

                <table class="table table-striped  table-hover table-condensed" style="border-radius: 5px;">
                    <tr>
                        <th><label style="color:green;">Customer Name</label></th>
                        <td><label style="color:green;"><?php echo $cartAlia; ?></label></td>
                    </tr>
                    <tr>
                        <th><label style="color:green;">National ID (Cart ID)</label></th>
                        <td><label style="color:green;"><?php echo $cartid; ?></label></td>
                    </tr>
                    <tr>
                        <th><label style="color:green;">Cellphone Number</label></th>
                        <td><label style="color:green;"><?php echo $cartphon; ?></label></td>
                    </tr>
                    <tr>
                        <th><label style="color:green;">Total Cost  $</label></th>
                        <td><label style="color:green;"><?php echo $cartCost; ?></label></td>
                    </tr>
                </table>
                <div class="clearfix"></div>

            </div>
        </div>
        <div class="col-md-9">  
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Customer Cart Details</h2>
                        <a href="#myModPay" data-toggle="modal" style="background-color:#535ba0;border:#535ba0;" class="btn btn-success pull-right">Make Cart Payment <span class='glyphicon glyphicon-arrow-right'></a>
                    </div>
                    <?php
                    $nuh="No";
                    // Attempt select query execution
                    $sql = "SELECT * FROM cartinfor WHERE NatID='$cartid' AND Paid ='$nuh'";
                    if($result = mysqli_query($myConn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Product ID</th>";
                                        echo "<th>Product Name</th>";
                                        echo "<th>Product Cost $</th>";
                                        echo "<th>Number Added</th>";
                                        echo "<th>Total Cost $</th>";
										echo "<th>Add One</th>";
                                        echo "<th>Remove One</th>";
                                        echo "<th>Remove All</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['ProID'] . "</td>";
                                        echo "<td>" . $row['Product'] . "</td>";
                                        echo "<td>" . $row['ItemCost'] . "</td>";
                                        echo "<td>" . $row['NumTaken'] . "</td>";
										echo "<td>" . $row['TotCost'] . "</td>";
                                        echo '<td style="text-align:center;">';
                                            echo "<a href='mycart.php?id=Add". $row['ProID'] ."' title='Add One Item'><span class='glyphicon glyphicon-plus'></span></a>";
                                        echo "</td>";
										 echo '<td style="text-align:center;">';
											echo "<a href='mycart.php?id=Rem". $row['ProID'] ."' title='Remove One Item' ><span class='glyphicon glyphicon-minus'></span></a>";
                                        echo "</td>";
										echo '<td style="text-align:center;">';
											echo "<a href='mycart.php?id=Del". $row['ProID'] ."' title='Remove All' ><span class='glyphicon glyphicon-remove'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($myConn);
                    }
 
                    // Close connection
                    mysqli_close($myConn);
                    ?>
        </div>
		<!--column 9 end here -->
       
    </div>
        <!-- all js here -->
        <!-- jquery latest version -->
        <script src="js/vendor/jquery-1.12.4.min.js"></script>
        <!-- bootstrap js -->
        <script src="js/bootstrap.min.js"></script>
        <!-- Google Map js -->
        <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
        <script src="js/jquery-ui.js"></script>		<!-- owl.carousel js -->
        <script src="js/owl.carousel.min.js"></script>
        <!-- easing js -->
        <script src="js/easing.js"></script>
        <!-- jquery.appear js -->
        <script src="js/jquery.appear.js"></script>
        <!-- animated js -->
        <script src="js/animated.js"></script>
        <!-- Mixitup js -->
        <script src="js/jquery.mixitup.min.js"></script>
        <!-- wow js -->
        <script src="js/wow.min.js"></script>
        <!-- counter js -->
        <script src="js/jquery.counterup.min.js"></script>
        <script src="js/waypoints.js"></script>
        <!-- plugins js -->
        <script src="js/plugins.js"></script>
        <!-- main js -->
        <script src="js/main.js"></script>
        <!-- Calendar -->

        <script>
            $(function () {
                $("#datepicker,#datepicker1").datepicker();
            });
        </script>
        <!-- //Calendar -->
</body>	
	
</html>			