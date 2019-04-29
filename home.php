<?php
	#Call Db Connection
	include('dbconnect.php');
	$sqlView="SELECT * FROM products";
	
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
		
	}else if(isset($_POST['tablets'])){
		//tablets
		$sqlView="SELECT * FROM products";
		$sqlView="SELECT * FROM products WHERE Category='tablets'";
		
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
		
	}else if(isset($_POST['prescription_only'])){
		//prescription_only
		$sqlView="SELECT * FROM products";
		$sqlView="SELECT * FROM products WHERE Category='prescription_only'";
		
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
	
	
	if(isset($_POST['btnSend'])){
		session_start();
		$_SESSION['cartID']=strtoupper($_POST['txtID']);
		$_SESSION['cartAlia']=$_POST['txtAlias'];
	    $_SESSION['cartPhone']=$_POST['txtPhone'];
		//Prescription ID
		$_SESSION['PresID']="";
	    $_SESSION['PresPin']="";
		$cartAlia=$_SESSION['cartAlia'];
		$cartid=$_SESSION['cartID'];
		$cartphon= $_SESSION['cartPhone'];
		
		$nuh="No";
		
		$query="SELECT * FROM cartinfor WHERE NatID='$cartid' AND Paid ='$nuh'";
				
			$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			$cos=0.0;
			if (mysqli_num_rows($result)>0){
				
				while ($row = mysqli_fetch_array($result))	{
					//$cos=$cos+ double($row['TotCost']);
					$cos=$cos+ $row['TotCost'];
		        } 
				$_SESSION['cartCost']=$cos;
				$cartCost=$_SESSION['cartCost'];
			}else{
				$_SESSION['cartCost']=$cos;
				$cartCost=$_SESSION['cartCost'];
			}
			mysqli_free_result($result);
			header("location: shopping.php");//redirects to another page
	}	
	
	if (isset($_POST['btnSendLog'])){
	$_SESSION['cartID']=strtoupper($_POST['txtIDLog']);
	$cartid=$_SESSION['cartID'];
	$sql="SELECT * FROM cartinfor WHERE NatID='$cartid'";
    $result=mysqli_query($myConn,$sql);//will execute the query into the database
	$row=mysqli_fetch_assoc($result);

	$alias=$row["Alias"];
	$phone=$row["Phone"];

	if(mysqli_num_rows($result)>0){
		session_start();
		$_SESSION['cartAlia']=$alias;
	    $_SESSION['cartPhone']=$phone;
		//Prescription ID
		$_SESSION['PresID']="";
	    $_SESSION['PresPin']="";
		
		$_SESSION['cartID']=strtoupper($_POST['txtIDLog']);
		$cartAlia=$_SESSION['cartAlia'];
		$cartid=$_SESSION['cartID'];
		$cartphon= $_SESSION['cartPhone'];
		mysqli_free_result($result);
	    
		$nuh="No";
		$query="SELECT * FROM cartinfor WHERE NatID='$cartid' AND Paid ='$nuh'";
				
		$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
		$cos=0.0;
		if (mysqli_num_rows($result)>0){
				
			while ($row = mysqli_fetch_array($result)){
				$cos=$cos + $row['TotCost'];
		        } 
				$_SESSION['cartCost']=$cos;
				$cartCost=$_SESSION['cartCost'];
		}else{
				$_SESSION['cartCost']=$cos;
				$cartCost=$_SESSION['cartCost'];
		}
		mysqli_free_result($result);
		header("location: shopping.php");//redirects to another page	
	    }else if (mysqli_num_rows($result)==0){
		echo("<SCRIPT LANGUAGE='JavaScript'>
		window.alert('You failed to log in correctly try again. Your ID is not found in previous cart information.')
		window.location.href='home.php'
		</SCRIPT>");
		exit();
		}
    }
	//Doctore details sign up
	if (isset($_POST['btnDrSend'])){
			//Variables Collection
			$alias=ucfirst($_POST['txtDrName']);
			$ec=strtoupper($_POST['txtDrRegID']);
			$phone=$_POST['txtDrPhone'];
			$email=$_POST['txtDrEmail'];
			
			if (isset($_POST['txtDrFax'])){
				$fax=$_POST['txtDrFax'];
			}else{
				$fax="No Details";
			}
			
			$addr=$_POST['txtDrAddr'];
			$usrnm=$_POST['txtDrUsrnm'];
			$pswd=$_POST['txtDrPswd'];
			$stt="No";
			
		//Find if Ec was not used already
		$result=mysqli_query($myConn,"SELECT * FROM doctors WHERE ID='$ec'");
		$row=mysqli_fetch_assoc($result);
		$rows=mysqli_num_rows($result);
		if ($rows==1){
			echo("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('The doctor ec number provided is with another doctore, saving failed.')
				window.location.href='home.php'
				</SCRIPT>");
			exit();
		}
		mysqli_free_result($result);
		//find the customer id
		$query="select * from increments";		
		$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_array($result))	
					{
					$systid= $row['SystID'];
					//$ogId="R{$clientid}";
					}
			}
		mysqli_free_result($result);
		//now save the record
		$nextID=$systid + 1;
		$regdat=date("d/m/Y");
		mysqli_query($myConn,"INSERT INTO doctors VALUES ('$systid','$regdat','$alias','$ec','$phone','$email','$fax','$addr','$usrnm','$pswd','$stt')");
		mysqli_query($myConn,"UPDATE increments SET SystID='$nextID'");
		//echo('<SCRIPT>window.alert("Quotation request successfull and your application id is: "+"'.$odid.'");
		echo('<SCRIPT>window.alert("Doctor sign up successfully done.");
			window.location.href="home.php";
			</SCRIPT>');
		exit();	
	}	
	
	//Pharmacy details sign up
	if (isset($_POST['btnPhSend'])){
			//Variables Collection
			$alias=ucfirst($_POST['txtPhName']);
			$phone=$_POST['txtPhPhone'];
			$email=$_POST['txtPhEmail'];
			$comp=$_POST['txtPhCompany'];
			$ec=strtoupper($_POST['txtPhRegID']);
			$addr=$_POST['txtPhAddr'];
			$usrnm=$_POST['txtPhUsrnm'];
			$pswd=$_POST['txtPhPswd'];
			$stt="No";
			
		//find the customer id
		$query="select * from increments";		
		$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_array($result))	
					{
					$systid= $row['PhSystID'];
					$locId="L{$systid}";
					}
			}
		mysqli_free_result($result);
		
		$ext = end(explode(".", $_FILES["file"]["name"]));
		$name=$locId.".".$ext;
		
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
		//now save the record
		$regdat=date("d/m/Y");
		$purchShare="0";
		mysqli_query($myConn,"INSERT INTO pharmacy VALUES ('$systid','$regdat','$alias','$comp','$ec','$phone','$email','$purchShare','$addr','$usrnm','$pswd','$stt','$name')");
		mysqli_query($myConn,"UPDATE increments SET PhSystID=PhSystID + 1");
		//echo('<SCRIPT>window.alert("Quotation request successfull and your application id is: "+"'.$odid.'");
		echo('<SCRIPT>window.alert("Pharmacy sign up successfully done.");
			window.location.href="home.php";
			</SCRIPT>');
		exit();	
	}	
	
	//Doctor Login Code
		if (isset($_POST['btnDrLogin'])){
			//VARIABLE COLLECTION FROM LOGIN FORM
			$id=$_POST["txtDrLgUsrnm"];
			//take pswd
			$pswd=$_POST["txtDrLgPswd"];
			
			$sql="SELECT * FROM doctors WHERE Username='$id' AND Password='$pswd'";
			//Executing the query into the database and store result in result variable
			$result=mysqli_query($myConn,$sql);
			$row=mysqli_fetch_assoc($result);
			
			$systID=$row["SystID"];
			$alias=$row["FullName"];
			$ec=$row["ID"];
			$addr=$row["BuisAddress"];
			$stt=$row["Blocked"];
				
			If (mysqli_num_rows($result)==1){ //Record is found so login is correct.Session is started
				If ($stt=="Yes"){
					echo("<SCRIPT LANGUAGE='JavaScript'>
						window.alert('Your account is currently blocked. Please contact system admin for assistance.')
						window.location.href='home.php'
						</SCRIPT>");
					exit(); 
				}
				
				session_start();
				$_SESSION['SID'] = $systID;
				$_SESSION['EC'] = $ec;
				$_SESSION['Alias'] = $alias;
				$_SESSION['Addr'] = $addr;
				$_SESSION['PreID'] ="";
				$_SESSION['PrePin'] = "";
				header("location:drportal.php");//redirects to another page
			}else If (mysqli_num_rows($result)==0){
				 echo("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('Login details are incorrect .Try again')
				window.location.href='home.php'
				</SCRIPT>");
				exit(); 
			}
		}
		
		//Pharmacy Login Code
		if (isset($_POST['btnPhLogin'])){
			//VARIABLE COLLECTION FROM LOGIN FORM
			$id=$_POST["txtPhLgUsrnm"];
			//take pswd
			$pswd=$_POST["txtPhLgPswd"];
			
			$sql="SELECT * FROM pharmacy WHERE Username='$id' AND Password='$pswd'";
			//Executing the query into the database and store result in result variable
			$result=mysqli_query($myConn,$sql);
			$row=mysqli_fetch_assoc($result);
			
			$systID=$row["SystID"];
			$alias=$row["FullName"];
			$ec=$row["ID"];
			$addr=$row["BuisAddress"];
			$stt=$row["Blocked"];
				
			If (mysqli_num_rows($result)==1){ //Record is found so login is correct.Session is started
				If ($stt=="Yes"){
					echo("<SCRIPT LANGUAGE='JavaScript'>
						window.alert('Your account is currently blocked. Please contact system admin for assistance.')
						window.location.href='home.php'
						</SCRIPT>");
					exit(); 
				}
				session_start();
				$_SESSION['SID'] = $systID;
				$_SESSION['EC'] = $ec;
				$_SESSION['Alias'] = $alias;
				$_SESSION['Addr'] = $addr;
				header("location:pharmportal.php");//redirects to another page
			}else If (mysqli_num_rows($result)==0){
				 echo("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('Login details are incorrect .Try again')
				window.location.href='home.php'
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
            background: url("images/img.jpg");
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
			function myfuncLog(){
				
					//Validation Regex
					var IDalphanumeric=/^[-0-9a-zA-Z]+$/;
					var ricc= /^[0-9]+$/ ;
					var alphaspaces=/^[ a-zA-Z]+$/;
					var phon=/^\d{10}$/;
					var whitespaces=/[^a-z|^A-Z|^\s]/;
					var letters=/^[a-zA-Z]+$/;
					
				//National ID validator
				if(document.myFormLog.txtIDLog.value==""){
					window.alert('Submission failed. Fill in your missing national id number.'); 
					return false;
					
				}
				if (document.myFormLog.txtIDLog.value.match(IDalphanumeric)){}
				else{
					window.alert('National ID Number must contain numbers and letters only. Registration failed'); 
				    return false;
				}
				if (document.myFormLog.txtIDLog.value.length!==12 && document.myFormLog.txtIDLog.value.length!==13){
						window.alert('Provided National ID length is invalid. Registration failed.'); 
						return false;
					}
					if (document.myFormLog.txtIDLog.value.indexOf('-')!==2){
						window.alert('Registration failed. National ID Number must contain a hyphen(-) on the third position.'); 
						return false;
					}
					if (isNaN(document.myFormLog.txtIDLog.value.substr(0-2))){
						window.alert('Registration failed. National ID Number first two characters must be numeric.'); 
						return false;
					}
					//For 12 national id	
					if (document.myFormLog.txtIDLog.value.length==12){
						if (isNaN(document.myFormLog.txtIDLog.value.substr(3,6))){
							window.alert('Registration failed. National ID Number middle six characters must be numeric.'); 
							return false;
						}
						if (isNaN(document.myFormLog.txtIDLog.value.substr(10,2))){
							window.alert('Registration failed. National ID Number last two characters must be numeric.'); 
							return false;
						}
						if (isNaN(document.myFormLog.txtIDLog.value.substr(9,1))){}
							else{
								window.alert('Registration failed. National ID Number check letter must be present on the tenth position.'); 
								return false;
						}
					}
					
					//For 13 national id	
					if (document.myFormLog.txtIDLog.value.length==13){
						if (isNaN(document.myForm.txtIDLog.value.substr(3,7))){
							window.alert('Registration failed. National ID Number middle six characters must be numeric.'); 
							return false;
						}
						if (isNaN(document.myFormLog.txtIDLog.value.substr(11,2))){
							window.alert('Registration failed. National ID Number last two characters must be numeric.'); 
							return false;
						}
						if (isNaN(document.myFormLog.txtIDLog.value.substr(10,1))){}
							else{
								window.alert('Registration failed. National ID Number check letter must be present on the tenth position.'); 
								return false;
						}
					}				
			//code below now send the form after validation is complete
			document.getElementsByClassName("subbyLog")[0].click();
			}
			//DOCTOR SIGHN UP VALIDATION IN JAVASCRIPT
			function myfuncDr(){
					//Validation Regex
					var IDalphanumeric=/^[-0-9a-zA-Z]+$/;
					var ricc= /^[0-9]+$/ ;
					var alphaspaces=/^[ a-zA-Z]+$/;
					var phon=/^\d{10}$/;
					var whitespaces=/[^a-z|^A-Z|^\s]/;
					var letters=/^[a-zA-Z]+$/;
					var email=document.getElementById('txtDrEmail');
					var filter =/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				//Alias validator
				if(document.frmDrSign.txtDrName.value==""){
					window.alert('Submission failed. Fill in your missing full name.'); 
					return false;
				}
				if (document.frmDrSign.txtDrName.value.match(alphaspaces)){}
				else{
					window.alert('Submission failed. Your name must contain letters only.'); 
					return false;
				}
				//EC validator
				if(document.frmDrSign.txtDrRegID.value==""){
					window.alert('Submission failed. Fill in your missing ec number.'); 
					return false;
				}
				if (document.frmDrSign.txtDrRegID.value.match(IDalphanumeric)){
					
				}else{
					window.alert('EC Number must contain numbers and letters only. Registration failed'); 
				    return false;
				}
				/* if (document.myForm.txtID.value.length!==12 && document.myForm.txtID.value.length!==13){
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
					}		 */		
					
				//Phone validator
				if(document.frmDrSign.txtDrPhone.value==""){
					window.alert('Submission failed. Fill in your missing phone number.'); 
					return false;
				}
				/* if (document.myForm.txtPhone.value.length!==10){
					window.alert('Submision failed. Phone length is invalid.'); 
					return false;
				}
						var str = document.myForm.txtPhone.value;
						var n=str.startsWith("07");
				if (n==false){
					window.alert('Submision failed. Zimbabwean Phone numbers start with 07.'); 
					return false;
				}   */
				
				//Email Validator
				if(document.frmDrSign.txtDrEmail.value==""){
					window.alert('Submission failed. Fill in your missing email address.'); 
					return false;
				}
				if (!filter.test(email.value) && document.frmDrSign.txtDrEmail.value!=""){
					window.alert('Submision failed. The Email you provided is invalid.'); 
					return false;
				}
				//Address Validator
				if(document.frmDrSign.txtDrAddr.value==""){
					window.alert('Submission failed. Fill in your missing business address.'); 
					return false;
				}
				//Username Validator
				if(document.frmDrSign.txtDrUsrnm.value==""){
					window.alert('Submission failed. Fill in your missing portal login username.'); 
					return false;
				}
				//Password Validator
				if(document.frmDrSign.txtDrPswd.value==""){
					window.alert('Submission failed. Fill in your missing portal login password.'); 
					return false;
				}
				if(document.frmDrSign.txtDrCnPswd.value==""){
					window.alert('Submission failed. Fill in your missing portal login confirm password.'); 
					return false;
				}
				//Confirm Password validator
				if(document.frmDrSign.txtDrPswd.value!==document.frmDrSign.txtDrCnPswd.value){
					window.alert('Submision failed. Password is not correctly confirmed.'); 
					return false;
				}
			//code below now send the form after validation is complete
			document.getElementsByClassName("drsubby")[0].click();
			}
			
			//PHARMACY SIGHN UP VALIDATION IN JAVASCRIPT
			function myfuncPh(){
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
				if(document.frmPharSign.txtPhName.value==""){
					window.alert('Submission failed. Fill in your missing full name.'); 
					return false;
				}
				if (document.frmPharSign.txtPhName.value.match(alphaspaces)){}
				else{
					window.alert('Submission failed. Your name must contain letters only.'); 
					return false;
				}
				//Phone validator
				if(document.frmPharSign.txtPhPhone.value==""){
					window.alert('Submission failed. Fill in your missing phone number.'); 
					return false;
				}
				//Email Validator
				if(document.frmPharSign.txtPhEmail.value==""){
					window.alert('Submission failed. Fill in your missing email address.'); 
					return false;
				}
				if (!filter.test(email.value) && document.frmPharSign.txtPhEmail.value!=""){
					window.alert('Submision failed. The Email you provided is invalid.'); 
					return false;
				}
				//Company Validator
				if(document.frmPharSign.txtPhCompany.value==""){
					window.alert('Submission failed. Fill in your missing company or organisation name.'); 
					return false;
				}
				//Reg validator
				if(document.frmPharSign.txtPhRegID.value==""){
					window.alert('Submission failed. Fill in your missing company reg number.'); 
					return false;
				}
				if (document.frmPharSign.txtPhRegID.value.match(IDalphanumeric)){
					
				}else{
					window.alert('Reg Number must contain numbers and letters only. Registration failed'); 
				    return false;
				}
				/* if (document.myForm.txtID.value.length!==12 && document.myForm.txtID.value.length!==13){
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
					}		 */		
					
				
				/* if (document.myForm.txtPhone.value.length!==10){
					window.alert('Submision failed. Phone length is invalid.'); 
					return false;
				}
						var str = document.myForm.txtPhone.value;
						var n=str.startsWith("07");
				if (n==false){
					window.alert('Submision failed. Zimbabwean Phone numbers start with 07.'); 
					return false;
				}   */
				
				//Address Validator
				if(document.frmPharSign.txtPhAddr.value==""){
					window.alert('Submission failed. Fill in your missing business address.'); 
					return false;
				}
				//Username Validator
				if(document.frmPharSign.txtPhUsrnm.value==""){
					window.alert('Submission failed. Fill in your missing portal login username.'); 
					return false;
				}
				//Password Validator
				if(document.frmPharSign.txtPhPswd.value==""){
					window.alert('Submission failed. Fill in your missing portal login password.'); 
					return false;
				}
				if(document.frmPharSign.txtPhCnPswd.value==""){
					window.alert('Submission failed. Fill in your missing portal login confirm password.'); 
					return false;
				}
				//Confirm Password validator
				if(document.frmPharSign.txtPhPswd.value!==document.frmPharSign.txtPhCnPswd.value){
					window.alert('Submision failed. Password is not correctly confirmed.'); 
					return false;
				}
			//code below now send the form after validation is complete
			document.getElementsByClassName("phsubby")[0].click();
			}
		</SCRIPT>
	</head>
    <body>
	<!-- Dr Login Modal HTML -->
	<div id="drLogin" class="modal fade">
		<div class="modal-dialog" style="margin-top:30px;">
			<div class="modal-content">
				<div class="modal-header">
					<img src="images\log.png" style="-moz-border-radius:15px;-webkit-border-radius:15px;height:50px;"/><label style="font-size:20pt;">Doctor Login</label>
				</div>
				<form method="POST" action="home.php" autocomplete="off" name="frmDrLogin">
				<div class="modal-body">
					<input type="text" class="form-control" name="txtDrLgUsrnm" placeholder="Doctor username"><br>
					<br><br>
					<input type="password" class="form-control" name="txtDrLgPswd" placeholder="Doctor Password"><br>
				</div>
				<div class="modal-footer" style="background-color: #535ba0;" >
					<button name="btnDrLogin" class="btn btn-default" type="submit">Login</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Pharmacy Login Modal HTML -->
	<div id="pharLogin" class="modal fade">
		<div class="modal-dialog" style="margin-top:30px;">
			<div class="modal-content">
				<div class="modal-header">
					<img src="images\log.png" style="-moz-border-radius:15px;-webkit-border-radius:15px;height:50px;"/><label style="font-size:20pt;">Pharmacy Login</label>
				</div>
				<form method="POST" action="home.php" autocomplete="off" name="frmPharLogin">
				<div class="modal-body">
					<input type="text" class="form-control" name="txtPhLgUsrnm" placeholder="Account username"><br>
					<br><br>
					<input type="password" class="form-control" name="txtPhLgPswd" placeholder="Account Password"><br>
				</div>
				<div class="modal-footer" style="background-color: #535ba0;" >
					<button name="btnPhLogin" class="btn btn-default" type="submit">Login</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	
	
	<!-- Pharmacy Sign Up Modal HTML -->
	<div id="pharSign" class="modal fade">
		<div class="modal-dialog" style="margin-top:0px;">
			<div class="modal-content">
				<form method="POST" action="home.php" autocomplete="off" name="frmPharSign" enctype="multipart/form-data">

					<div class="modal-header">
						<img src="images\log.png" style="-moz-border-radius:15px;-webkit-border-radius:15px;height:50px;"/><label style="font-size:20pt;">Pharmacy Signup</label>
					</div>
					<div class="container">
							<div class="col-lg-6">
								<label style="font-size:10pt;color:#4ddbff;font-weight:bold;">Fill all the required details and click submit<label><br>
								<label style="color:black;">Pharmacy Full Name</label>
								<input type="text" name="txtPhName" style="background-color:white;text-transform:capitalize;"></input>
								<label style="color:black;">Contact Person Phone or Mobile</label>
								<input type="text" name="txtPhPhone" style="background-color:white;"></input>
								<label style="color:black;">Contact Person Email</label>
								<input type="text" name="txtPhEmail" id="txtPhEmail" style="background-color:white;"></input>
								<label style="color:black;">Pharmacy Company Name</label>
								<input type="text" name="txtPhCompany" style="background-color:white;"></input>
								<label style="color:black;">Pharmacy Company Registration ID</label>
								<input type="text" name="txtPhRegID" style="background-color:white;"></input>
								<label style="color:black;">Business Address</label>
								<input type="text" name="txtPhAddr" style="background-color:white;"></input>
								<label style="color:black;">Login Username</label>
								<input type="text" name="txtPhUsrnm" style="background-color:white;"></input>
								<label style="color:black;">Login Password</label>
								<input type="password" class="form-control" name="txtPhPswd" style="background-color:white;"></input>
								<label style="color:black;">Confirm Login Password</label>
								<input type="password" class="form-control" name="txtPhCnPswd" style="background-color:white;"></input>
								<label style="color:black;font-size:9pt;" for="jumb">Location Image File(.jpg and .png files only)</label>
					            <input type="file" style="color:black;font-size:8pt;" name="file" id="profile-img">
							</div>
							<input style="left:28%;" class="phsubby" type="submit" name="btnPhSend" hidden>
					</div>
					<br>
					<div class="modal-footer" style="background-color: #535ba0;" >
					    
						<button class="btn btn-default btn-primary" type="button" style="width:30%;" onclick="myfuncPh()">Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Dr Sign Up Modal HTML -->
	<div id="drSign" class="modal fade">
		<div class="modal-dialog" style="margin-top:0px;">
			<div class="modal-content">
				<form method="POST" action="home.php" autocomplete="off" name="frmDrSign">
					<div class="modal-header">
						<img src="images\log.png" style="-moz-border-radius:15px;-webkit-border-radius:15px;height:50px;"/><label style="font-size:20pt;">Doctor Sign Up</label>
					</div>
					<div class="container">
						
							<div class="col-lg-6">
								<label style="font-size:10pt;color:#4ddbff;font-weight:bold;">Fill all the required details and click submit<label><br>
								<label style="color:black;">Doctor Full Name</label>
								<input type="text" name="txtDrName" style="background-color:white;text-transform:capitalize;"></input>
								<label style="color:black;">Doctor EC Number</label>
								<input type="text" name="txtDrRegID" style="background-color:white;"></input>
								<label style="color:black;">Doctor Phone or Mobile</label>
								<input type="text" name="txtDrPhone" style="background-color:white;"></input>
								<label style="color:black;">Doctor Email</label>
								<input type="text" name="txtDrEmail" id="txtDrEmail" style="background-color:white;"></input>
								<label style="color:black;">Doctor Fax</label>
								<input type="text" name="txtDrFax" style="background-color:white;"></input>
								<label style="color:black;">Business Address</label>
								<input type="text" name="txtDrAddr" style="background-color:white;"></input>
								<label style="color:black;">Login Username</label>
								<input type="text" name="txtDrUsrnm" style="background-color:white;"></input>
								<label style="color:black;">Login Password</label>
								<input type="password" class="form-control" name="txtDrPswd" style="background-color:white;"></input>
								<label style="color:black;">Confirm Login Password</label>
								<input type="password" class="form-control" name="txtDrCnPswd" style="background-color:white;"></input>
							</div>
							<input style="left:28%;" class="drsubby" type="submit" name="btnDrSend" hidden>
					</div>
					<br>
					<div class="modal-footer" style="background-color: #535ba0;" >
						<button class="btn btn-default btn-primary" type="button" style="width:35%;" onclick="myfuncDr()">Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="container" style="background-color:rgba(0,0,0,0.8);padding:10px 0px;width:100%;">
		<div class="col-md-3">
			<img src="images\log.png" style="height:10%;width:360px;-moz-border-radius:15px;-webkit-border-radius:15px;"/>
			<form method="POST" action="home.php" autocomplete="off">
	                <br>				
					<div class="input-group">
						<input type="text" style="background-color:transparent;color:white;width:534px;" name="txtSearchKey" class="form-control" placeholder="Type your drug name">
							<span class="input-group-btn btn-success">
								<button name="btnSearchProduct" type="submit" style="text-align:center;width:196px;" class="btn btn-default" type="button">FIND THE LOWEST PRICE<span></button>
							</span>
					</div>
			</form>
		</div>
	
		<div class="col-md-6">
			<!--<img src="images\img.jpg" style="height:15%;width:100%;-moz-border-radius:15px;-webkit-border-radius:15px;"/>-->
		</div>
		
		<div class="col-md-3">
			<div class="btn-group" role="group">
				<button type="button" style="width:200px;" class="btn btn-default pull-right dropdown-toggle" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Doctor
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
					<li><a href="#drLogin" data-toggle="modal"><span ></span>  Login</a></li>
					<li><a href="#drSign" data-toggle="modal">Sign Up</a></li>
				</ul>
			</div>
			<br><br>
			<div class="btn-group" role="group">
				<button type="button" style="width:200px;" class="btn btn-default pull-right dropdown-toggle" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Pharmacy
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
					<li><a href="#pharLogin" data-toggle="modal">Login</a></li>
					<li><a href="#pharSign" data-toggle="modal">Sign Up</a></li>
				</ul>
			</div>
		</div>
    </div>  
<br>
    <div class="container" style="background-color:rgba(0,0,0,0.8);padding: 10px 0px;width:100%;">
		<div class="col-md-3">
            <div class="col-md-12">
                <div >
                    <a a href="#myModPin" data-toggle="modal" style="background-color: #535ba0;border:#535ba0;" class="btn btn-success cart-btn">Add Customer Cart Information<span class='glyphicon glyphicon-shopping-cart'></a>
				</div>
				<div >
                    <a a href="#myModLog" data-toggle="modal"  style="background-color: #535ba0;border:#535ba0;" class="btn btn-success cart-btn">Customer Login By ID<span class='glyphicon glyphicon-shopping-cart'></a>
				</div>
                <div class="clearfix"></div>
			</div>
		
			
        </div>
        <div class="col-md-9">  
            
                <div class="col-sm-12">
					<form method="POST" action="home.php" autocomplete="off">  
						<button type="submit" name="frequently_searched_drugs" style="background-color: #535ba0;width:190px;border:#535ba0;" class="<?php echo $btnOne;?>">Frequently Searched Drugs</button>
						<button type="submit" name="bottled" style="background-color:#535ba0;width:130px;border:#535ba0;" class="<?php echo $btnTwo;?>">Bottled</button>
						<button type="submit" name="tablets" style="background-color: #535ba0;width:130px;border:#535ba0;" class="<?php echo $btnThree;?>">Tablets</button>
						<button type="submit" name="capsules" style="background-color: #535ba0;width:130px;border:#535ba0;" class="<?php echo $btnFour;?>">Capsules</button>
						<button type="submit" name="prescription_only" style="background-color: #535ba0;width:150px;border:#535ba0;" class="<?php echo $btnFive;?>">prescription only</button>
						<button type="submit" name="presc" style="background-color: #535ba0;width:160px;border:#535ba0;" class="<?php echo $btnSix;?>">Prescription Based</button>
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
                                <img src="Products\<?php echo $row['ImgPath'];?>" style="width:250px;height:250px;"/>
                                <hr />
                                <h2 style="color:#424141;font-weight:bold;">$<?php echo $row['Cost'];?></h2>
                                <label style="color:#615c5c;font-weight:bold;">Drug Name: <?php echo $row['Alias'];?></label><br>
								<label style="color:#615c5c;">SI Unit: <?php echo $row['SIUVal']." ".$row['SiUnit'];?></label><br>
								<label style="color:#615c5c;font-weight:bold;">Pharmacy Name: <?php echo $row['PharmNam'];?></label><br>
								<label style="color:#615c5c;">Pharmacy Location: <?php echo $row['Location'];?></label><br>
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
            $(function () {
                $("#datepicker,#datepicker1").datepicker();
            });
        </script>
        <!-- //Calendar -->
	<!--Modal Registration-->
	<div id="myModPin" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				    <img src="images\log.png" style="width:100px;height:100px;"/>
					<h4 class="modal-title">Customer Information Registration</h4>
				</div>
				<form method="POST" action="home.php" autocomplete="off" name="myForm">
					<div class="modal-body">
						<input type="text" class="form-control" name="txtAlias" placeholder="Type your name in full here" style="text-transform: capitalize;"><br>
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
	<!--Modal Login-->
	<div id="myModLog" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				    <img src="images\log.png" style="width:100px;height:100px;"/>
					<h4 class="modal-title">Customer Login</h4>
				</div>
				<form method="POST" action="home.php" autocomplete="off" name="myFormLog">
					<div class="modal-body">
						<input type="text" class="form-control" name="txtIDLog" placeholder="Type your national id here" style='text-transform:uppercase;'><br>
					</div>
					<div class="modal-footer">
						<button name="btnSend" class="btn btn-default" type="button" onclick="myfuncLog()">Submit</button>
						<input type="submit" name="btnSendLog" class="subbyLog" hidden>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>	
	
</html>			