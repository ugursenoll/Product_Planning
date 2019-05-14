<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bare - Start Bootstrap Template</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style>
      body {
        padding-top: 4px;
      }
      @media (min-width: 992px) {
        body {
          padding-top: 6px;
        }
      }
	
	.center {
   	 display: block;
   	 margin-left: auto;
   	 margin-right: auto;
   	 width: 50%;
}

    </style>

  </head>

  <body>￼

<?php
	function station_name($statName){
		$conn=mysqli_connect("localhost","phpmyadmin","123456","root");
		if (!$conn) 
		{
			die("Connection failed: " . mysqli_connect_error());
		}
		$datex = date_default_timezone_set( 'Europe/Istanbul' );	//DateTime istanbula ayarlandı.
		$datex = date("Y-m-d");				//DateTime Mysql'e göre ayarlandı.

		$sql= "SELECT * FROM Printer WHERE name='$statName' ORDER BY printerid DESC";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			// output data of each row
			while($row = mysqli_fetch_assoc($result))
			{				
				$status = $row['status'];
				switch($status) {
					case 'prod_start':
						$status = 'starting';
						break;
					case 'prod_stop':
						$status = 'stopped';
						if ($row['product'] == 'X1')
							$sayac_x1++;
						else
							$sayac_x1plus++;
						
						$date1 = $row['date'];
						$date2 = substr($date1,0,10);
						if($date2 == $datex)
							if ($row['product'] == 'X1')
								$sayac_x1_day = $sayac_x1_day + 1;
							else
								$sayac_x1plus_day = $sayac_x1plus_day +1;
						break;
					case 'prod_mola':
						$status = 'Mola Verildi.';
						break;
					case 'prod_parca':
						$status = 'Yazıcı Üretimi İçin Yeteri Kadar Parça Bulunmadı.';
						break;
					case 'prod_ariza':
						$status = 'Arızalı Makineyle İlgileniliyor.';
						break;
					case 'prod_teknik':
						$status = 'Teknik İşler Yapılıyor(Üretime Ön Hazırlık vb).';
						break;
					case 'prod_paketleme':
						$status = 'Kargoya Verildi.';
						break;
				
				}
				echo '<div class ="container">
					<div class = "row">
						<div class = "col-md-12 col-sm-6">
						<p><center>'.$row['name'].' <b>'.$row['product'].'</b> '.$row['date'].' <font color="lightgreen">'.$status.'</font></center></p>
						</div>	
					</div>
				      </div>';	

			}
		} 
		else 
		{
			echo "0 results";
		}
		echo '  </div>
    		</div>
		<hr>
	      </div>';
	}
?>

   <div class="container-fluid">
      <div class="row">

	<div class="col-md-3">
        	<div class="thumbnail">
       		   <img src="image.png" alt="Nature" style="width:70%" class="center">
		<hr>
       		   <div class="caption" style="overflow:scroll; height:500px; background:#f5f2f0; font-size:12px; width:95%">
	<?php
		station_name('1');
	?>

	<div class="col-md-3">
        	<div class="thumbnail">
       		   <img src="image2.png" alt="Nature" style="width:70%" class="center">
		<hr>
       		   <div class="caption" style="overflow:scroll; height:500px; background:#f5f2f0; font-size:12px; width:95%">
	<?php
		station_name('2');
	?>
  

	<div class="col-md-3">
        	<div class="thumbnail">
       		   <img src="image3.png" alt="Nature" style="width:70%" class="center">
  		<hr>
       		   <div class="caption" style="overflow:scroll; height:500px; background:#f5f2f0; font-size:12px; width:95%;">
	
	<?php
		station_name('3');
	?>

	<div class="col-md-3">
        	<div class="thumbnail">
       		   <img src="image4.png" alt="Nature" style="width:70%" class="center">
  		<hr>
       		   <div class="caption" style="overflow:scroll; height:500px; background:#f5f2f0; font-size:12px; width:95%;">
	
	<?php
		station_name('4');
	?>
		

	</div>
	
      </div>
      <div class="row">
	<div class="col-md-5">
	</div>
	<div class="col-md-3" style="margin-left:30px">
		<a href="stationadmin.php" class="btn btn-info" role="button">Station Admin</a>
		<a href="logadmin.php" class="btn btn-info" role="button">Log Admin</a>
	</div>
	
      </div>
    </div>
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>
