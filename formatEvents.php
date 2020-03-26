<?php
	//Get the Event data from the server.
	//http://localhost/wdv341/formatEvents/formatEvents.php
	
	
//Hard code current date to demonstrate formatting of 
//database records
$today = strtotime("now");
$today = strtotime("10/1/2018");

$totalQuantityEvents = 0;


	require 'dbConnect.php';
		try {
			$stmt = $conn->prepare("SELECT 
				COUNT(*) as rows 
				FROM wdv341_event") ;
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);

			$result = $stmt->fetch();  // associative array
			$totalQuantityEvents = $result["rows"];
			if( $result["rows"] > 0 ){ 
				$stmt = $conn->prepare("SELECT 
					event_id,
					event_description,
					event_presenter,
					event_date,
					DATE_FORMAT(event_date, '%M %d %Y') as formated_date,
					event_time
					FROM wdv341_event 
					order by event_date DESC
					");
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				
				$result = $stmt->fetchAll();  // associative array
			}else{
				$result =[];
			}
			
		}
		catch(PDOException $e) {
			$result =[];
		}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>WDV341 Intro PHP  - Display Events Example</title>
    <style>
		.eventBlock{
			width:500px;
			margin-left:auto;
			margin-right:auto;
			background-color:#CCC;	
		}
		
		.displayEvent{
			text_align:left;
			font-size:18px;	
		}
		
		.displayDescription {
			margin-left:100px;
		}
		.samemonth{color:red;font-weight: bold;}
		.futureevent{font-style: italic;}
	</style>
</head>

<body>
    <h1>WDV341 Intro PHP</h1>
    <h2>Example Code - Display Events as formatted output blocks</h2>   
    <h3><?php echo "$totalQuantityEvents Events are available today.";?></h3>
	<h4>Today is <?php echo date( "M d, Y",$today); ?></h4>

	<?php
		//Display each row as formatted output in the div below
	foreach($result as $record)		
	 {
		$date = strtotime( $record['event_date'] );
		$descript_modifier = "";

		if ( $date > $today ) {
		  $descript_modifier .= " futureevent ";
		}
		if ( date("my", $date) == date("my", $today) ) {
		  $descript_modifier .= " samemonth ";
		}
		
		
		echo "<p>
			<div class='eventBlock'>	
				<div>
					<span class='displayEvent'>Event:" . $record['event_id'] . "</span>
					<span>Presenter:" . $record['event_presenter'] . "</span>
				</div>
				<div>
					<span class='displayDescription " . $descript_modifier . "'>Description:" . $record['event_description'] . "</span>
				</div>
				<div>
					<span class='displayTime'>Time:" . $record['event_time'] . "</span>
				</div>
				<div>
					<span class='displayDate'>Date:" . $record['formated_date'] . "</span>
				</div>
			</div>
		</p>";
	} 

	$conn = null;
	?>
</div>	
</body>
</html>