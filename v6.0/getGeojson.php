<?php

// get ajax request 
// month ,day, time


$month = intval($_POST['month']);
$day = intval($_POST['day']);
$time = $_POST['time'];

/*
echo $month;
echo gettype($month);
echo $day;
echo gettype($day);
echo $time;
echo gettype($time);
*/


if ($time=='dayAll'){
  $timeStart='06:00:00';
  $timeEnd='18:00:00';
}elseif($time=='morning'){
  $timeStart='06:00:00';
  $timeEnd='10:00:00';
}elseif($time=='noon'){
  $timeStart='10:00:00';
  $timeEnd='14:00:00';
}elseif($time=='afternoon'){
  $timeStart='14:00:00';
  $timeEnd='18:00:00';
}



$db_servername = "127.0.0.1";     //localhost
$db_username = "justin";
$db_password = "justin0919";
$db_database = "bicycle";
$db_port = "3306"; 


try{
  $conn = new PDO("mysql:host={$db_servername};port={$db_port};dbname={$db_database}", 
                  $db_username, 
                  $db_password,
                  array(
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'  //important
                  )
                 );
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully </br>";
  
}catch(PDOException $e)
{
  //echo "database connection failed: ({$db_servername}:{$db_port})\n {$e->getMessage()}";
  exit;
}


if($day==0 and $month==0){ //select all
  $sql = 'SELECT *, Longitude AS x, Latitude AS y FROM bike WHERE (Timee<=\''.$timeEnd.'\' and Timee>=\''.$timeStart.'\')'
  ;

}elseif($day==0 and $month!=0){
  $sql = 'SELECT *, Longitude AS x, Latitude AS y FROM bike WHERE MONTH(Datee)='.$month.' and (Timee<=\''.$timeEnd.'\' and Timee>=\''.$timeStart.'\')';

}elseif($day!=0 and $month!=0){
  $sql = 'SELECT *, Longitude AS x, Latitude AS y FROM bike WHERE MONTH(Datee)='.$month.' and DAY(Datee)='.$day.' and (Timee<=\''.$timeEnd.'\' and Timee>=\''.$timeStart.'\')';
}



//$sql = 'SELECT *, Longitude AS x, Latitude AS y FROM bike';


$rs = $conn->query($sql);
/*
if (!$rs) {
    echo 'An SQL error occured.\n';
    exit;
}
*/

$geojson = array(
   'type'      => 'FeatureCollection',
   "name"=>  "bicycle",
   'crs'=> array(
      "type" => "name",
      "properties" => array(
        "name"=> "urn:ogc:def:crs:OGC:1.3:CRS84"
      )),
   'features'  => array()
);
# Loop through rows to build feature arrays
while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
    $properties = $row;
    # Remove x and y fields from properties (optional)
    unset($properties['x']);
    unset($properties['y']);
    $feature = array(
        'type' => 'Feature',
        'geometry' => array(
            'type' => 'Point',
            'coordinates' => array(
                (float)$row['x'],
                (float)$row['y']
            )
        ),
        'properties' => $properties
    );
    # Add feature arrays to feature collection array
    array_push($geojson['features'], $feature);
}
header('Content-type: application/json');
echo json_encode($geojson, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
$conn = NULL;



?>