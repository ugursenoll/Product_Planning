<?php
//delete.php
$conn=mysqli_connect("","","","");
if(isset($_POST["id"]))
{
 foreach($_POST["id"] as $id)
 {
  $sql_code = "DELETE FROM Printer WHERE printerid = '".$id."'";
  mysqli_query($conn, $sql_code);
 }
}
?>
