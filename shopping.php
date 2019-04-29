<?php
	#Call Db Connection
	include('dbconnect.php');
	session_start();
	
	$cartAlia=$_SESSION['cartAlia'];
	$cartid=$_SESSION['cartID'];
	$cartphon= $_SESSION['cartPhone'];
	$cartCost=$_SESSION['cartCost'];
	$sqlView="SELECT * FROM products";
    $VieID="";
	$prID=$_SESSION['PresID'];
	$prPin=$_SESSION['PresPin'];
		
	$btnOne="btn btn-active w-158";
	$btnTwo="btn btn-success  w-160";
	$btnThree="btn btn-success  w-160";
	$btnFour="btn btn-success  w-160";
	$btnFive="btn btn-success  w-159";
	$btnSix="btn btn-success  w-159";
	
	
	if(isset($_POST["btnSearchProduct"]) && !empty(trim($_POST["txtSearchKey"]))){
		$ker=$_POST['txtSearchKey'].'%';
		$sqlView="SELECT * FROM products WHERE Alias Like '$ker' ORDER BY Cost ASC";	
	} 

	//to statecolor again here
	if(isset($_POST['all'])){
		$sqlView="SELECT * FROM products";
	}else if(isset($_POST['bottled'])){
		//Bottled
		$sqlView="SELECT * FROM products";
		$sqlView="SELECT * FROM products WHERE Category='Bottled'";
		
		$btnOne="btn btn-success w-158";
		$btnTwo="btn btn-active w-160";
		$btnThree="btn btn-success w-160";
		$btnFour="btn btn-success w-160";
		$btnFive="btn btn-success w-159";
		$btnSix="btn btn-success w-160";
		
	}else if(isset($_POST['pills'])){
		//Pills
		$sqlView="SELECT * FROM products";
		$sqlView="SELECT * FROM products WHERE Category='Pills'";
		
		$btnOne="btn btn-success w-158";
		$btnTwo="btn btn-success w-160";
		$btnThree="btn btn-active w-160";
		$btnFour="btn btn-success w-160";
		$btnFive="btn btn-success w-159";
		$btnSix="btn btn-success w-160";
		
	}else if(isset($_POST['capsules'])){
		//Capsules
		$sqlView="SELECT * FROM products";
		$sqlView="SELECT * FROM products WHERE Category='Capsules'";
		
		$btnOne="btn btn-success w-158";
		$btnTwo="btn btn-success w-160";
		$btnThree="btn btn-success w-160";
		$btnFour="btn btn-active w-160";
		$btnSix="btn btn-success w-160";
		$btnFive="btn btn-success w-159";
		
	}else if(isset($_POST['other'])){
		//Other
		$sqlView="SELECT * FROM products";
		$sqlView="SELECT * FROM products WHERE Category='Other'";
		
		$btnOne="btn btn-success w-158";
		$btnTwo="btn btn-success w-160";
		$btnThree="btn btn-success w-160";
		$btnFour="btn btn-success w-160";
		$btnSix="btn btn-success w-160";
		$btnFive="btn btn-active w-159";
		
	}else if(isset($_POST['presc'])){
		//Other
		$sqlView="SELECT * FROM products";
		$yeah="Yes";
		$sqlView="SELECT * FROM products WHERE NeedPresc='$yeah'";
		
		$btnOne="btn btn-success w-158";
		$btnTwo="btn btn-success w-160";
		$btnThree="btn btn-success w-160";
		$btnFour="btn btn-success w-160";
		$btnFive="btn btn-success w-160";
		$btnSix="btn btn-active w-159";
	}
	
	//If page accessed without log in
	if(empty($_SESSION['cartAlia'])){
		header("location: home.php");//redirects to another page
	} 

	//Logout
	if(isset($_POST['lgout'])){
	
	unset ($_SESSION['cartAlia']);
	unset ($_SESSION['cartID']);
	unset ($_SESSION['cartPhone']);
	unset ($_SESSION['cartCost']);
	session_destroy();
	header("location: home.php");//redirects to another page
	}
	if(isset($_POST['remove'])){
		$cartid=$_SESSION['cartID'];
		$nuh="No";
		
		mysqli_query($myConn,"DELETE FROM cartinfor WHERE NatID='$cartid' AND Paid ='$nuh'");
		$_SESSION['cartCost']="0";
		$cartCost=$_SESSION['cartCost'];
			echo("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('The selected cart items for this ID number are successfully removed all.')
				window.location.href='shopping.php'
				</SCRIPT>");
			exit(); 
	}
	
		
	if(isset($_POST['btnSend'])){
		if(isset($_POST['txtDetails'])){
			$que=$_POST['txtDetails'];
		}else{
			echo("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('Query details are missing. Send failed.')
				window.location.href='shopping.php'
				</SCRIPT>");
			exit(); 
		}
	
	}
	if(isset($_POST['btnSend'])){
		$oldid=$_SESSION['cartID'];
		
		$_SESSION['cartID']=$_POST['txtID'];
		$_SESSION['cartAlia']=$_POST['txtAlias'];
	    $_SESSION['cartPhone']=$_POST['txtPhone'];
	   
		$cartAlia=$_SESSION['cartAlia'];
		$cartid=$_SESSION['cartID'];
		$cartphon= $_SESSION['cartPhone'];
		$nuh="No";
		
		mysqli_query($myConn,"UPDATE cartinfor SET NatID ='$cartid',Phone ='$cartphon',Alias ='$cartAlia' WHERE NatID ='$oldid' AND Paid ='$nuh'");
	
		$query="SELECT * FROM cartinfor WHERE NatID='$cartid' AND Paid ='$nuh'";
				
			$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			$cos=0.0;
			if (mysqli_num_rows($result)>0){
				
				while ($row = mysqli_fetch_array($result))	{
					$cos=$cos+ $row['TotCost'];
		        } 
				$_SESSION['cartCost']=$cos;
				$cartCost=$_SESSION['cartCost'];
			}
			mysqli_free_result($result);
		
	}	
	
	if(isset($_POST["btnSendPrID"]) && !empty(trim($_POST["txtPresID"]))){
		$preID=$_POST['txtPresID'];
		$preKey=$_POST['txtPresKey'];
		$YEA="Yes";
		$nuhhu="No";
		$xoxo="Saved";
		$yeaPaid="Yes Paid";
		$adde="Added";
		
		$result=mysqli_query($myConn,"SELECT * FROM prescriptions WHERE ID='$preID' AND Purchased NOT IN ('$yeaPaid')");
		$row=mysqli_fetch_assoc($result);
		$rows=mysqli_num_rows($result);
		if ($rows==0){
			echo("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('The prescription with the provided details was used already and can not be re-used.')
				window.location.href='shopping.php'
				</SCRIPT>");
			exit();
		}
		mysqli_free_result($result);
		mysqli_query($myConn,"UPDATE products SET Prescribed='$nuhhu'");
		$query="SELECT * FROM prescriptions WHERE ID='$preID' AND Pin='$preKey' AND Stat='$xoxo' ";
		//Executing the query into the database and store result in result variable
			$result=mysqli_query($myConn,$query);
			$row=mysqli_fetch_assoc($result);
			
			if (mysqli_num_rows($result)>1){
					
				$_SESSION['PresID']=$preID;
				$_SESSION['PresPin']=$preKey;
				mysqli_query($myConn,"UPDATE prescriptions SET Purchased='$nuhhu' WHERE ID='$preID' AND Purchased='$YEA'");
				do{
					//$DRUG=$row["SystID"];
					$DRUG=$row['DrugNam'].'%';
					mysqli_query($myConn,"UPDATE products SET Prescribed='$YEA' WHERE  Alias Like '$DRUG' ");
		        } while ($row = mysqli_fetch_array($result));
					
				$sqlView="SELECT * FROM products WHERE Prescribed='$YEA' ORDER BY Cost ASC";
			}elseif (mysqli_num_rows($result)==1){
				$_SESSION['PresID']=$preID;
				$_SESSION['PresPin']=$preKey;
				
				$DRUG=$row['DrugNam'].'%';
				mysqli_query($myConn,"UPDATE products SET Prescribed='$YEA' WHERE  Alias Like '$DRUG' ");
				$sqlView="SELECT * FROM products WHERE Prescribed='$YEA' ORDER BY Cost ASC";
			}else{
				echo("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('There is no any saved prescription in system matching your prescription ID.')
					window.location.href='shopping.php'
					</SCRIPT>");
				exit();
			}
			mysqli_free_result($result);

			
	} 
	
	
	//Add to cart
   	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
		$sentence=$_GET["id"];
		$secVal=strlen($sentence)-3;
		$product=substr($sentence,5,$secVal);
		$actio=substr($sentence,1,3);
		
		//ADD ACTION
		if($actio=="Add"){
			
			if ($_SESSION['cartID']=="") {
				echo("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('Please kindly fill your cart details first before selecting items to add to cart.')
					window.location.href='shopping.php'
					</SCRIPT>");
				exit(); 
			}else{
				$cartAlia=$_SESSION['cartAlia'];
				$cartid=$_SESSION['cartID'];
				$cartphon= $_SESSION['cartPhone'];
				//First search the product cost
				$sql="SELECT * FROM products WHERE ID='$product'";
				$result=mysqli_query($myConn,$sql);
				$row=mysqli_fetch_assoc($result);//attach row variable to STUDENT table rows
				
				
				$price=$row["Cost"];
				$PERC=0.1;
				$lesPercent=$price - ($PERC * $price);
				$lesPercent=round($lesPercent,2);
				
				$prodNamTa=$row["Alias"];
				$prodNamTo=$row["SIUVal"]." ".$row["SiUnit"];
				
				$PharmID=$row["PharmID"];
				
				
				$prodNam=$prodNamTa." ".$prodNamTo;
				$nuh="No";
				
				$onPres=$row["NeedPresc"];
				
				mysqli_free_result($result);
				
				if ($prID=="" AND $onPres=="Yes"){
					echo("<SCRIPT LANGUAGE='JavaScript'>
						window.alert('To add this drug to cart add the prescription details first.')
						window.location.href='shopping.php'
						</SCRIPT>");
					exit();
				}elseif ($prID!=="" AND $onPres=="Yes"){
					//NOW CHECK IF THE DRUG IS ON Prescription
					$rows1=mysqli_fetch_array(mysqli_query($myConn,"SELECT * from prescriptions WHERE DrugNam='$prodNamTa' AND ID='$prID' "));
					$row1=mysqli_num_rows(mysqli_query($myConn,"SELECT * from prescriptions WHERE DrugNam='$prodNamTa' AND ID='$prID' "));
					if($row1=='1'){
						$yeah="Yes";
						$alreadBot=$rows1['Purchased'];
						if ($alreadBot=="Yes"){
							echo("<SCRIPT LANGUAGE='JavaScript'>
								window.alert('The drug is already selected and canceled on prescription and can not be added to cart now.')
								window.location.href='shopping.php'
								</SCRIPT>");
							exit();
						}
						if ($alreadBot=="Yes Paid"){
							echo("<SCRIPT LANGUAGE='JavaScript'>
								window.alert('The drug was purchased and canceled on the currect prescription and can not be added to cart.')
								window.location.href='shopping.php'
								</SCRIPT>");
							exit();
						}
						
						mysqli_query($myConn,"UPDATE prescriptions SET Purchased = '$yeah' WHERE DrugNam='$prodNamTa' AND ID='$prID' ");
					}else{
						echo("<SCRIPT LANGUAGE='JavaScript'>
							window.alert('The drug is not on you current prescription and can not be added to cart.')
							window.location.href='shopping.php'
							</SCRIPT>");
						exit();
					}
				}elseif ($prID!=="" AND $onPres=="No"){
					//NOW CHECK IF THE DRUG IS ON Prescription
					$rows1=mysqli_fetch_array(mysqli_query($myConn,"SELECT * from prescriptions WHERE DrugNam='$prodNamTa' AND ID='$prID' "));
					$row1=mysqli_num_rows(mysqli_query($myConn,"SELECT * from prescriptions WHERE DrugNam='$prodNamTa' AND ID='$prID' "));
					if($row1=='1'){
						$yeah="Yes";
						$alreadBot=$rows1['Purchased'];
						if ($alreadBot=="Yes"){
							echo("<SCRIPT LANGUAGE='JavaScript'>
								window.alert('The drug is already selected and canceled on prescription and can not be added to cart now.')
								window.location.href='shopping.php'
								</SCRIPT>");
							exit();
						}
						if ($alreadBot=="Yes Paid"){
							echo("<SCRIPT LANGUAGE='JavaScript'>
								window.alert('The drug was purchased and canceled on the currect prescription and can not be added to cart.')
								window.location.href='shopping.php'
								</SCRIPT>");
							exit();
						}
					
					}
					$yeih="Yes";
					mysqli_query($myConn,"UPDATE prescriptions SET Purchased = '$yeih' WHERE DrugNam='$prodNamTa' AND ID='$prID' ");

				}
				
				$query="SELECT * FROM cartinfor WHERE NatID='$cartid' AND Paid ='$nuh'";
				
				$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
				$cos= $price;
				if (mysqli_num_rows($result)>0){
					while ($row = mysqli_fetch_array($result))	{
						$cos=$cos + $row['TotCost'];
					} 
				}
				mysqli_free_result($result);
				//Now save the items to dbase
				$nuh="No";
				$emp="Emp";
				$regDat=date("d/m/Y");
				$one="1";
				
				$query="SELECT * FROM cartinfor WHERE ProID='$product' AND NatID='$cartid' AND Paid ='$nuh'";
				$result=mysqli_query($myConn,$query);
				if (mysqli_num_rows($result)>0){
					mysqli_query($myConn,"UPDATE cartinfor SET RegDat = '$regDat',NumTaken = NumTaken + 1,TotCost = TotCost + '$price',FinalAmount = FinalAmount + '$lesPercent'  WHERE ProID='$product' AND NatID='$cartid' AND Paid ='$nuh'");
				}else{
					mysqli_query($myConn,"INSERT INTO cartinfor VALUES('$regDat','$cartid','$cartphon','$cartAlia','$product','$prodNam','$price','$one','$price','$emp','$nuh','$emp','$PharmID','$lesPercent')");
				}
				$query="SELECT * FROM cartinfor WHERE NatID='$cartid' AND Paid ='$nuh'";
				
				$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
				$cos= 0;
				if (mysqli_num_rows($result)>0){
					while ($row = mysqli_fetch_array($result))	{
						$cos=$cos + $row['TotCost'];
					} 
					$_SESSION['cartCost']=$cos;
					$cartCost=$_SESSION['cartCost'];	
				}
				echo("<SCRIPT LANGUAGE='JavaScript'>
					window.location.href='shopping.php'
					</SCRIPT>");
				exit(); 
			}
			
		}elseif($actio=="Vie"){
			$VieID=$product;
			//echo "<script>$('#myModPin').modal('show')</script>";
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
					
				//Alias validator
				if(document.myForm.txtAlias.value==""){
					window.alert('Submission failed. Fill in your missing full name.'); 
					return false;
				}
				if (document.myForm.txtAlias.value.match(alphaspaces)){}
				else{
					window.alert('Submission failed. Your name must contain letters only.'); 
					return false;
				}
				//National ID validator
				if(document.myForm.txtID.value==""){
					window.alert('Submission failed. Fill in your missing national id number.'); 
					return false;
					
				}
				if (document.myForm.txtID.value.match(IDalphanumeric)){}
				else{
					window.alert('National ID Number must contain numbers and letters only. Registration failed'); 
				    return false;
				}
				if (document.myForm.txtID.value.length!==12 && document.myForm.txtID.value.length!==13){
						window.alert('Provided National ID length is invalid. Registration failed.'); 
						return false;
					}
					if (document.myForm.txtID.value.indexOf('-')!==2){
						window.alert('Registration failed. National ID Number must contain a hyphen(-) on the third position.'); 
						return false;
					}
					if (isNaN(document.myForm.txtID.value.substr(0-2))){
						window.alert('Registration failed. National ID Number first two characters must be numeric.'); 
						return false;
					}
					//For 12 national id	
					if (document.myForm.txtID.value.length==12){
						if (isNaN(document.myForm.txtID.value.substr(3,6))){
							window.alert('Registration failed. National ID Number middle six characters must be numeric.'); 
							return false;
						}
						if (isNaN(document.myForm.txtID.value.substr(10,2))){
							window.alert('Registration failed. National ID Number last two characters must be numeric.'); 
							return false;
						}
						if (isNaN(document.myForm.txtID.value.substr(9,1))){}
							else{
								window.alert('Registration failed. National ID Number check letter must be present on the tenth position.'); 
								return false;
						}
					}
					
					//For 13 national id	
					if (document.myForm.txtID.value.length==13){
						if (isNaN(document.myForm.txtID.value.substr(3,7))){
							window.alert('Registration failed. National ID Number middle six characters must be numeric.'); 
							return false;
						}
						if (isNaN(document.myForm.txtID.value.substr(11,2))){
							window.alert('Registration failed. National ID Number last two characters must be numeric.'); 
							return false;
						}
						if (isNaN(document.myForm.txtID.value.substr(10,1))){}
							else{
								window.alert('Registration failed. National ID Number check letter must be present on the tenth position.'); 
								return false;
						}
					}				
				//Phone validator
				if(document.myForm.txtPhone.value==""){
					window.alert('Submission failed. Fill in your missing cellphone number.'); 
					return false;
				}
				if (document.myForm.txtPhone.value.length!==10){
					window.alert('Submision failed. Phone length is invalid.'); 
					return false;
				}
						var str = document.myForm.txtPhone.value;
						var n=str.startsWith("07");
				if (n==false){
					window.alert('Submision failed. Zimbabwean Phone numbers start with 07.'); 
					return false;
				}  
			//code below now send the form after validation is complete
			document.getElementsByClassName("subby")[0].click();
			}
			
			function myfunc3(){
				//Alias validator
				if(document.myForm.txtDetails.value==""){
					window.alert('Submission failed. Fill in the query details first.'); 
					return false;
				}
			//code below now send the form after validation is complete
			document.getElementsByClassName("subby")[0].click();
			}
			
			function viewfunc(){
			//code below now send the form after validation is complete
			document.getElementsByClassName("vwPro")[0].click();
			}
		</SCRIPT>
</head>
    <body >
		<div id="my_modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Single Product View</h4>
				</div>
				<form method="GET" action="shopping.php" autocomplete="off" name="myForm">
					<div class="modal-body">
						<input type="text" name="bookId" id="bookId" ><br>
						<br>
								<?php 
								$prodID="1308";
								$row=mysqli_fetch_array(mysqli_query($myConn,"SELECT * from products WHERE ID='$prodID'"));
								$phd=$row['PharmID'];
								$rowPhar=mysqli_fetch_array(mysqli_query($myConn,"SELECT * from pharmacy WHERE SystID='$phd'"));

								?>
								<label style="color: #535ba0;font-weight:bold;" for="jumb">Product Code:<?php echo $row['ID'];?></label>
                                <img src="Products\<?php echo $row['ImgPath'];?>" style="width:250px;height:225px;"/>
                                <hr />
                                <h2 style="color:#424141;font-weight:bold;">$<?php echo $row['Cost'];?></h2>
                                <label style="color:#615c5c;font-weight:bold;">Drug Name: <?php echo $row['Alias'];?></label><br>
								<label style="color:#615c5c;">SI Unit: <?php echo $row['SIUVal']." ".$row['SiUnit'];?></label><br>
								<br>
								<label style="color:#615c5c;font-weight:bold;">Pharmacy Name: <?php echo $row['PharmNam'];?></label><br>
								<label style="color:#615c5c;">Pharmacy Location: <?php echo $row['Location'];?></label>
								<img src="Products\<?php echo $rowPhar['LocID'];?>" style="width:100%;height:225px;"/>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!--Modal Registration-->
	<div id="myModPin" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				    <img src="images\log.png" style="width:100px;height:100px;"/>
					<h4 class="modal-title">Customer Information Registration</h4>
				</div>
				<form method="POST" action="shopping.php" autocomplete="off" name="myForm">
					<div class="modal-body">
						<input type="text" class="form-control" value="<?php echo $VieID; ?>" name="txtAlias" placeholder="Type your name in full here" style="text-transform: capitalize;"><br>
						<input type="text" class="form-control" name="txtID" placeholder="Type your national id here" style='text-transform:uppercase;'><br>
						<input type="text" class="form-control" name="txtPhone" placeholder="Type your cell phone number here"><br>
					</div>
					<div class="modal-footer">
						<button name="btnSend" class="btn btn-default" type="button" onclick="myfunc()">Submit</button>
						<input type="submit" name="btnSend" class="subby" hidden>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!--Modal Prescription-->
	<div id="myModPres" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				    <img src="images\log.png" style="width:100px;height:100px;"/>
					<h4 class="modal-title">Prescription Products Search</h4>
				</div>
				<form method="POST" action="shopping.php" autocomplete="off" name="myForm">
					<div class="modal-body">
						<input type="number" class="form-control" name="txtPresID" placeholder="Type in the prescription ID number here..."><br>
						<br>
						<input type="number" class="form-control" name="txtPresKey" placeholder="Type in the prescription Key number here..."><br>
					</div>
					<div class="modal-footer">
						<button name="btnSendPrID" class="btn btn-default" type="submit">Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
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
    <div class="container" style="background-color:rgba(0,0,0,0.8);padding: 0px 0px;width:100%;">
		<div class="col-md-3">
			<form method="POST" action="shopping.php" autocomplete="off">
				<div class="col-sm-12"> 
<br>				
					<div class="input-group">
					<input type="text" style="background-color:white;color:black;width:534px;" name="txtSearchKey" class="form-control" placeholder="Type your drug name">
							<span class="input-group-btn btn-success">
								<button name="btnSearchProduct" type="submit" style="text-align:center;width:196px;" class="btn btn-default" type="button">FIND THE LOWEST PRICE<span></button>
							</span>
					</div><!-- /input-group -->
					<br>
				</div>  
				
				<div class="col-md-12">
				<br>
					<div>
						<a href="mycart.php"style="background-color: #535ba0;border:#535ba0;"class="btn btn-success  cart-btn">View My Cart<span class='glyphicon glyphicon-shopping-cart'></a>
					</div>

					<div >
						<a a href="#myModPin" data-toggle="modal"style="background-color: #535ba0;border:#535ba0;" class="btn btn-success cart-btn">Edit Customer Cart Information<span class='glyphicon glyphicon-user'></a>
					</div>
					
						<div>
							<button type="submit" name="remove"style="background-color: #535ba0;border:#535ba0;" class="btn btn-success cart-btn">Delete Unpaid Cart Drugs<span class='glyphicon glyphicon-th-list'></button>
						</div>
				  
						<div>
							<button href="#myModPres" data-toggle="modal" type="button" name="btnUsePre"style="background-color: #535ba0;border:#535ba0;" class="btn btn-success cart-btn">Use Doctor Prescription<span class='glyphicon glyphicon-envelope'></button>
						</div>
				  
					<table class="table  table-hover table-condensed" style="border-radius: 5px;background-color:white;">
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
							<th><label style="color:green;">Total Cost</label></th>
							<td><label style="color:green;"><?php echo $cartCost; ?></label></td>
						</tr>
					</table>
					<div class="clearfix"></div>

					<h4 style="font-weight:600;margin-bottom:20px;">Contact Customer Care</h4>

					<div class="form-group">
						<h4 style="font-weight:600;margin-bottom:20px;">  <label for="txtDetails">For enquiries call our toll free number at 01014</label></h4>
					</div>
				
					
					<div>
						<button type="submit" name="lgout" style="background-color: #535ba0;border:#535ba0;" class="btn btn-success cart-btn">Log Out Of User</button>
					</div>
               
				</div>
			</form>
		</div>
        <div class="col-md-9">  
            <br><br>
                <div class="col-sm-12">
					<form method="POST" action="shopping.php" autocomplete="off">  
					<button type="submit" name="frequently_searched_drugs" style="background-color: #535ba0;width:190px;border:#535ba0;margin-top:40px;" class="<?php echo $btnOne;?>">Frequently Searched Drugs</button>
						<button type="submit" name="bottled" style="background-color:#535ba0;width:130px;border:#535ba0;margin-top:40px;" class="<?php echo $btnTwo;?>">Bottled</button>
						<button type="submit" name="tablets" style="background-color: #535ba0;width:130px;border:#535ba0;margin-top:40px;" class="<?php echo $btnThree;?>">Tablets</button>
						<button type="submit" name="capsules" style="background-color: #535ba0;width:130px;border:#535ba0;margin-top:40px;" class="<?php echo $btnFour;?>">Capsules</button>
						<button type="submit" name="prescription_only" style="background-color: #535ba0;width:150px;border:#535ba0;margin-top:40px;" class="<?php echo $btnFive;?>">prescription only</button>
						<button type="submit" name="presc" style="background-color: #535ba0;width:160px;border:#535ba0;margin-top:40px;" class="<?php echo $btnSix;?>">Prescription Based</button>
					</form>
                </div>
					<?php
					
					$result=mysqli_query($myConn,$sqlView);
						if (mysqli_num_rows($result)>0){
							$rownum=1;
						while ($row = mysqli_fetch_array($result))	
						{
						if (($rownum -1) %4==0){
							echo '<div class="row">';
							echo '<div class="col-sm-12">';
						}
						
					?>
					    <div class="col-md-3">
                            <div class="thumbnail">
                                
								<label style="color: #535ba0;font-weight:bold;" for="jumb">Product Code:<?php echo $row['ID'];?></label>
                                <img src="Products\<?php echo $row['ImgPath'];?>" style="width:250px;height:225px;"/>
                                <hr />
                                <h2 style="color:#424141;font-weight:bold;">$<?php echo $row['Cost'];?></h2>
                                <label style="color:#615c5c;font-weight:bold;">Drug Name: <?php echo $row['Alias'];?></label><br>
								<label style="color:#615c5c;">SI Unit: <?php echo $row['SIUVal']." ".$row['SiUnit'];?></label><br>
								<label style="color:#615c5c;font-weight:bold;">Pharmacy Name: <?php echo $row['PharmNam'];?></label><br>
								<label style="color:#615c5c;">Pharmacy Location: <?php echo $row['Location'];?></label><br>
                                <br>
								<a href='shopping.php?id="Add"<?php echo $row['ID'];?>' title='Add To Cart'><span class='glyphicon glyphicon-shopping-cart'></span></a>
								<a href='viewprod.php?id=<?php echo $row['ID'];?>' class="pull-right">View More Details</a>							
						</div>
						</div>
                    <?php
							$rownum=$rownum + 1;
							if (($rownum -1) %4==0){
								echo "</div>";
								echo "</div>";
							}
						}			   
					}
					mysqli_free_result($result);
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
			$(document).ready(function() {

			  $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {

				var data_id = '';

				if (typeof $(this).data('id') !== 'undefined') {

				  data_id = $(this).data('id');
				}

				$('#bookId').val(data_id);
			  })
			});
    </script>
        <!-- //Calendar -->
</body>	
	
</html>	
	