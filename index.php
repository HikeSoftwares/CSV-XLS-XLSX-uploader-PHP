<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'excelLibrary/PHPExcel/IOFactory.php';

if(!empty($_FILES["employee_file"]["name"]))  
 { 
	$batch_id = date('Ymdhis');  

	///////moving uploaded file on server
	$path = 'uploads/'.date("Y-m-d");
	$file = $path."/".$batch_id."_".basename($_FILES['employee_file']['name']);
	$allowed_ext = array("xlsx", "XLSX", "csv", "CSV", "xls", "XLS");  
	$t = explode(".", $_FILES["employee_file"]["name"]);
	$extension = end($t);  
	mkpath($path);
	$x = move_uploaded_file($_FILES['employee_file']['tmp_name'], $file);
	if ($x) { $response['fileUploaded'] = "yes"; } else { $response['fileUploaded'] = "no"; }
	///////moving uploaded file on server

	if(in_array($extension, $allowed_ext))  
      {
		    //  Read your Excel workbook
			try
			{
				$inputfiletype = PHPExcel_IOFactory::identify($file);
				$objReader = PHPExcel_IOFactory::createReader($inputfiletype);
				$objPHPExcel = $objReader->load($file);
			}
			catch(Exception $e)
			{
				$msg['result']=3;
				$msg['errormsg']="XLSX file can't be read.";
				echo json_encode($msg);
			}
			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();

			//  Loop through each row of the worksheet in turn
			for ($rowTmp = 1; $rowTmp <= $highestRow; $rowTmp++)
			{ 
				//  Read a row of data into an array
				$rowData = $sheet->rangeToArray('A' . $rowTmp . ':' . $highestColumn . $rowTmp, NULL, TRUE, FALSE);
				$col_1 = $rowData[0][1];
				$col_2 = $rowData[0][2];
				$col_3 = $rowData[0][3];
				$col_4 = $rowData[0][4];
				$col_5 = $rowData[0][5];
				$col_6 = $rowData[0][6];
				$col_7 = $rowData[0][7];               
			}
	  }
      else{
			$msg['result']=0;
			$msg['errormsg']='File Format Not Allowed';
			echo json_encode($msg);
		} 
}
else{
	  $msg['result']=1;
	  $msg['errormsg']='File Format Not Found';
	  echo json_encode($msg);
  } 

function mkpath($path)
{
	if(@mkdir($path) or file_exists($path)) return true;
	return (mkpath(dirname($path)) and mkdir($path));
}		  
		  
?>