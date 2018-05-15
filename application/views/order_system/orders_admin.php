<!DOCTYPE html>
<head>
  	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    	<title>store | Orders (Admin)</title>
    	<link rel="stylesheet" href="<?php echo base_url("css/foundation.min.css"); ?>" />
    	<script src="<?php echo base_url("js/vendor/modernizr.js"); ?>"></script>
    	<script src="<?php echo base_url("js/vendor/jquery.js"); ?>"></script>
</head>
  <body>
	<div class="main-section">
		<div class="contain-to-grid sticky">
		  <nav class="top-bar" data-topbar>
			  <ul class="title-area">
			    <!-- <li class="name"> -->
			      <!-- <h1><a href="index">store</a></h1> -->
			       <?php
			      	echo "<li class='name'><h1>" . anchor('store/index', 'store') . "</li><h1>";
			      ?>
			    <!-- </li> -->
			    <li class="toggle-topbar menu-icon"><a href="#"></a></li>
			  </ul>

			  <section class="top-bar-section">

			    <!-- Left Nav Section -->
			    <ul class="left">
			      <?php
			      	echo "<li>" . anchor('store/index', 'Home') . "</li>";

			      	echo "<li>" . anchor('ordercontroller/loadOrders', 'Browse Orders') . "</li>";

			      	echo "<li>" . anchor('logincontroller/loadCustomers', 'Browse Customers') . "</li>";
			      ?>
			    </ul>

			    <!-- Right Nav Section -->
			    <ul class="right">
			      <!-- <li><a href="#">Shopping Cart</a></li> -->
			      <li>
			      <?php
			      	if($this->session->userdata('loggedIn')) {
			      		echo "<li> <a href=\"#\"> Welcome, " . $this->session->userdata('username') . "</a></li>";
			      		echo "<li class=\"has-form\">" . anchor('store/logOut', 'Logout', 'class="button alert"') . "</li>";
			      	}

			      ?>
			    </li>
			    </ul>
			  </section>
		  </nav>
		</div>

		<div class="row">
			<!-- Code to display orders in a grid format -->
			<ul class="pricing-table">
			  <li class="title">All orders</li>
			  <li class="description">Feel free to browse/delete orders</li>

			  <li class="bullet-item">
				  <ul class="small-block-grid-5 medium-block-grid-5 large-block-grid-5">
				  	<li><h6 style="color: SlateGray"> Customer No. </h6></li>
				  	<li><h6 style="color: SlateGray"> Order placed </h6></li>
				  	<li><h6 style="color: SlateGray"> Total Amount </h6></li>
				  	<li><h6 style="color: SlateGray"> Credit Card Number (Valid Until) </h6></li>
				  	<li><h6 style="color: SlateGray"> Remove </h6></li>
				  </ul>


			  </li>
			  <?php
			  	foreach($orders as $order) {
			  		echo "<li class=\"bullet-item\">";

			  		echo "<ul class=\"small-block-grid-5 medium-block-grid-5 large-block-grid-5\">";
			  			echo "<li><h6>" . $order->customer_id . "</h6></li>";
			  			echo "<li><h6>" . $order->order_date . " / " . $order->order_time . "</h6></li>";
			  			echo "<li><h6>" . $order->total . "</h6></li>";
			  			echo "<li><h6>" . 'XXXX-XXXX-XXXX-X' . substr(strval($order->creditcard_number), -3) .
			  						"(" . $order->creditcard_month . "/" . $order->creditcard_year . ")" . "</h6></li>";
			  			echo "<li>" . anchor("ordercontroller/deleteOrder/$order->id", '&#215;', 'class="button alert"') . " </li>";
			  		echo "</ul>";

			  		echo "</li>";
			  	}


			  ?>

			  <li class="cta-button">
			  	<?php
					echo "<li>" . anchor("ordercontroller/deleteAllOrders", 'Delete all orders', 'class="button alert right"') . " </li>";
			  	?>
			  </li>

			</ul>
		</div>

	</div>

      	<script src="<?php echo base_url("js/foundation.min.js"); ?>"></script>
      	<script>
      	 	$(document).foundation();
        	</script>
  </body>