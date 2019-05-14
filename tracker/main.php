<!DOCTYPE html>
<html lang="en">
  <head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, ">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="gear.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  </head>
  <body>
  
<?php
header("Refresh:3");
function printer_counter($statNumber)
{
	$conn=mysqli_connect("","","","");
	if (!$conn) 
	{
		die("Connection failed: " . mysqli_connect_error());
	}
	$datetime = date_default_timezone_set( 'Europe/Istanbul' );	//DateTime istanbula ayarlandı.
	$datetime = date("Y-m-d");				//DateTime Mysql'e göre ayarlandı.
	
	//Sayımların yapılması için variable	
	$counter_printer1 = 0;
	$counter_printer2 = 0;
	$counter_printer1_day = 0;
	$counter_printer2_day = 0;

	$sql_code= "SELECT * FROM Printer WHERE station_number='$statNumber' AND status='prod_stop' ORDER BY printerid DESC";
	$result = mysqli_query($conn, $sql_code);	//Printer tablosundaki tamamlanmış ürünlerin sayımı yapılır.
	$sad = $result;
	if (mysqli_num_rows($result) > 0) 
	{
			// output data of each row
			while($row = mysqli_fetch_assoc($result))
			{
				if ($row['product'] == 'Printer1')
					$counter_printer1++;
				else
					$counter_printer2++;
						
				$sql_date = $row['date'];
				$now_date = substr($sql_date,0,10);
				if($now_date == $datetime)
				{
					if ($row['product'] == 'Printer1')
						$counter_printer1_day++;
					else
						$counter_printer2_day++;
				}	
			}
			$total = $counter_printer1 + $counter_printer2 ;
			$total_day = $counter_printer1_day + $counter_printer2_day ;
	}
	if($statNumber == 5)	//Eğer istasyon 5 ise Tema farklı olur.
	{
		echo '
		<div class = "row">
			<div class = "col-md-6">
				<div class="alert alert-warning" role="alert" style="font-size:16px; margin-top:-15px; height:80px;">
					<p style="margin-top:-10px;">
					Üretilen <b>X1</b> 3d Printer Sayısı : <span class="badge badge-dark"> '.$counter_printer1.' </span>
					<br>
  					Üretilen <b>X1 Plus</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$counter_printer2.'</span>
					<br>
  					Üretilen <b>Toplam</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$total.'</span> 
					</p>
				</div>
			</div>
			<div class = "col-md-6">
				<div class="alert alert-info" role="alert" style="font-size:16px; margin-top:-15px;  height:80px;">
					<p style="margin-top:-10px;">
					Gün İçerisinde Üretilen <b>X1</b> 3d Printer Sayısı : <span class="badge badge-dark"><strong>'.$counter_printer1_day.'</strong></span>
					<br>
					Gün İçerisinde Üretilen <b>X1 Plus</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$counter_printer2_day.'</span>
					<br> Gün İçerisinde Üretilen <b>Toplam</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$total_day.'</span>
					</p>
				</div>
			</div>
		</div>
			';
	}
	else
	{
		echo '
		<div >
			<div class="alert alert-warning" role="alert" style="font-size:16px; margin-top:-15px;">
				Üretilen <b>X1</b> 3d Printer Sayısı : <span class="badge badge-dark"> '.$counter_printer1.' </span>
				<br>
  				Üretilen <b>X1 Plus</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$counter_printer2.'</span>
				<br>
  				Üretilen <b>Toplam</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$total.'</span> 
			</div>
			<div class="alert alert-info" role="alert" style="font-size:16px; margin-top:-15px;">
				Gün İçerisinde Üretilen <b>X1</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$counter_printer1_day.'</span>
				<br>
				Gün İçerisinde Üretilen <b>X1 Plus</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$counter_printer2_day.'</span>
				<br> Gün İçerisinde Üretilen <b>Toplam</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$total_day.'</span>
			</div>
		</div>';
	}
	
mysqli_close($conn);
}

