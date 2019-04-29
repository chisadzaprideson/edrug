<?php
	session_start();
	$systID= $_SESSION['SID'];
	$ec=$_SESSION['EC'];
	$alias=$_SESSION['Alias'];
	$addr=$_SESSION['Addr'];
					$btnSave="enabled";
					$btnUpdat="disabled";
					$btnDelet="disabled";
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
		unset ($_SESSION['PreID']);
		unset ($_SESSION['PrePin']);
		header("location: home.php");
	}
	include('dbconnect.php');
	
	//find the PRESCRIPTION id
	$query="select * from increments";		
	$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result))	
			{
				
				$presid=$row['PrescID'];
				$rand=$row['PreKey'];
			}
		}
	mysqli_free_result($result);
	
	$patNam="";
	$patDis="";
	$preDat=date("d/m/Y");
	$druNam="";
	$druSI="";
	$SIVal="";
	$druSI="";
	$druNum="";
	
	$btnSave="enabled";
	$btnUpdat="disabled";
	$btnDelet="disabled";
	
	//Add Drug To Prescription
	if (isset($_POST['btnDrugAdd'])){
		$jst="Added";
		$emp="Emp";
		$nuhhh="No";
		$namDr=$_POST['txtDruNam'];
		$siDr=$_POST['cmbDruSI'];
		$sivalDr=$_POST['txtSIVal'];
		$numDr=$_POST['txtDruNum'];
	    $zer=0;
		mysqli_query($myConn,"INSERT INTO prescriptions VALUES ('$presid','$zer','$emp','$emp','$preDat','$namDr','$siDr','$sivalDr','$numDr','$jst','$nuhhh','$zer')");
		echo('<SCRIPT>
				window.location.href="drportal.php";
			</SCRIPT>');
		exit();
	}
	if (isset($_POST['btnSavePresc'])){
		$jst="Saved";
		$namPt=$_POST['txtPatName'];
		$disDt=$_POST['txtPatDis'];
		$pin=$_POST['txtRand'];
		
		//Find if drugs were added
		$result=mysqli_query($myConn,"SELECT * FROM prescriptions WHERE ID='$presid'");
		$row=mysqli_fetch_assoc($result);
		$rows=mysqli_num_rows($result);
		if ($rows==0){
			echo("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('There are no drugs added to this prescription, saving failed.')
				window.location.href='drportal.php'
				</SCRIPT>");
			exit();
		}
		mysqli_free_result($result);
		mysqli_query($myConn,"UPDATE prescriptions SET Pin='$pin',PatientName='$namPt',Disease='$disDt',Stat='$jst',Dr='$systID' WHERE ID='$presid'");
		$rand=rand(1000000,3000000);
		mysqli_query($myConn,"UPDATE increments SET PrescID=PrescID + 1, PreKey='$rand'");
		
		echo('<SCRIPT>window.alert("Prescription saving is successfully done.");
			window.location.href="drportal.php";
			</SCRIPT>');
		exit();
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

		//now save the record
		mysqli_query($myConn,"UPDATE doctors SET FullName='$alias',ID='$ec',Phone='$phone',Email='$email',Fax='$fax',BuisAddress='$addr',Username='$usrnm',Password='$pswd' WHERE SystID='$systid' ");
		
		$_SESSION['EC']=$ec;
		$_SESSION['Alias']=$alias;
		$_SESSION['Addr']=$addr;
	
		echo('<SCRIPT>window.alert("Doctor details updating is successfully done.");
			window.location.href="drportal.php";
			</SCRIPT>');
		exit();	
	}

	if (isset($_POST['btnDelDel'])){
		mysqli_query($myConn,"DELETE FROM prescriptions WHERE ID='$presid'");
		echo('<SCRIPT>window.alert("Prescription details are successfully removed from system.");
			window.location.href="drportal.php";
			</SCRIPT>');
		exit();	
	}	
	
	//Added Drugs Infor Actions(Add,Remove,Delete)
   	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
		$sentence=$_GET["id"];
		$secVal=strlen($sentence)-3;
		$product=substr($sentence,3,$secVal);
		$actio=substr($sentence,0,3);
		$cartid=$_SESSION['cartID'];
		
		//First search the number added
		$sql="SELECT * FROM prescriptions WHERE ID='$presid' AND DrugNam='$product'";
		$result=mysqli_query($myConn,$sql);
		$row=mysqli_fetch_assoc($result);//attach row variable to STUDENT table rows
		$numAdded=$row["DrugNum"];
	    
		$numAddOne=$numAdded + 1;
		$numLessOne=$numAdded - 1;
		
	
		mysqli_free_result($result);
		
		if ($actio=="Add"){	
			
			mysqli_query($myConn,"UPDATE prescriptions SET DrugNum = '$numAddOne' WHERE ID='$presid' AND DrugNam='$product' ");
			echo("<SCRIPT LANGUAGE='JavaScript'>
				window.location.href='drportal.php'
				</SCRIPT>");
			exit(); 
		}else if ($actio=="Rem"){
			//See if its one or more
			$sql="SELECT * FROM prescriptions WHERE ID='$presid' AND DrugNam='$product'";
			$result=mysqli_query($myConn,$sql);
			$row=mysqli_fetch_assoc($result);
			$numAddey=$row["DrugNum"];
			
			if ($numAddey > 1){
			mysqli_query($myConn,"UPDATE prescriptions SET DrugNum = '$numLessOne' WHERE ID='$presid' AND DrugNam='$product' ");
				echo("<SCRIPT LANGUAGE='JavaScript'>
					window.location.href='drportal.php'
					</SCRIPT>");
				exit();
			}else{
				mysqli_query($myConn,"DELETE FROM prescriptions WHERE ID='$presid' AND DrugNam='$product' ");
				echo("<SCRIPT LANGUAGE='JavaScript'>
					window.location.href='drportal.php'
					</SCRIPT>");
				exit();
			}
		}else if ($actio=="Del"){
			mysqli_query($myConn,"DELETE FROM prescriptions WHERE ID='$presid' AND DrugNam='$product' ");
			echo("<SCRIPT LANGUAGE='JavaScript'>
				window.location.href='drportal.php'
				</SCRIPT>");
					
			exit();
		}
			
		
	}	
   //Presc Button Search
	if (isset($_POST['btnSeaDrug'])){
		if (empty($_POST['txtSeaDrug'])){
			echo('<SCRIPT>window.alert("Prescription search failed because you did not provide the ID.");
				window.location.href="drportal.php";
				</SCRIPT>');
		    exit();
		}
		$ker=$_POST['txtSeaDrug'];
		$prosql="SELECT * FROM prescriptions WHERE ID = '$ker' AND Dr='$systID'";
		$result=mysqli_query($myConn,$prosql) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_array($result))	{
					
					$presid=$row['ID'];
					$rand=$row['Pin'];
					$patNam=$row['PatientName'];
					$patDis=$row['Disease'];
					$preDat=$row['Date'];
					
					$btnSave="disabled";
					$btnUpdat="enabled";
					$btnDelet="enabled";
		        }
			}else{
				echo('<SCRIPT>window.alert("Prescription ID provided is not matching any prescription in system database.");
					window.location.href="drportal.php";
					</SCRIPT>');
				exit();
			}
	}	
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Doctor Portal</title>
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
			function myfuncAdd(){
					//Validation Regex
					var IDalphanumeric=/^[-0-9a-zA-Z]+$/;
					var ricc= /^[0-9]+$/ ;
					var alphaspaces=/^[ a-zA-Z]+$/;
					var phon=/^\d{10}$/;
					var whitespaces=/[^a-z|^A-Z|^\s]/;
					var letters=/^[a-zA-Z]+$/;
					
				//Drug Name Validator
				if(document.frmPresc.txtDruNam.value==""){
					window.alert('Submission failed. Fill in the missing drug name.'); 
					return false;
				}
				//Drug SIUbitt Validator
				if(document.frmPresc.cmbDruSI.value==""){
					window.alert('Submission failed. Fill in the missing drug SI Unit.'); 
					return false;
				}
				//Drug SIUbitt Val Validator
				if(document.frmPresc.txtSIVal.value==""){
					window.alert('Submission failed. Fill in the missing drug SI Unit Value.'); 
					return false;
				}
				//Drug Number Validator
				if(document.frmPresc.txtDruNum.value==""){
					window.alert('Submission failed. Fill in the required drug number.'); 
					return false;
				}
			//code below now send the form after validation is complete
			document.getElementsByClassName("addsubby")[0].click();
			}
			function myfuncSavPre(){
					//Validation Regex
					var IDalphanumeric=/^[-0-9a-zA-Z]+$/;
					var ricc= /^[0-9]+$/ ;
					var alphaspaces=/^[ a-zA-Z]+$/;
					var phon=/^\d{10}$/;
					var whitespaces=/[^a-z|^A-Z|^\s]/;
					var letters=/^[a-zA-Z]+$/;
					
				//Drug Name Validator
				if(document.frmPresc.txtPatName.value==""){
					window.alert('Submission failed. Fill in the missing patient name.'); 
					return false;
				}
				//Drug SIUbitt Validator
				if(document.frmPresc.txtPatDis.value==""){
					window.alert('Submission failed. Fill in the missing patient disease or illness.'); 
					return false;
				}
				
			//code below now send the form after validation is complete
			document.getElementsByClassName("savesubby")[0].click();
			}
			
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
		</SCRIPT>
	</head>
    <body>

	<!-- Dr Sign Up Modal HTML -->
	<div id="drSign" class="modal fade">
		<div class="modal-dialog" style="margin-top:0px;">
			<div class="modal-content">
				<form method="POST" action="drportal.php" autocomplete="off" name="frmDrSign">
					<div class="modal-header">
						<img src="images\log.png" style="-moz-border-radius:15px;-webkit-border-radius:15px;height:50px;"/><label style="font-size:20pt;">Doctor Details Edditing</label>
					</div>
					<div class="container">
						    <?php
								//See if its one or more
								$sql="SELECT * FROM doctors WHERE SystID='$systID'";
								$result=mysqli_query($myConn,$sql);
								$row=mysqli_fetch_assoc($result);
								
								$one=$row["FullName"];
								$two=$row["ID"];
								$three=$row["Phone"];
								$four=$row["Email"];
								$five=$row["Fax"];
								$six=$row["BuisAddress"];
								$seven=$row["Username"];
								$eight=$row["Password"];
								$nine=$row["Password"];
								
							?>
							<div class="col-lg-6">
								<label style="font-size:10pt;color:#4ddbff;font-weight:bold;">Fill all the required details and click submit<label><br>
								<label style="color:black;">Doctor Full Name</label>
								<input type="text" name="txtDrName" value='<?php echo $one; ?>' style="background-color:white;text-transform:capitalize;"></input>
								<label style="color:black;">Doctor EC Number</label>
								<input type="text" name="txtDrRegID" value='<?php echo $two; ?>' style="background-color:white;"></input>
								<label style="color:black;">Doctor Phone or Mobile</label>
								<input type="text" name="txtDrPhone" value='<?php echo $three; ?>'  style="background-color:white;"></input>
								<label style="color:black;">Doctor Email</label>
								<input type="text" name="txtDrEmail" value='<?php echo $four; ?>' id="txtDrEmail" style="background-color:white;"></input>
								<label style="color:black;">Doctor Fax</label>
								<input type="text" name="txtDrFax" value='<?php echo $five; ?>' style="background-color:white;"></input>
								<label style="color:black;">Business Address</label>
								<input type="text" name="txtDrAddr" value='<?php echo $six; ?>' style="background-color:white;"></input>
								<label style="color:black;">Login Username</label>
								<input type="text" name="txtDrUsrnm" value='<?php echo $seven; ?>' style="background-color:white;"></input>
								<label style="color:black;">Login Password</label>
								<input class="form-control" type="password" name="txtDrPswd" value='<?php echo $eight; ?>' style="background-color:white;"></input>
								<label style="color:black;">Confirm Login Password</label>
								<input class="form-control" type="password" name="txtDrCnPswd" value='<?php echo $nine; ?>' style="background-color:white;"></input>
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
	<div id="drugSearch" class="modal fade">
		<div class="modal-dialog" style="margin-top:30px;">
			<div class="modal-content">
				<div class="modal-header">
					<img src="images\log.png" style="-moz-border-radius:15px;-webkit-border-radius:15px;height:50px;"/><label style="font-size:20pt;">Prescription Search</label>
				</div>
				<form method="POST" action="drportal.php" autocomplete="off" name="frmDruSea">
				<div class="modal-body">
					<input type="text" class="form-control" name="txtSeaDrug" placeholder="Type in the prescription ID here"><br>
				</div>
				<div class="modal-footer" style="background-color: #535ba0;" >
					<button name="btnSeaDrug" class="btn btn-default" type="submit">Search</button>
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
				</tr>y
			</table>
		</div>
		
		<div class="col-md-3">

				<div class="btn-group" role="group">
					<button type="button" style="width:200px;left:100px;" class="btn btn-default dropdown-toggle pull-right" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Doctor
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
						<li>
							<form method="POST" action="drportal.php" autocomplete="off">
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
		<form method="POST" action="drportal.php" autocomplete="off" name="frmPresc">
			<div class="col-md-3">
				<img src="images\drimg.jpg" style="height:40%;width:360px;-moz-border-radius:15px;-webkit-border-radius:15px;"/>
			</div>
		
			<div class="col-md-3">
				<label style="font-size:10pt;color:#4ddbff;font-weight:bold;">Patient Details<label><br><br>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Patient Name<label style="color:white;">.......</label></span>
						<input name="txtPatName" maxlength="50" value='<?php echo $patNam;?>' type="text" class="form-control" style="background-color:white;">						
					</div>
				</div>

				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Patient Illness<label style="color:white;">......</label></span>
						<input name="txtPatDis" maxlength="50" value='<?php echo $patDis;?>' type="text" class="form-control" style="background-color:white;">						
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Prescription Date</span>
						<input name="txtRegDat" maxlength="50" value='<?php echo $preDat;?>' type="text" class="form-control" style="background-color:white;">						
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Prescription ID<label style="color:white;">.....</label></span>
						<input name="txtPresID" maxlength="50" value='<?php echo $presid;?>' type="text" class="form-control" style="background-color:white;">						
					</div>
				</div>
			</div>
			
			<div class="col-md-3">
				<label style="font-size:10pt;color:#4ddbff;font-weight:bold;">Prescription Drugs Details<label><br><br>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Drug Name<label style="color:white;">............</label></span>
						<input name="txtDruNam" maxlength="50" value='<?php echo $druNam;?>' type="text" class="form-control" style="background-color:white;">						
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Drug SIUnit<label style="color:white;">............</label></span>
							<select name="cmbDruSI" class="form-control" style="background:white;color:black;">
								<option value="<?php echo $druSI;?>"><?php echo $druSI;?></option>
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
						<input name="txtSIVal" maxlength="50" value='<?php echo $SIVal;?>' type="number" class="form-control" style="background-color:white;">						
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Drug Number<label style="color:white;">........</label></span>
						<input name="txtDruNum" value='<?php echo $druNum;?>' type="number" class="form-control" style="background-color:white;">						
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">Prescription Key<label style="color:white;">...</label></span>
						<input name="txtRand" value='<?php echo $rand;?>' type="number" class="form-control" style="background-color:white;">						
					</div>
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group">
					<button type="button" style="background-color:#535ba0;border:#535ba0;color:white;width:200px;margin-top:40px;" class="btn btn-default pull-right" onclick="myfuncAdd()">Add Drug To Prescription</button>
					<input style="left:28%;" class="addsubby" type="submit" name="btnDrugAdd" hidden>
				</div>
				<div class="form-group">
					<button type="button" style="background-color:#535ba0;border:#535ba0;color:white;width:200px;margin-top:15px;" class="btn btn-default pull-right" onclick="myfuncSavPre()">Save Prescription</button>
					<input style="left:28%;" <?php echo $btnSave; ?> class="savesubby" type="submit" name="btnSavePresc" hidden>
				</div>
				<div class="form-group">
					<button type="button" style="background-color:#535ba0;border:#535ba0;color:white;width:200px;margin-top:15px;" class="btn btn-default pull-right" href="#drugSearch" data-toggle="modal">Search Prescription</button>
				</div>
				
				<div class="form-group">
					<button type="submit" name="btnDelDel" <?php echo $btnDelet; ?> style="background-color:#535ba0;border:#535ba0;color:white;width:200px;margin-top:15px;" class="btn btn-default pull-right">Delete Prescription</button>
				</div>
				<div class="form-group">
					<button type="submit" style="background-color:#535ba0;border:#535ba0;color:white;width:200px;margin-top:15px;" class="btn btn-default pull-right">Clear Form</button>
				</div>
			</div>
		</form>
    </div> 
	
	<div class="container" style="background-color:rgba(0,0,0,0.7);padding:0px 10px;width:100%;margin-top:10px;">
					<div class="page-header clearfix">
                        <h2 style="font-size:10pt;color:#4ddbff;font-weight:bold;" class="pull-left">Prescription Added Drugs Details</h2>
                    </div>
                    <?php
                    $nuh="No";
                    // Attempt select query execution
                    $sql = "SELECT * FROM prescriptions WHERE ID='$presid'";
                    if($result = mysqli_query($myConn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-condensed'>";
                                echo "<thead>";
                                    echo "<tr style='color:white;background-color:#20B2AA;'>";
                                        echo "<th>Added Drug Name</th>";
                                        echo "<th>Drug SI Unit</th>";
                                        echo "<th>Drug SI Unit Value</th>";
                                        echo "<th>Number Added</th>";
										echo "<th>Add One</th>";
                                        echo "<th>Remove One</th>";
                                        echo "<th>Remove All</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr style='color:black;background-color:#FDF5E6;'>";
                                        echo "<td>" . $row['DrugNam'] . "</td>";
                                        echo "<td>" . $row['DrugSI'] . "</td>";
                                        echo "<td>" . $row['SIVal'] . "</td>";
                                        echo "<td>" . $row['DrugNum'] . "</td>";
                                        echo '<td style="text-align:center;">';
                                            echo "<a href='drportal.php?id=Add". $row['DrugNam'] ."' title='Add One Item'><span class='glyphicon glyphicon-plus'></span></a>";
                                        echo "</td>";
										 echo '<td style="text-align:center;">';
											echo "<a href='drportal.php?id=Rem". $row['DrugNam'] ."' title='Remove One Item' ><span class='glyphicon glyphicon-minus'></span></a>";
                                        echo "</td>";
										echo '<td style="text-align:center;">';
											echo "<a href='drportal.php?id=Del". $row['DrugNam'] ."' title='Remove All' ><span class='glyphicon glyphicon-remove'></span></a>";
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
        <!-- Calendar -->

        <script>
            $(function () {
                $("#datepicker,#datepicker1").datepicker();
            });
        </script>
	</body>	
	
</html>			