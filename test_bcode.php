 <html>
 <meta charset="utf-8" />
 <!-- test page. Hello world! /-->
   <head>
     <title>Test page. Hello World!</title>
     	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
 <?php
 	$tbname = 'tb_test';
   /* php test */
   echo "Barcode Test Page.\n\r<br />\n\r";
   if ($_GET['barcode']) {
     echo "barcode is ".$_GET['barcode']."<br />\n";
   } else {
     echo "barcode is null.<br />\n";
   }
   echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br         /><br />\n";
   echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br         /><br />Check Scroll bar<br />\n";
 ?>
 <a href="./index.php?tbname=<?=$tbname?>" class="btnList btn">목록추가</a>
 <a href="./index.php?tbname=<?=$tbname?>" class="btnList btn">입고</a>
 <a href="./index.php?tbname=<?=$tbname?>" class="btnList btn">출고</a>
 
 </body>
 </html>