function station_name($statName,$status)
{
	$conn=mysqli_connect("localhost","phpmyadmin","123456","root");
	if (!$conn) 
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	$sql= "SELECT * FROM PrinterProduction WHERE station_number='$statName' AND status='$status' ORDER BY production_id DESC";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) 
	{
		
		// output data of each row
		while($row = mysqli_fetch_assoc($result))
		{
			
			if($row['status'] == 'prod_start')
			{
				$id = "gear";
				$opacity = 0.7;
			}
			else
			{
				$id = "tick";
				$opacity = 1;
			}	
				
			if($row['product_name'] == 'X1')
			{
				if($statName == 5)
				{
					echo '
							<img src="zaxe-x1.png" alt="Nature" style="width:11%; margin-top:-10px;  opacity:'.$opacity.';">';
				}
			
				else {
					echo '<div class = "row">
				      		<div class="col-md-4">
							<img src="zaxe-x1.png" alt="Nature" style="width:43%; float:right; opacity:'.$opacity.';">
				      		</div>
				      		<div class="col-md-4">
							<div style="margin-top:20px; font-size:17px; font-family:Helvetica;"><b>'.$row['product_name'].'</b> <br></div>
				     		</div>
						<div class="col-md-4" >
							<div class="gear" style = "margin-top:-40px; "  id='.$id.'></div>
				     		 </div>
				      	      </div>		
				      	      <br>';
				}
			}
			else
			{
				if($statName == 5)
				{
					echo '
							<img src="zaxe-x1plus.png" alt="Nature" style="width:10%; margin-top:-10px; opacity:'.$opacity.';">';
				}
				else	
				{
					echo '<div class = "row">
					      	<div class="col-md-4">
							<img src="zaxe-x1plus.png" alt="Nature" style="width:40%; float:right; opacity:'.$opacity.'">

					      	</div>
					      	<div class="col-md-4">
							<div style="margin-top:15px; font-size:15px; font-family:Helvetica;"><b>'.$row['product_name'].'</b> <br></div>
					     	</div>
							<div class="col-md-4" >
							<div class="gear" style = "margin-top:-40px; "  id='.$id.'></div>
			     			 </div>
			        	      </div>
					      <br>';
				}
				

			}
		}
	}
	else
	{
		echo '<center>Şuanda Ürün Bulunamamaktadır.</center>';
	}
mysqli_close($conn);
}

function paketleme()	//Depo ve kargodaki Printer sayılarını bulmak için kullanılır.
	{
		$kalan_x1 = 0;
		$kalan_x1_plus = 0;
		$kargoda_x1 = 0;
		$kargoda_x1_plus = 0;
		$conn=mysqli_connect("localhost","phpmyadmin","123456","root");
		if (!$conn) 
		{
			die("Connection failed: " . mysqli_connect_error());
		}
	
		$sql= "SELECT * FROM PrinterProduction WHERE status='prod_stop' ORDER BY production_id DESC";
		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
					if($row['station_number'] == 5 )
					{
						if($row['product_name'] == 'X1')
							$kalan_x1++;
						else
							$kalan_x1_plus++;
					}
					else if($row['station_number'] == 0)
					{
						if($row['product_name'] == 'X1')
							$kargoda_x1++;
						else
							$kargoda_x1_plus++;
					}
					
			}
		}
		echo '
				<div class="alert alert-danger" role="alert" style="font-size:15px; margin-top:-15px; height:90px;">
					<p style="margin-top:-10px;">
					Kargolanan <b>X1</b> 3d Printer Sayısı : <span class="badge badge-dark"> '.$kargoda_x1.' </span>
					<br>
  					Kargolanan <b>X1 Plus</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$kargoda_x1_plus.'</span>
					<br>
  					Depodaki <b>X1</b> 3d Printer Sayısı : <span class="badge badge-dark"><strong>'.$kalan_x1.'</strong></span> 
					<br>
  					Depodaki <b>X1 Plus</b> 3d Printer Sayısı : <span class="badge badge-dark">'.$kalan_x1_plus.'</span>
					
					</p>
					<br>
				</div>
		';
	}

