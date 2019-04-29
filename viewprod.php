<?php
	#Call Db Connection
	include('dbconnect.php');
	
	session_start();
	
	$cartAlia=$_SESSION['cartAlia'];
	$cartid=$_SESSION['cartID'];
	$cartphon= $_SESSION['cartPhone'];
	$cartCost=$_SESSION['cartCost'];
	//Logout
	if(isset($_POST['btnBck'])){
		
		header("location: shopping.php");//redirects to another page
	}
	
	//If page accessed without log in
	if(empty($_SESSION['cartAlia'])){
		header("location: home.php");//redirects to another page
	} 
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Set parameters
        $prodID = trim($_GET["id"]);
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
	
</head>
    <body >
	
	
	
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
    <div class="container" style="background-color:#f5f5f5;padding: 0px 0px;width:100%;height:100%;">
		<div class="col-md-3">
			<form method="POST" action="shopping.php" autocomplete="off">
				<div class="col-sm-12">         
					<br>
				</div>
				
				<div class="col-md-12">
				  
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
						<button type="submit" name="btnBck"  class="btn btn-success cart-btn">Back To Shopping Page</button>
					</div>
               
				</div>
			</form>
		</div>
        <div class="col-md-9">  
            
                <div class="col-sm-12">
				
					<div class="col-sm-3">
					<!--show product infor here-->
					<?php 
								$row=mysqli_fetch_array(mysqli_query($myConn,"SELECT * from products WHERE ID='$prodID'"));
								$phd=$row['PharmID'];
								$rowPhar=mysqli_fetch_array(mysqli_query($myConn,"SELECT * from pharmacy WHERE SystID='$phd'"));

								?>
								<label style="color: #535ba0;font-weight:bold;" for="jumb">Product Code:<?php echo $row['ID'];?></label><br>
                                <img src="Products\<?php echo $row['ImgPath'];?>" style="width:100%;height:225px;"/>
                                <hr />
                                <h2 style="color:#424141;font-weight:bold;">$<?php echo $row['Cost'];?></h2>
                                <label style="color:#615c5c;font-weight:bold;">Drug Name: <?php echo $row['Alias'];?></label><br>
								<label style="color:#615c5c;">SI Unit: <?php echo $row['SIUVal']." ".$row['SiUnit'];?></label><br>
					</div>
					<div class="col-sm-9">
								<label style="color: #535ba0;font-weight:bold;" class="pull-left" for="jumb">Pharmacy Location Image</label><br>

								<img src="Products\<?php echo $rowPhar['LocID'];?>" style="width:100%;height:225px;"/>
								<hr>
								<label style="color:#615c5c;font-weight:bold;">Pharmacy Name: <?php echo $row['PharmNam'];?></label><br>
								<label style="color:#615c5c;">Pharmacy Location: <?php echo $row['Location'];?></label>
					</div>
				</div>
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
	