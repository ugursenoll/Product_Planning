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
	$conn=mysqli_connect("","","","");
	if (!$conn) 
	{
		die("Connection failed: " . mysqli_connect_error());
	}
	$sql_code= "SELECT * FROM Printer ORDER BY date DESC";
	$result = mysqli_query($conn, $sql_code);
	?>
	<div class="container">
		<br/>
		<h3 align="center">Printer Log And Counter Status</h3>
		<?php
			if (mysqli_num_rows($result) > 0) 
			{
		?>
		<div class="table-responsive">
				<table class="table table-bordered">
				    <tr>
					<th>Station Name</th>
					<th>Production Name</th>
					<th>Date</th>
					<th>Status</th>
					<th>Delete</th>
				    </tr>
		<?php
				while($row = mysqli_fetch_assoc($result))
				{
									
		?>
				<tr id="<?php echo $row["printerid"]; ?>">
					<td> <?php echo $row["name"]; ?></td>
					<td> <?php echo $row["product"]; ?></td>
					<td> <?php echo $row["date"]; ?></td>
					<td> <?php echo $row["status"]; ?></td>
					<td><input type="checkbox" name="printer_id[]" class="delete_customer" value="<?php echo $row["printerid"]; ?>" /></td> 
				</tr>
		<?php	
								
				}
		?>
				</table>
		</div>
		<?php
			}
		?>
		<div align="center">
   		 <button type="button" name="btn_delete" id="btn_delete" class="btn btn-success">Delete</button>
   		</div>
		<br />
	
	
  	

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>

<script>
$(document).ready(function(){
 
 $('#btn_delete').click(function(){
  
  if(confirm("Are you sure you want to delete this?"))
  {
   var id = [];
   
   $(':checkbox:checked').each(function(i){
    id[i] = $(this).val();
   });
   
   if(id.length === 0) //tell you if the array is empty
   {
    alert("Please Select atleast one checkbox");
   }
   else
   {
    $.ajax({
     url:'logdelete.php',
     method:'POST',
     data:{id:id},
     success:function()
     {
      for(var i=0; i<id.length; i++)
      {
       $('tr#'+id[i]+'').css('background-color', '#ccc');
       $('tr#'+id[i]+'').fadeOut('slow');
      }
     }
     
    });
   }
   
  }
  else
  {
   return false;
  }
 });
 
});
</script>