?>
  <div class="container-fluid">

    <div class="row">
	<div class="col-md-3">
		<div class="alert alert-info" style="background: url('stationphoto1.jpg'); font-size:30px; ">	
			<font color="white"><center><strong>STATION 1</strong></font>
		</div>
		<div class="well" style = "height:600px;  overflow: auto; margin-top:-10px;">
   			<h5 style = "margin-top:-14px; margin-bottom:-5px;"><center><strong><u>ÜRETİM</u></strong></center> </h5> 
			<br>
			<?php station_name(1,'prod_start'); ?><br>
			<h5 style = "margin-top:-5px; margin-bottom:-3px;"><center><b><u>TAMAMLANDI</u></b></center></h5>
			<br>
			<?php station_name(1,'prod_stop'); ?>

  		</div>
		<div>
			<?php printer_counter('1'); ?><br>
		</div>
	</div>

	<div class="col-md-3">
		<div class="alert alert-info" style="background: url('stationphoto2.jpg'); font-size:30px; ">	
			<font color="white"><center><strong>STATION 2</strong></font>
		</div>
		<div class="well" style = "height:600px;  overflow: auto; margin-top:-10px;">
   			<h5 style = "margin-top:-14px; margin-bottom:-5px;"><center><strong><u>ÜRETİM</u></strong></center> </h5> 
			<br>
			<?php station_name(2,'prod_start'); ?><br>
			<h5 style = "margin-top:-5px; margin-bottom:-3px;"><center><b><u>TAMAMLANDI</u></b></center></h5>
			<br>
			<?php station_name(2,'prod_stop'); ?>
  		</div>
		<div>
			<?php printer_counter('2'); ?><br>
		</div>
	</div>

	<div class="col-md-3">
		<div class="alert alert-info" style="background: url('stationphoto3.jpg'); font-size:30px; ">	
			<font color="white"><center><strong>STATION 3</strong></font>
		</div>
		<div class="well" style = "height:600px;  overflow: auto; margin-top:-10px;">
   			<h5 style = "margin-top:-14px; margin-bottom:-5px;"><center><strong><u>ÜRETİM</u></strong></center> </h5> 
			<br>
			<?php station_name(3,'prod_start'); ?><br>
			<h5 style = "margin-top:-5px; margin-bottom:-3px;"><center><b><u>TAMAMLANDI</u></b></center></h5>
			<br>
			<?php station_name(3,'prod_stop'); ?>
  		</div>
		<div>
			<?php printer_counter('3'); ?><br>
		</div>
	</div>

	<div class="col-md-3">
		<div class="alert alert-info" style="background: url('stationphoto4.jpg'); font-size:30px;">	
			<font color="white"><center><strong>TEST STATION</strong></font>
		</div>
		<div class="well" style = "height:600px;  overflow: auto; margin-top:-10px;">
   			<h5 style = "margin-top:-14px; margin-bottom:-5px;"><center><strong><u>ÜRETİM</u></strong></center> </h5> 
			<br>
			<?php station_name(4,'prod_start'); ?><br>
			<h5 style = "margin-top:-5px; margin-bottom:-3px;"><center><b><u>TAMAMLANDI</u></b></center></h5>
			<br>
			<?php station_name(4,'prod_stop'); ?>
  		</div>
		<div>
			<?php printer_counter('4'); ?><br>
		</div>
	</div>
    </div>
    <div class="row">
	<div class="col-md-1">
		<div class="alert alert-info" style="height:180px;  background: url('stationphoto5.jpg'); font-size:25px; margin-top:-30px; max-width:150px;">	
			<p style= "-webkit-transform: rotate(270deg); margin-top:80px;"><font color="white"><strong>PAKETLEME</strong></font></p>
		</div>
	</div>
	<div class="col-md-11">
		<div class="well well-lg" style = "weight:100%;  overflow: auto; margin-top:-30px; height:100px;">
			<div class="col-md-4">
				<h5 style = "margin-top:-14px; margin-left:10px; float:left"><center><strong><u>PAKETLEMEDE</u></strong></center> </h5> 
				<br>
				<?php station_name(5,'prod_start'); ?>
			</div>
			<div class="col-md-4" >
				<div class="progress" style="margin-top:20px; margin-right:70px;">
    					<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:100%">
					
    					</div>
  				</div>
			</div>
			<div class="col-md-4">
				<h5 style = "margin-top:-14px; margin-left:10px; float:left"><center><strong><u>TAMAMLANDI</u></strong></center> </h5> 
				<br>
				<?php station_name(5,'prod_stop'); ?>
				
			</div>
			
  		</div>
	<div class="col-md-8" style = "margin-left:-15px;">
			<?php printer_counter('5'); ?><br>

	</div>
	<div class = "col-md-4"  ">
			
			<?php paketleme(); ?> <br>
	</div>
	
	</div>
    </div>
  </div>
   
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>
