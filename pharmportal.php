<?php
	session_start();
	$systID= $_SESSION['SID'];
	$ec=$_SESSION['EC'];
	$alias=$_SESSION['Alias'];
	$addr=$_SESSION['Addr'];
	$varProImg='drimg.jpg';
	$prosql = "SELECT * FROM products WHERE PharmID='$systID' ORDER BY InStk ASC";
	$tblHeader="All Drugs In Stock";
	$nuh="Yes";
	$sqlquer = "SELECT * FROM cartinfor WHERE Paid='$nuh' AND PharmID='$systID'";
	//If page accessed without log in
	if(empty($_SESSION['EC'])){
		header("location: home.php");//redirects to another page
	}
	
	if (isset($_POST['btnLgout'])){
		
		session_destroy();
		unset ($_SESSION['SID']);
		unset ($_SESSION['EC']);
		unset ($_SESSION['Alias']);
		unset ($_SESSION['Addr']);
		header("location: home.php");
	
	}
	include('dbconnect.php');

	
	//find the PRESCRIPTION id
	$query="select * from increments";		
	$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result))	
			{
			$did=$row['ProductID'];
			}
		}
	mysqli_free_result($result);
	
	$pri="";
	$dat=date("d/m/Y");
	$cat="";
	$dsi="";
	$siv="";
	$nam="";
	$num="";
	$pre="";
	
	$btnSave="enabled";
	$btnUpdat="disabled";
	$btnDelet="disabled";
	//Drug Button Search
	if (isset($_POST['btnSeaDrug'])){
		if (empty($_POST['txtSeaDrug'])){
				echo('<SCRIPT>window.alert("Product search failed because you did not provide any drug search name.");
				window.location.href="pharmportal.php";
				</SCRIPT>');
		    exit();
		}
		$ker=$_POST['txtSeaDrug'].'%';
		$prosql="SELECT * FROM products WHERE Alias Like '$ker' AND PharmID='$systID' ORDER BY Cost ASC";
		$tblHeader="Searched Drugs In Stock";
	}
	
	// Button Eye Search
	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
	    $sentence=$_GET["id"];
		$secVal=strlen($sentence)-3;
		$product=substr($sentence,3,$secVal);
		$actio=substr($sentence,0,3);
		
		if ($actio="vie"){
	        $query="SELECT * FROM products WHERE ID='$product'";
				
			$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_array($result))	{
					$did=$product;
					$pri=$row['Cost'];
					$dat=$row['RegDat'];
					$cat=$row['Category'];
					$dsi=$row['SiUnit'];
					$siv=$row['SIUVal'];
					$nam=$row['Alias'];
					$num=$row['InStk'];
					$pre=$row['NeedPresc'];
					$varProImg=$row['ImgPath'];
					
					$btnSave="disabled";
					$btnUpdat="enabled";
					$btnDelet="enabled";
					
					$prosql = "SELECT * FROM products WHERE ID='$product' AND PharmID='$systID'";
		        }
			}
			mysqli_free_result($result);
		}
	}
	
	//Add Drug To Products
	if (isset($_POST['btnSave'])){
		
		$DrCateg=$_POST['cmbCat'];
		$DrPrice=$_POST['txtPri'];
		$DrRegda=date("d/m/Y");
		$DrID=$_POST['txtDID'];
		$DrAlias=$_POST['txtNam'];
		$DrSIUni=$_POST['cmbDSI'];
		$DrSival=$_POST['txtSIV'];
		$DrStkno=$_POST['txtNum'];
		$DrPresc=$_POST['cmbPre'];
		//$ImgPath=$_POST['txtNum'];
		
		//Image Copy
		 //$name=$_FILES['file']['name'];
		 $ext = end(explode(".", $_FILES["file"]["name"]));
		 $name=$DrID.".".$ext;
		
		 $size=$_FILES['file']['size'];
		 $type=$_FILES['file']['type'];
		 $tmp_name=$_FILES['file']['tmp_name'];
		 $error=$_FILES['file']['error'];
		 $nopic="";
		if(isset($name) AND !empty($ext)  AND !empty($name)){
				$location = 'Products/';
				$allowedExts = array("jpg", "JPG", "jpeg", "JPEG","png","PNG"); 
				$extension = end(explode(".", $_FILES["file"]["name"]));
					if (!in_array($extension, $allowedExts))
					{  
					$nopic="sure";
					$name="blank.jpg";
					}else{
						move_uploaded_file($tmp_name, $location.$name);
					}
		}else{
			$name="blank.jpg";
		}
		
		$nuhho="No";
		mysqli_query($myConn,"INSERT INTO products VALUES ('$DrRegda','$DrID','$DrCateg','$DrAlias','$DrSIUni','$DrSival','$DrPrice','$name','$DrStkno','$systID','$alias','$addr','$nuhho','$DrPresc')");
		mysqli_query($myConn,"UPDATE increments SET ProductID=ProductID + 1");
		if ($nopic==""){
			echo('<SCRIPT>window.alert("Product saving is successfully done.");
			window.location.href="pharmportal.php";
			</SCRIPT>');
		    exit();
		}else{
			echo('<SCRIPT>window.alert("Product saving is successfully done but without an image because of wrong format used.");
			window.location.href="pharmportal.php";
			</SCRIPT>');
		    exit();
		}
		
	}
	
	//Update Drug To Products
	if (isset($_POST['btnUpdate'])){
		
		$DrCateg=$_POST['cmbCat'];
		$DrPrice=$_POST['txtPri'];
		$DrID=$_POST['txtDID'];
		$DrAlias=$_POST['txtNam'];
		$DrSIUni=$_POST['cmbDSI'];
		$DrSival=$_POST['txtSIV'];
		$DrStkno=$_POST['txtNum'];
		$DrPresc=$_POST['txtNum'];
		//$ImgPath=$_POST['txtNum'];
		
		//Image Copy
		 $ext = end(explode(".", $_FILES["file"]["name"]));
		 $name=$DrID.".".$ext;
		
		//$size=$_FILES['file']['size'];
		//$type=$_FILES['file']['type'];
		$tmp_name=$_FILES['file']['tmp_name'];
		$error=$_FILES['file']['error'];
		$updSql="UPDATE products SET Category='$DrCateg',Alias='$DrAlias',SiUnit='$DrSIUni',SIUVal='$DrSival',Cost='$DrPrice',InStk=,'$DrStkno',NeedPresc=,'$DrPresc' WHERE ID='$DrID'";
		
		if(isset($name) AND !empty($ext)  AND !empty($name)){
				$location = 'Products/';
				$allowedExts = array("jpg", "JPG", "jpeg", "JPEG","png","PNG"); 
				$extension = end(explode(".", $_FILES["file"]["name"]));
					if (!in_array($extension, $allowedExts))
					{  
					}else{
						move_uploaded_file($tmp_name, $location.$name);
						$updSql="UPDATE products SET Category='$DrCateg',Alias='$DrAlias',SiUnit='$DrSIUni',SIUVal='$DrSival',Cost='$DrPrice',ImgPath='$name',InStk=,'$DrStkno',NeedPresc=,'$DrPresc' WHERE ID='$DrID'";
					}
		}
		mysqli_query($myConn,$updSql);
		echo('<SCRIPT>window.alert("Product updating is successfully done.");
			window.location.href="pharmportal.php";
			</SCRIPT>');
		exit();
	}
	
	//DELETE Drug To Products
	if (isset($_POST['btnDelete'])){
		
		$DrID=$_POST['txtDID'];

		$delSql="DELETE FROM products WHERE ID='$DrID'";
		 
		mysqli_query($myConn,$delSql);
		
		echo('<SCRIPT>window.alert("Product deletion is successfully done.");
			window.location.href="pharmportal.php";
			</SCRIPT>');
		exit();
	}
	
	//Doctore details sign up
	if (isset($_POST['btnDrSend'])){
			//Variables Collection
			$alias=ucfirst($_POST['txtPhName']);
			$phone=$_POST['txtPhPhone'];
			$email=$_POST['txtPhEmail'];
			$company=$_POST['txtPhCompany'];
			$ec=strtoupper($_POST['txtPhRegID']);
			$addr=$_POST['txtPhAddr'];
			$usrnm=$_POST['txtPhUsrnm'];
			$pswd=$_POST['txtPhCnPswd'];
			

		//now save the record
		mysqli_query($myConn,"UPDATE pharmacy SET FullName='$alias',Company='$company',ID='$ec',Phone='$phone',Email='$email',BuisAddress='$addr',Username='$usrnm',Password='$pswd' WHERE SystID='$systid' ");
		
		$_SESSION['EC']=$ec;
		$_SESSION['Alias']=$alias;
		$_SESSION['Addr']=$addr;
	
		echo('<SCRIPT>window.alert("Pharmacy details updating is successfully done.");
			window.location.href="pharmportal.php";
			</SCRIPT>');
		exit();	
	}	
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Pharmacy Portal</title>
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
            background: url("images/bck.jpeg");
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
			
			function myfuncSav(){
					//Validation Regex
					var IDalphanumeric=/^[-0-9a-zA-Z]+$/;
					var ricc= /^[0-9]+$/ ;
					var alphaspaces=/^[ a-zA-Z]+$/;
					var phon=/^\d{10}$/;
					var whitespaces=/[^a-z|^A-Z|^\s]/;
					var letters=/^[a-zA-Z]+$/;
					
				//Drug Category Validator
				if(document.frmPresc.cmbCat.value==""){
					window.alert('Submission failed. Select the missing drug category.'); 
					return false;
				}
				//Drug Price Validator
				if(document.frmPresc.txtPri.value==""){
					window.alert('Submission failed. Fill in the missing drug price.'); 
					return false;
				}
				//Drug Need Prescription Validator
				if(document.frmPresc.cmbPre.value==""){
					window.alert('Submission failed. Select if the drug need prescription or not.'); 
					return false;
				}
				//Drug ID Validator
				if(document.frmPresc.txtDID.value==""){
					window.alert('Submission failed. Fill in the missing drug ID.'); 
					return false;
				}
				
				//Drug Name Validator
				if(document.frmPresc.txtNam.value==""){
					window.alert('Submission failed. Fill in the missing drug name.'); 
					return false;
				}
				//Drug SIU Validator
				if(document.frmPresc.cmbDSI.value==""){
					window.alert('Submission failed. Select the missing drug SIUnit.'); 
					return false;
				}
				//Drug SIUV Validator
				if(document.frmPresc.txtSIV.value==""){
					window.alert('Submission failed. Fill in the missing SI Unit value.'); 
					return false;
				}
				//Drug ID Validator
				if(document.frmPresc.txtNum.value==""){
					window.alert('Submission failed. Fill in the missing drug number in stock.'); 
					return false;
				}
				
			//code below now send the form after validation is complete
			document.getElementsByClassName("savesubby")[0].click();
			}
			
			//UPDATE BUTTON JAVASCRIPT
			function myfuncUpd(){
					//Validation Regex
					var IDalphanumeric=/^[-0-9a-zA-Z]+$/;
					var ricc= /^[0-9]+$/ ;
					var alphaspaces=/^[ a-zA-Z]+$/;
					var phon=/^\d{10}$/;
					var whitespaces=/[^a-z|^A-Z|^\s]/;
					var letters=/^[a-zA-Z]+$/;
					
				//Drug Category Validator
				if(document.frmPresc.cmbCat.value==""){
					window.alert('Updating failed. Select the missing drug category.'); 
					return false;
				}
				//Drug Price Validator
				if(document.frmPresc.txtPri.value==""){
					window.alert('Updating failed. Fill in the missing drug price.'); 
					return false;
				}
				//Drug Need Prescription Validator
				if(document.frmPresc.cmbPre.value==""){
					window.alert('Submission failed. Select if the drug need prescription or not.'); 
					return false;
				}
				//Drug ID Validator
				if(document.frmPresc.txtDID.value==""){
					window.alert('Updating failed. Fill in the missing drug ID.'); 
					return false;
				}
				
				//Drug Name Validator
				if(document.frmPresc.txtNam.value==""){
					window.alert('Updating failed. Fill in the missing drug name.'); 
					return false;
				}
				//Drug SIU Validator
				if(document.frmPresc.cmbDSI.value==""){
					window.alert('Updating failed. Select the missing drug SIUnit.'); 
					return false;
				}
				//Drug SIUV Validator
				if(document.frmPresc.txtSIV.value==""){
					window.alert('Updating failed. Fill in the missing SI Unit value.'); 
					return false;
				}
				//Drug ID Validator
				if(document.frmPresc.txtNum.value==""){
					window.alert('Updating failed. Fill in the missing drug number in stock.'); 
					return false;
				}
				
			//code below now send the form after validation is complete
			document.getElementsByClassName("updatsubby")[0].click();
			}
			
			function myfuncDr(){
					//Validation Regex
					var IDalphanumeric=/^[-0-9a-zA-Z]+$/;
					var ricc= /^[0-9]+$/ ;
					var alphaspaces=/^[ a-zA-Z]+$/;
					var phon=/^\d{10}$/;
					var whitespaces=/[^a-z|^A-Z|^\s]/;
					var letters=/^[a-zA-Z]+$/;
					var email=document.getElementById('txtPhEmail');
					var filter =/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				//Alias validator
				if(document.frmDrSign.txtPhName.value==""){
					window.alert('Submission failed. Fill in your missing pharmacy name.'); 
					return false;
				}
				
				//Phone validator
				if(document.frmDrSign.txtPhPhone.value==""){
					window.alert('Submission failed. Fill in your missing phone number.'); 
					return false;
				}
		
				//Email Validator
				if(document.frmDrSign.txtPhEmail.value==""){
					window.alert('Submission failed. Fill in your missing email address.'); 
					return false;
				}
				if (!filter.test(email.value) && document.frmDrSign.txtPhEmail.value!=""){
					window.alert('Submision failed. The Email you provided is invalid.'); 
					return false;
				}
				
				//Company Validator
				if(document.frmDrSign.txtPhCompany.value==""){
					window.alert('Submission failed. Fill in your missing company name.'); 
					return false;
				}
				
				if(document.frmDrSign.txtPhRegID.value==""){
					window.alert('Submission failed. Fill in your missing company reg number.'); 
					return false;
				}
				
				//Address Validator
				if(document.frmDrSign.txtPhAddr.value==""){
					window.alert('Submission failed. Fill in your missing business address.'); 
					return false;
				}
				//Username Validator
				if(document.frmDrSign.txtPhUsrnm.value==""){
					window.alert('Submission failed. Fill in your missing portal login username.'); 
					return false;
				}
				//Password Validator
				if(document.frmDrSign.txtPhPswd.value==""){
					window.alert('Submission failed. Fill in your missing portal login password.'); 
					return false;
				}
				if(document.frmDrSign.txtPhCnPswd.value==""){
					window.alert('Submission failed. Fill in your missing portal login confirm password.'); 
					return false;
				}
				//Confirm Password validator
				if(document.frmDrSign.txtPhPswd.value!==document.frmDrSign.txtPhCnPswd.value){
					window.alert('Submision failed. Password is not correctly confirmed.'); 
					return false;
				}
			//code below now send the form after validation is complete
			document.getElementsByClassName("drsubby")[0].click();
			}
		</SCRIPT>
	</head>
    <body>

	<!-- Dr Sign Up Modal HTML -->
	<div id="drSign" class="modal fade">
		<div class="modal-dialog" style="margin-top:0px;">
			<div class="modal-content">
				<form method="POST" action="pharmportal.php" autocomplete="off" name="frmDrSign">
					<div class="modal-header">
						<img src="images\log.png" style="-moz-border-radius:15px;-webkit-border-radius:15px;height:50px;"/><label style="font-size:20pt;">Pharmacy Details Edditing</label>
					</div>
					<div class="container">
						    <?php
								//See if its one or more
								$sql="SELECT * FROM pharmacy WHERE SystID='$systID'";
								$result=mysqli_query($myConn,$sql);
								$row=mysqli_fetch_assoc($result);
								
								$one=$row["FullName"];
								$two=$row["Phone"];
								$three=$row["Email"];
								$four=$row["Company"];
								$five=$row["ID"];
								$six=$row["BuisAddress"];
								$seven=$row["Username"];
								$eight=$row["Password"];	
							?>
							<div class="col-lg-6">
								<label style="font-size:10pt;color:#4ddbff;font-weight:bold;">Fill all the required details and click submit<label><br>
								<label style="color:black;">Pharmacy Full Name</label>
								<input type="text" value="<?php echo $one;?>" name="txtPhName" style="background-color:white;text-transform:capitalize;"></input>
								<label style="color:black;">Contact Person Phone or Mobile</label>
								<input type="text" value="<?php echo $two;?>" name="txtPhPhone" style="background-color:white;"></input>
								<label style="color:black;">Contact Person Email</label>
								<input type="text" value="<?php echo $three;?>" name="txtPhEmail" id="txtPhEmail" style="background-color:white;"></input>
								<label style="color:black;">Pharmacy Company Name</label>
								<input type="text" value="<?php echo $four;?>" name="txtPhCompany" style="background-color:white;"></input>
								<label style="color:black;">Pharmacy Company Registration ID</label>
								<input type="text" value="<?php echo $five;?>" name="txtPhRegID" style="background-color:white;"></input>
								<label style="color:black;">Business Address</label>
								<input type="text" value="<?php echo $six;?>" name="txtPhAddr" style="background-color:white;"></input>
								<label style="color:black;">Login Username</label>
								<input type="text" value="<?php echo $seven;?>" name="txtPhUsrnm" style="background-color:white;"></input>
								<label style="color:black;">Login Password</label>
								<input type="password" value="<?php echo $eight;?>" class="form-control" name="txtPhPswd" style="background-color:white;"></input>
								<label style="color:black;">Confirm Login Password</label>
								<input type="password" value="<?php echo $eight;?>" class="form-control" name="txtPhCnPswd" style="background-color:white;"></input>
							</div>
							<input style="left:28%;" class="drsubby" type="submit" name="btnDrSend" hidden>
					</div>
					<br>
					<div class="modal-footer" style="background-color:blue;" >
						<button class="btn btn-default btn-primary" type="button" style="width:35%;" onclick="myfuncDr()">Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="drugSearch" class="modal fade">
		<div class="modal-dialog" style="margin-top:30px;">
			<div class="modal-content">
				<div class="modal-header">
					<img src="images\log.png" style="-moz-border-radius:15px;-webkit-border-radius:15px;height:50px;"/><label style="font-size:20pt;">Drug Search</label>
				</div>
				<form method="POST" action="pharmportal.php" autocomplete="off" name="frmDruSea">
				<div class="modal-body">
					<input type="text" class="form-control" name="txtSeaDrug" placeholder="Type in the drug name here"><br>
				</div>
				<div class="modal-footer" style="background-color:blue;" >
					<button name="btnSeaDrug" class="btn btn-default" type="submit">Search Drug</button>
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
					<td><label><?php echo$alias; ?></label></td>
				</tr>
				<tr >
					<th><label style="color:white;">Business Address</label></th>
					<td><label><?php echo$addr; ?></label></td> 
				</tr>
			</table>
		</div>
		
		<div class="col-md-3">

				<div class="btn-group" role="group">
					<button type="button" style="width:200px;left:100px;" class="btn btn-default dropdown-toggle pull-right" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Pharmacy
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
						<li>
							<form method="POST" action="pharmportal.php" autocomplete="off">
								<button class="btn btn-default" type="submit" name="btnLgout" >Logout</button>
							</form>
						</li>
						<br>
						<li><button class="btn btn-default" href="#drSign" data-toggle="modal">Edit Profile</button></li>
					</ul>
				</div>
		</div>
    </div>  
	
	<div class="container" style="background-color:rgba(0,0,0,0.7);padding:10px 0px;width:100%;margin-top:10px;">
		<form method="POST" action="pharmportal.php" autocomplete="off" name="frmPresc" enctype="multipart/form-data">
			<div class="col-md-3">
				<img src="Products\<?php echo $varProImg;?>" id="profile-img-tag" style="height:40%;width:360px;-moz-border-radius:15px;-webkit-border-radius:15px;"/>
			</div>
		
			<div class="col-md-3">
				<label style="font-size:12pt;color:#4ddbff;font-weight:bold;">Drug Details--------------------------------------<label><br><br>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Drug Category<label style="color:white;">.....</label></span>
							<select name="cmbCat" class="form-control" style="background:white;color:black;">
								<option value="<?php echo $cat;?>"><?php echo $cat;?></option>
								<option value="Bottled">Bottled</option>
								<option value="Capsules">Capsules</option>
								<option value="Pills">Pills</option>
								<option value="Other">Other</option>
							</select><br>					
					</div>
				</div>

				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Drug Price<label style="color:white;">.............</label></span>
						<input name="txtPri" value='<?php echo $pri;?>' type="number" class="form-control" style="background-color:white;">						
					</div>
				</div>
				
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Need Prescription</span>
							<select name="cmbPre" class="form-control" style="background:white;color:black;">
								<option value="<?php echo $pre;?>"><?php echo $pre;?></option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select><br>					
					</div>
				</div>
				
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Drug ID<label style="color:white;">..................</label></span>
						<input name="txtDID" maxlength="50" value='<?php echo $did;?>' type="text" class="form-control" style="background-color:white;">						
					</div>
				</div>
			</div>
			
			<div class="col-md-3">
			    <div class="form-group">
					<label style="color:white;font-size:9pt;" for="jumb">Drug Product Image File(.jpg and .png files only)</label>
					<input type="file" style="color:white;font-size:8pt;" name="file" id="profile-img">
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Drug Name<label style="color:white;">............</label></span>
						<input name="txtNam" maxlength="50" value='<?php echo $nam;?>' type="text" class="form-control" style="background-color:white;">						
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Drug SIUnit<label style="color:white;">............</label></span>
							<select name="cmbDSI" class="form-control" style="background:white;color:black;">
								<option value="<?php echo $dsi;?>"><?php echo $dsi;?></option>
								<option value="Kgs">Kgs</option>
								<option value="Grams">Grams</option>
								<option value="Mills">Mills</option>
								<option value="Tablets">Tablets</option>
								<option value="Capsules">Capsules</option>
								<option value="Packet">Packet</option>
								<option value="Satchet">Satchet</option>
							</select><br>					
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Drug SIUnit Value</span>
						<input name="txtSIV" maxlength="50" value='<?php echo $siv;?>' type="number" class="form-control" style="background-color:white;">						
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Stock Number<label style="color:white;">.......</label></span>
						<input name="txtNum" value='<?php echo $num;?>' type="number" class="form-control" style="background-color:white;">						
					</div>
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group">
					<button type="button" <?php echo $btnSave; ?> style="width:200px;margin-top:15px;" class="btn btn-default pull-right" onclick="myfuncSav()">Save Drug Product</button>
					<input style="left:28%;" class="savesubby" type="submit" name="btnSave" hidden>
				</div>
				<div class="form-group">
					<button type="button" style="width:200px;margin-top:15px;"  class="btn btn-default pull-right" href="#drugSearch" data-toggle="modal">Search Drug</button>
				</div>
				<div class="form-group">
					<button type="button" <?php echo $btnUpdat; ?> style="width:200px;margin-top:15px;" name="btnUpdat" class="btn btn-default pull-right" onclick="myfuncUpd()">Update Drug Details</button>
					<input style="left:28%;" class="updatsubby" type="submit" name="btnUpdate" hidden>
				</div>
				<div class="form-group">
					<button type="submit" <?php echo $btnDelet; ?> style="width:200px;margin-top:15px;" name="btnDelete" class="btn btn-default pull-right">Delete Drug Details</button>
				</div>
				<div class="form-group">
					<button type="submit" style="width:200px;margin-top:15px;" class="btn btn-default pull-right">Clear Form</button>
				</div>
			</div>
		</form>
    </div> 
	
	<div class="container" style="background-color:rgba(0,0,0,0.7);padding:0px 10px;width:100%;margin-top:10px;">
					<div class="page-header clearfix">
                        <h2 style="font-size:10pt;color:#4ddbff;font-weight:bold;" class="pull-left">All Drugs In Stock</h2>
                    </div>
                    <?php
                    $nuh="No";
                    // Attempt select query execution
                    if($result = mysqli_query($myConn, $prosql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-condensed'>";
                                echo "<thead>";
                                    echo "<tr style='color:white;background-color:#20B2AA;'>";
                                        echo "<th>Date Saved</th>";
										echo "<th>Drug ID</th>";
										echo "<th>Drug Name</th>";
										echo "<th>Drug Category</th>";
                                        echo "<th>Drug SI Unit</th>";
                                        echo "<th>Drug SI Unit Value</th>";
                                        echo "<th>Number In Stock</th>";
										echo "<th>Drug Cost $</th>";
										echo "<th>View</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr style='color:black;background-color:#FDF5E6;'>";
                                        echo "<td>" . $row['RegDat'] . "</td>";
                                        echo "<td>" . $row['ID'] . "</td>";
                                        echo "<td>" . $row['Alias'] . "</td>";
                                        echo "<td>" . $row['Category'] . "</td>";
										
										echo "<td>" . $row['SiUnit'] . "</td>";
                                        echo "<td>" . $row['SIUVal'] . "</td>";
                                        echo "<td>" . $row['InStk'] . "</td>";
                                        echo "<td>" . $row['Cost'] . "</td>";
                                        echo '<td style="text-align:center;">';
                                            echo "<a href='pharmportal.php?id=vie". $row['ID'] ."' title='View Details'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } 
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($myConn);
                    }
 
                    // Close connection
                    //mysqli_close($myConn);
                    ?>
    </div>
	
	<div class="container" style="background-color:rgba(0,0,0,0.7);padding:0px 10px;width:100%;margin-top:10px;">
					<div class="page-header clearfix">
                        <h2 style="font-size:10pt;color:#4ddbff;font-weight:bold;" class="pull-left">All Drugs Purchases</h2>
                    </div>
                    <?php
                   
                    if($result = mysqli_query($myConn,$sqlquer)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-condensed'>";
                                echo "<thead>";
                                    echo "<tr style='color:white;background-color:#20B2AA;'>";
                                        echo "<th>Transection Date</th>";
                                        echo "<th>Drug ID</th>";
                                        echo "<th>Drug Name</th>";
										echo "<th>Drug Cost $</th>";
										echo "<th>Number Bought</th>";
                                        echo "<th>Total Cost</th>";
                                        echo "<th>Receipt Number</th>";
										echo "<th>Total Share $</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr style='color:black;background-color:#FDF5E6;'>";
                                        echo "<td>" . $row['RegDat'] . "</td>";
                                        echo "<td>" . $row['ProID'] . "</td>";
                                        echo "<td>" . $row['Product'] . "</td>";
                                        echo "<td>" . $row['ItemCost'] . "</td>";
                                      
										echo "<td>" . $row['NumTaken'] . "</td>";
                                        echo "<td>" . $row['TotCost'] . "</td>";
                                        echo "<td>" . $row['RecNum'] . "</td>";
                                        echo "<td>" . $row['FinalAmount'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } 
                    } else{
                        echo "ERROR: Could not able to execute $sqlquer. " . mysqli_error($myConn);
                    }
 
                    // Close connection
                    mysqli_close($myConn);
                    ?>
    </div>
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
        <!-- Calendar 

        <script>
            $(function () {
                $("#datepicker,#datepicker1").datepicker();
            });
        </script>-->
		<script type="text/javascript">
			function readURL(input) {
				if (input.files && input.files[0]) {
					var reader = new FileReader();
					
					reader.onload = function (e) {
						$('#profile-img-tag').attr('src', e.target.result);
					}
					reader.readAsDataURL(input.files[0]);
				}
			}
			$("#profile-img").change(function(){
				readURL(this);
			});
		</script>
	</body>	
	
</html>			