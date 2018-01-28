<?php
 
	$serverName = "localhost";
	$userName = "xxxxx";
	$userPassword = "xxxxx";
	$dbName = "xxxxx";

	$objCon = mysqli_connect($serverName,$userName,$userPassword,$dbName);

	mysqli_set_charset($objCon, "utf8");

	$strSQL = "SELECT c.bibid, c.copyid, m.mbrid, c.barcode_nmbr, CONCAT_WS(  ' ', b.call_nmbr1, b.call_nmbr2, b.call_nmbr3 ) AS callno, b.title, b.author, c.status_begin_dt, c.due_back_dt, m.barcode_nmbr member_bcode, CONCAT( m.last_name,  ', ', m.first_name ) name, FLOOR( TO_DAYS( NOW( ) ) - TO_DAYS( c.due_back_dt ) ) days_late
FROM biblio b, biblio_copy c, member m
WHERE b.bibid = c.bibid
AND c.mbrid = m.mbrid
AND c.status_cd =  'out'
AND FLOOR( TO_DAYS( NOW( ) ) - TO_DAYS( c.due_back_dt ) ) = '0'";

	$objQuery = mysqli_query($objCon,$strSQL);
	$da = date("Y-m-d");
$i=1;
$rowcount=mysqli_num_rows($objQuery );
if($rowcount > 0){
	while($objResult = mysqli_fetch_array($objQuery,MYSQLI_ASSOC))
	{
		$text = "วันที่ ".$da."  ".$i.". ชื่อ ".$objResult["name"]. " รหัสพนักงาน ".$objResult["member_bcode"]." ครบกำหนดคืนหนังสือชื่อ ". $objResult["title"] ."  ";   

$message = $text;
$token = 'ใส่เลข token ';
echo send_line_notify($message, $token);

$i++;
	}

}else {  

$text = "วันที่ ".$da." ไม่พบหนังสือที่ครบกำหนดคืน";   
$message = $text;
$token = 'ใส่เลข token ';
echo send_line_notify($message, $token);

}


function send_line_notify($message, $token)
{ $ch = curl_init(); curl_setopt( $ch, CURLOPT_URL, "https://notify-api.line.me/api/notify"); curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0); curl_setopt( $ch, CURLOPT_POST, 1); curl_setopt( $ch, CURLOPT_POSTFIELDS, "message=$message"); curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1); $headers = array( "Content-type: application/x-www-form-urlencoded", "Authorization: Bearer $token", ); curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1); $result = curl_exec( $ch ); curl_close( $ch ); return $result;
}

	?>	
	

