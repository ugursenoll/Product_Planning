<?php
	
	$name = $_POST["name"];
	$product = $_POST["product"];
	$status = $_POST["status"];
	$station_number = $_POST["station_number"];
	$product_count = $_POST["product_count"];
	$date = date_default_timezone_set( 'Europe/Istanbul' );	//DateTime istanbula ayarlandı.
	$date = date("Y-m-d H:i:s");				//DateTime Mysql'e göre ayarlandı.
	
	$conn=mysqli_connect("","","","");
	if($conn)		
	{
		
		$new_station_number = $station_number - 1;    //Bir önceki istasyonda printer olup olmadığını anlamak için bir önceki istasyona bakılıcak.
		$sqli = "SELECT COUNT(*) as total FROM PrinterProduction WHERE station_number = $new_station_number AND product_name = '".$product."' AND status = 'prod_stop' ";
		$result = mysqli_query($conn, $sqli);
		$data=mysqli_fetch_assoc($result);	//Bir önceki istasyondaki printer sayısı bulunuyor.

		$sql2 = "SELECT COUNT(*) as total2 FROM PrinterProduction WHERE station_number = $station_number AND product_name = '".$product."' AND status = 'prod_start' ";
		$result1 = mysqli_query($conn, $sql2);
		$data2=mysqli_fetch_assoc($result1);	//Şuanki istasyonda üretimde olan ürün sayısı bulunuyor.
	
		if($data2['total2'] != 0)	//Eğer bir önceki istasyonda printer var ise üretime başlanabilir.
			$data['total'] = 1;
		else if ($station_number == 1)	//Eğer istasyon numarası 1 ise bir önceki istasyon olamayacağı için üretime başlanabilir.
			$data['total'] = 1;
		else if($status != 'prod_start' && $status != 'prod_stop')	//Eğer istasyonlar duraklat butonunu kullanacak ise üretim olamaz kullanabilir.
			$data['total'] = 1;
		else if($data['total'] < $product_count)	//Bir önceki istasyondaki printer sayısı şuan üretilecek printer sayısından küçük ise üretim olmaz.
			$data['total'] = 0;
		if($data['total'] != 0)		//Bir önceki istasyon printer üretmemiş ise üretime başlanamaz.
		{
			$sql_Printer = "insert into Printer(name , product , status , date ,station_number)
			values ('".$name."' , '".$product."' , '".$status."' , '".$date."' , '".$station_number."')";
			
			if(mysqli_query($conn, $sql_Printer))
			{
				echo "Uretime Baslandi.";
			}
			else
			{
				echo "Error: ";
			}
		}
		else
			echo "Bir Önceki İstasyonda Ürün Yok.";
		

		switch($station_number)	//Printerların bir önceki istasyondan diğerine aktarılması için yazılmıştır.
		{
			case 1:
				if($status == 'prod_start'){
					$sql_code = "insert into PrinterProduction(station_number , product_name , status , date) values ('".$station_number."' , '".$product."' , '".$status."' , '".$date."')";	//Station 1 üretime başlar.
				}
				else{
					$sql_code = "UPDATE PrinterProduction SET status = 'prod_stop' , date='".$date."' WHERE station_number=1 AND product_name = '".$product."' AND status = 'prod_start' Limit 1";	//Station 1 üretimi bitirirse üretimdeki printer tamamlandı bloğuna geçer.
					
				}
				mysqli_query($conn, $sql_code);
			break;
			case 2:
				$sqli = "SELECT COUNT(*) as total FROM PrinterProduction WHERE station_number = 1 AND product_name = '".$product."' AND status = 'prod_stop' ";
				$result = mysqli_query($conn, $sqli);
				$data=mysqli_fetch_assoc($result);
				
				if($status == 'prod_start'){
					$sql_code = "UPDATE PrinterProduction SET station_number=2, status = 'prod_start' , date='".$date."' WHERE station_number=1 AND product_name = '".$product."' AND status = 'prod_stop' Limit 1";	//Station 2 üretime başlarken Station 1 de tamamlanmış olan ürüne kendi sekmesinde üretim bloğuna koyar.
				}
				else{
					$sql_code = "UPDATE PrinterProduction SET status = 'prod_stop' , date='".$date."' WHERE station_number=2 AND product_name = '".$product."' AND status = 'prod_start' Limit 1";	//Station 2 üretimi bitirirse üretimdeki printer tamamlandı bloğuna geçer.
				}
				mysqli_query($conn, $sql_code);
				
			break;
			case 3:
				
				if($status == 'prod_start'){
					$sql_code = "UPDATE PrinterProduction SET station_number=3, status = 'prod_start' , date='".$date."' WHERE station_number=2 AND product_name = '".$product."' AND status = 'prod_stop' Limit 1";
				}
				else{
					$sql_code = "UPDATE PrinterProduction SET status = 'prod_stop' , date='".$date."' WHERE station_number=3 AND product_name = '".$product."' AND status = 'prod_start' Limit 1";
				}
				mysqli_query($conn, $sql_code);
			
			break;
			case 4:
				
				if($status == 'prod_start'){
					$sql_code = "UPDATE PrinterProduction SET station_number=4, status = 'prod_start' , date='".$date."' WHERE station_number=3 AND product_name = '".$product."' AND status = 'prod_stop' Limit 1";
				}
				else{
					$sql_code = "UPDATE PrinterProduction SET status = 'prod_stop' , date='".$date."' WHERE station_number=4 AND product_name = '".$product."' AND status = 'prod_start' Limit 1";
				}
				mysqli_query($conn, $sql_code);
				
			break;
			case 5:
				
				if($status == 'prod_start'){
					$sql_code = "UPDATE PrinterProduction SET station_number=5, status = 'prod_start' , date='".$date."' WHERE station_number=4 AND product_name = '".$product."' AND status = 'prod_stop' Limit 1";
				}
				else if($status == 'prod_stop'){
			
					$sql_code = "UPDATE PrinterProduction SET status = 'prod_stop' , date='".$date."' WHERE station_number=5 AND product_name = '".$product."' AND status = 'prod_start' Limit 1";
				}
				else if($status == 'prod_consiye'){
					$sql_code = "UPDATE PrinterProduction SET status == 'prod_consiye', date='".$date."' WHERE station_number=5 AND product_name = '".$product."' AND status = 'prod_stop' Limit 1";
				}
				else
				{
					$sql_code = "UPDATE PrinterProduction SET station_number=0, date='".$date."' WHERE station_number=5 AND product_name = '".$product."' AND status = 'prod_stop' Limit 1";
				}
				
				mysqli_query($conn, $sql_code);
				
			break;
		}

		
		mysqli_close($conn);
	}
	else
		echo "baglanmadi.";
		


?>
