<?php
    //Call Db Connection
	include('dbconnect.php');
	session_start();
		$ec= $_SESSION['Ec'];
		$alias= $_SESSION['alias'];
		$phone= $_SESSION['phone'];
		$_SESSION['RequestID']="";
		$_SESSION['Search']="Off";
		$_SESSION['Asset']="";
		$_SESSION['Priority']="";
		$_SESSION['Num']="";
		$_SESSION['Details']="";
		$btnUpd="disabled";
		$btnDel="disabled";
		$btnCrea="enabled";
		
	//If page accessed without log in
	if(empty($_SESSION['Ec'])){
		header("location: login.php");//redirects to another page
	}  

	//Logout
	if(isset($_POST['lgout'])){
	session_destroy();
	unset ($_SESSION['RequestID']);
	unset ($_SESSION['Asset']);
	unset ($_SESSION['Priority']);
	unset ($_SESSION['Num']);
	unset ($_SESSION['Details']);
	
	
	unset ($_SESSION['Ec']);
	unset ($_SESSION['alias']);
	unset ($_SESSION['ryts']);
	unset ($_SESSION['phone']);
	header("location: login.php");//redirects to another page
	}
	
	//Search Code
	if(isset($_POST['btnModSearch'])){
		$sechID=$_POST['txtSearcKey'];
			//check if its there and assign new values to session variables if found
			//$db = mysqli_connect('localhost','root','','dbase');
	        $query="SELECT * FROM requests WHERE Ec='$ec' && RequestID='$sechID'";
				
			$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_array($result))	{
					$_SESSION['Search']="On";
					$_SESSION['RequestID']=$sechID;
					$_SESSION['Asset']=$row['Asset'];
					$_SESSION['Priority']=$row['Priority'];
					$_SESSION['Num']=$row['Num'];
					$_SESSION['Details']=$row['Description'];
					$btnUpd="enabled";
					$btnDel="enabled";
					$btnCrea="disabled";
					
		        }
				mysqli_free_result($result);
			}else{
				echo("<SCRIPT LANGUAGE='JavaScript'>
                    window.alert('There is no any request in system matching the provided id.')
                    </SCRIPT>");
                echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
				exit();
			}
	}
	// Eye Search
		if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
		$sechID=$_GET["id"];
	   $query="SELECT * FROM requests WHERE Ec='$ec' && RequestID='$sechID'";
				
			$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_array($result))	{
					$_SESSION['Search']="On";
					$_SESSION['RequestID']=$sechID;
					$_SESSION['Asset']=$row['Asset'];
					$_SESSION['Priority']=$row['Priority'];
					$_SESSION['Num']=$row['Num'];
					$_SESSION['Details']=$row['Description'];
					$btnUpd="enabled";
					$btnDel="enabled";
					$btnCrea="disabled";
		        }
			}
		mysqli_free_result($result);
	}
    //Submit Or Insert Code
	if(isset($_POST['btnInsert'])){
		//Take posts first
		$regDat=date("d/m/Y");
		$emp="Empty";
		$appFor="Applied For";
		$asset=$_POST['txtAsset'];
		$priority=$_POST['cmbPriority'];
		$num=$_POST['txtNum'];
		$details=$_POST['txtDetails'];
		if ($_SESSION['Details']==$_POST['txtDetails']){
			$details="Empty";
		}
		
		
			//Check for next request id
			
	        $query="SELECT * FROM figures";
				
			$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_array($result))	{
					$reqId=$row['RequestID'];
		        }
				
				mysqli_free_result($result);
	            //mysqli_close($myConn);
			}
		//Now save into dbase then increment id
		mysqli_query($myConn,"INSERT INTO requests  VALUES('$reqId','$regDat','$ec','$alias','$phone','$asset','$priority','$num','$details','$appFor','$emp','$emp','$emp')");
		$reqId=$reqId + 1;
		mysqli_query($myConn,"UPDATE figures SET RequestID = '$reqId'");
		echo("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('The request is successfully saved in system.')
            </SCRIPT>");
        echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
		exit();
	}
	//Button Update Code
	if(isset($_POST['btnUpdate'])){
		$emp="Empty";
		$appFor="Applied For";
		$asset=$_POST['txtAsset'];
		$priority=$_POST['cmbPriority'];
		$num=$_POST['txtNum'];
		$details=$_POST['txtDetails'];
		if ($_SESSION['Details']==$_POST['txtDetails']){
			$details="Empty";
		}
		    $reqId=$_POST['txtID'];
			//Check if not already approved
	        $query="SELECT * FROM requests WHERE RequestID ='$reqId'";	
			$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_array($result))	{
					$str=$row['State'];
				}
			}
			if ($str==$appFor){
				mysqli_query($myConn,"UPDATE requests SET Asset = '$asset',Priority= '$priority',Num= '$num',Description= '$details' WHERE RequestID = '$reqId' && State = '$appFor'");
				echo("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('The request is successfully updated in system database.')
				</SCRIPT>");
				echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
			exit();	
			}else{
				if ($str=="Approved"){
					echo("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('The request is already approved and can not be updated now.')
					</SCRIPT>");
					echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
					exit();
				}else if ($str=="On Purchasing"){
					echo("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('The request is on purchase and can not be updated now.')
					</SCRIPT>");
					echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
					exit();	
				}else if ($str=="Allocated"){
					echo("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('The request is already allocated and can not be updated now.')
					</SCRIPT>");
					echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
					exit();	
				}else if ($str=="Disapproved"){
					echo("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('The request was disapproved and can not be updated now.')
					</SCRIPT>");
					echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
					exit();	
				}
				 	
			}	
	}
	//Button Delete Code
	if(isset($_POST['btnDelete'])){
			$appFor="Applied For";
		    $reqId=$_POST['txtID'];
			//Check if not already approved allocated or under purchase
	        $query="SELECT * FROM requests WHERE RequestID ='$reqId'";	
			$result=mysqli_query($myConn,$query) or die(mysqli_error($myConn));
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_array($result))	{
					$str=$row['State'];
				}
			} 	
			
			if ($str==$appFor){
				mysqli_query($myConn,"DELETE FROM requests WHERE RequestID='$reqId'");
				echo("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('The request is successfully deleted from system database.')
				</SCRIPT>");
				echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
			    exit();	
			}else if ($str=="Disapproved"){
				mysqli_query($myConn,"DELETE FROM requests WHERE RequestID='$reqId'");
				echo("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('The request is successfully deleted from system database.')
				</SCRIPT>");
				echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
			    exit();	
			}else{
				if ($str=="Approved"){
					echo("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('The request is already approved and can not be deleted now.')
					</SCRIPT>");
					echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
					exit();
				}else if ($str=="On Purchasing"){
					echo("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('The request is on purchase and can not be deleted now.')
					</SCRIPT>");
					echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
					exit();	
				}else if ($str=="Allocated"){
					echo("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('The request is already allocated and can not be deleted now.')
					</SCRIPT>");
					echo "<meta http-equiv=refresh content=\"0; URL=request.php\">";
					exit();	
				}
				 	
			}	
	}
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Asset Requests</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Lato:400,700,400italic,300' rel='stylesheet' type='text/css'>

		<!-- all css here -->
		
		<!-- bootstrap v3.3.6 css -->       
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <!-- Animated text css -->
		<link rel="stylesheet" href="css/animated.css">
		<!-- owl.carousel css -->
        <link rel="stylesheet" href="css/owl.carousel.css">
        <link rel="stylesheet" href="css/owl.transitions.css">
		<!-- font-awesome css -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
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
			body{
			background:url("images/bg3.jpg");
			background-size: cover;
		
	    }
		</style>
		<SCRIPT LANGUAGE='JavaScript'>
			function myfunc(){
				//All fields validator
				if(document.myForm.txtDetails.value=="" && document.myForm.txtAsset.value=="" && document.myForm.txtNum.value=="" && document.myForm.cmbPriority.value=="" ){
					window.alert('Submission failed. You can not submit black details.'); 
					return false;
				}
				//Asset Validator
				if(document.myForm.txtAsset.value==""){
					window.alert('Submission failed. Fill in the missing asset name.'); 
					return false;
				}
				//Priority Validator
				if(document.myForm.cmbPriority.value==""){
					window.alert('Submission failed. Select the missing asset request priority.'); 			
					return false;
				}
				//Number of items Validator
				if(document.myForm.txtNum.value==""){
					window.alert('Submission failed. Fill in the missing asset requested number.'); 
					return false;
				}
				if(document.myForm.txtNum.value<=0){
					window.alert('Submission failed. Number of assets requested can note be less than or equal to zero.'); 
					return false;
				}
			//code below now send the form after validation is complete
			document.getElementsByClassName("subby")[0].click();
			}
			
				function btnUpdtFunc(){
				//All fields validator
				if(document.myForm.txtAsset.value=="" && document.myForm.txtNum.value=="" && document.myForm.cmbPriority.value=="" ){
					window.alert('Submission failed. You can not submit black details.'); 
					return false;
				}
				//Asset Validator
				if(document.myForm.txtAsset.value==""){
					window.alert('Updating failed. Fill in the missing asset name.'); 
					return false;
				}
				//Priority Validator
				if(document.myForm.cmbPriority.value==""){
					window.alert('Updating failed. Select the missing asset request priority.'); 			
					return false;
				}
				//Number of items Validator
				if(document.myForm.txtNum.value==""){
					window.alert('Updating failed. Fill in the missing asset requested number.'); 
					return false;
				}
				if(document.myForm.txtNum.value<=0){
					window.alert('Updating failed. Number of assets requested can note be less than or equal to zero.'); 
					return false;
				}
			//code below now send the form after validation is complete
			document.getElementsByClassName("subbyUpdt")[0].click();
			}
		</SCRIPT>
    </head>
    <body >
		<!-- Modal HTML -->
	<div id="myModal" class="modal fade">
		<div class="modal-dialog" style="margin-top:30px;">
			<div class="modal-content">
				<div class="modal-header">
					<img style="height:100px;width:50%;align:center;" src="images\logo.png"  />
				</div>
				<form method="POST" action="request.php" autocomplete="off" name="modForm">
				<div class="modal-body">
					    <input type="text" class="form-control" name="txtSearcKey" placeholder="Enter the request id number here and click the search button"><br>
				</div>
				<div class="modal-footer" style="background-color:orange;" >
					<button name="btnModSearch" class="btn btn-default" type="submit">Search</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				</form>
			</div>
		</div>
	</div>
			<!-- Modal HTML With serach result-->
	<div id="myModal2" class="modal fade">
		<div class="modal-dialog" style="margin-top:30px;">
			<div class="modal-content">
				<div class="modal-header">
					<img style="height:50px;width:50%;" src="images\logo.png"  />
					<h4 class="modal-title" style="color:black; text-align:center;">Request Details Deletion Or Updating</h4>
				</div>
				<form>
				<div class="modal-body">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon3">Request ID<label style="color:white;">..............</label></span>
							<input name="txtID"  disabled type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">						
						</div>
					</div>  
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon3">Asset Name<label style="color:white;">............</label></span>
							<input name="txtAsset"  type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">						
						</div>
					</div>  
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon3">Request Priority<label style="color:white;">.....</label></span>
							<input name="txtPriority"  type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">					
						</div>
				    </div>
					<div class="form-group">
						<label for="txtDetails">Other request details</label>
							<textarea name="txtDetails"  rows="6" class="form-control" style="background:white;color:black;"> 
							</textarea>
					</div>	
				</div>
				<div class="modal-footer" style="background-color:orange;" >
					<button name="btnSearch" class="btn btn-default" type="button">Delete</button>
					<button name="btnSearch" class="btn btn-default" type="button">Update</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	<div class="Container" style="background-color:orange;padding:10px 5px; margin-top:0px;" >

    </div>
	<div class="Container" style="background-color:white;padding:2px 5px;" >
		
    </div>
	<div class="container" style="background-color:white;padding: 5px 25px; margin-top:60px;-moz-border-radius:15px;-webkit-border-radius:15px;">
	<h2 style="text-align: center;color:black;font-weight:bold;">ICT Staff Online Purchases Requisitions</h2>
	<hr>
			<div class="col-lg-4">
				<img src="images\logo.png" style="align:center;-moz-border-radius:15px;-webkit-border-radius:15px;"/>
				<table class="table table-striped  table-hover table-condensed" style="border-radius: 5px;">
					<tr >
						<th><label style="color:orange;">Logged In Staff Name</label></th>
						<td><label><?php echo $alias;?></label></td> 
					</tr>
					<tr >
						<th><label style="color:orange;">Your EC Number</label></th>  
						<td><label><?php echo $ec;?></label></td>
					</tr>
					<tr >
						<th><label style="color:orange;">Your Cell Number</label></th>  
						<td><label><?php echo $phone;?></label></td>
					</tr>
				</table>
	        </div>
		<form method="POST" action="request.php" autocomplete="off" name="myForm">
			<div class="col-lg-4">
				
				<label style="color:white;">.</label>
					    <div class="form-group">
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon3">Asset Name<label style="color:white;">............</label></span>
								<input name="txtAsset" value="<?php echo $_SESSION['Asset'];?>"  type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">						
							</div>
						</div>
					    <div class="form-group">
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon3">Request Priority<label style="color:white;">.....</label></span>
							    <select name="cmbPriority" class="form-control" style="background:white;color:black;">
									<option value="<?php echo $_SESSION['Priority'];?>"><?php echo $_SESSION['Priority'];?></option>
									<option value="Low">Low</option>
									<option value="Medium">Medium</option>
									<option value="High">High</option>
								</select><br>					
							</div>
						</div>
						  <div class="form-group">
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon3">Number Of Assets</span>
								<input name="txtNum" value="<?php echo $_SESSION['Num'];?>" type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3">						
							</div>
						</div>
						<div class="p-2">
							<input type="button" <?php echo $btnCrea;?> value="Submit Request" onclick="myfunc()" class="btn form-control" style="background:black;color:#fff;text-align:center;width:140px;">
							<input style="left:28%;" class="subby" type="submit" name="btnInsert" hidden>
							<input type="button" value="Seach Saved Request" a href="#myModal" data-toggle="modal" class="btnMod" style="background:black;color:#fff;text-align:center;width:197px;height:34px;">
							<br><br>
							<input type="button" value="Update Request" <?php echo $btnUpd;?> onclick="btnUpdtFunc()"  class="btn form-control" style="background:black;color:#fff;text-align:center;width:140px;">
							<input style="left:28%;" class="subbyUpdt" type="submit" name="btnUpdate" hidden>
							<input type="submit" name="btnDelete" value="Delete Request" <?php echo $btnDel;?> class="btn form-control" style="background:black;color:#fff;text-align:center;width:197px;">

							<!--<label><a href="#myModal" role="button" class="btnMod" >Forgot Password: Click Here</a></label> -->
						</div>
						<input style="left:28%;" class="subby" type="submit" name="btnLogin" hidden>
			
			</div>
            <div class="col-lg-4">
				<div class="form-group">
						<label for="txtDetails">Other request details (Optional)</label>
					    <textarea name="txtDetails" rows="6" class="form-control" style="background:white;color:black;"><?php echo $_SESSION['Details'];?></textarea><br>
						<input type="submit" name="btnClear" value="Clear Form" class="btn form-control" style="background:black;color:#fff;text-align:center;width:140px;">
						<input type="submit" name="lgout" value="Logout Of System" class="btn form-control" style="background:black;color:#fff;text-align:center;width:197px;">
						<input name="txtID" hidden value="<?php echo $_SESSION['RequestID'];?>">						

				</div>
			</div>	
		</form>	
	</div>
	<br>
	<div class="container" style="background-color:rgba(0,0,0,0.7);padding: 25px 25px;-moz-border-radius:15px;-webkit-border-radius:15px;">
	<table class="table table-striped table-bordered table-hover table-condensed" style="border-radius: 5px;">
		<tr style="color:orange;background-color:Black;font-size:11pt;font-weight:bold;">
			<th>Request ID</th>
			<th>Reg Date</th>
			<th>Asset Name</th>
			<th>Priority</th>
			<th>Requested No:</th>
			<th>Request State</th>
			<th>Disapproval Reason</th>
			<th>Action</th>
		</tr>
		<!--<tr class="danger" style="font-size:10pt;">-->
	<?php
		$alloe="Allocated";
	   if ($_SESSION['Search']=="Off"){
		   $sql="SELECT * FROM requests WHERE Ec='$ec' && State !='$alloe' ORDER BY RequestID DESC"; 
	   }else{
		    $sef=$_SESSION['RequestID'];
		    $sql="SELECT * FROM requests WHERE RequestID='$sef' && State !='$alloe' ORDER BY RequestID DESC"; 
	   } 
	   $result=mysqli_query($myConn,$sql);
				if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_array($result))	
				{
				if($row['State']=="Approved"){
					$cla= "info";
				}else if($row['State']=="On Purchasing"){
				$cla= "warning";
				}else if($row['State']=="Disapproved"){
				$cla= "success";
				}else{
					$cla= "danger";
				}	
				//""	
					
	?>
			<tr class="<?php echo $cla; ?>" style="font-size:10pt;">				 
				<td><?php echo$row['RequestID'];?></td>
				<td><?php echo$row['RegDat'];?></td>
				<td><?php echo$row['Asset'];?></td>
				<td><?php echo$row['Priority'];?></td>
				<td><?php echo$row['Num'];?></td>
				<td><?php echo$row['State'];?></th>
				<td><?php echo$row['DisCoz'];?></th>
				<td style="text-align:center;"><a href='request.php?id=<?php echo$row['RequestID'];?>' title='View Record'> <span class='glyphicon glyphicon-eye-open'></span></a></td>

	        </tr>
	
	<?php
		}			   
		}
	?>
	</table>
		<hr>
	</div>
	
	<br>
	<div class="Container" style="background-color:black;padding: 50px 25px;margin-top:100px;" >
		<div class="col-lg-4">
		<label style="color:orange;">Ministry Of ICT @2018.All rights reserved.</label>
		</div>
	  
		<div class="col-lg-4">
		
		</div>
		<div class="col-lg-4">
		</div>
    </div>
	  <!--zvang ends-->
            
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
        <!-- //Calendar -->
    </body>	
	
</html>			
