 <?php 
//link for API
$api = "https://api.openweathermap.org/data/2.5/weather?units=metric&lat=55.458565&lon=-4.629179&appid=f3d16ce85a48f177a124f8e0b7c4ae5f&units=metric";
//initializing curl
$ch = curl_init();
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_URL,$api);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch,CURLOPT_VERBOSE,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);


//executing curl
$response = curl_exec($ch);

//closing curl
curl_close($ch);

//data decoding into json
$data = json_decode($response);

//assigning data from database into respective variables
$temp = $data->main->temp;
$pressure = $data->main->pressure;
$humidity = $data->main->humidity;
$wind = $data->wind->speed;
$desc = $data->weather[0]->description;
//connecting to database 
//password:Classtime@2022
//database:weather
$con =new mysqli('localhost','root','Classtime@2022','weather');
if($con->connect_error){
    echo "not connected";
    die();
}else{  //inserting data into table weather created in database
    $send ="INSERT INTO weather (main,temp,wind,pressure,humidity) VALUES('$desc','$temp','$wind','$pressure','$humidity')";
    $s = mysqli_query($con,$send);
    if($s){
    }else{
        echo "sending failed";
    }
} // selecting all the data form weather table by id in decending order
$take = "SELECT * FROM weather order by id desc";
$t = mysqli_query($con,$take);
$rowdata = mysqli_fetch_object($t);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Weather App</title>
</head>
<body><!-- div for arranging all the required elements for the app-->
    <div class="app-main">
        
 
        <div class="weather-body">
            <div class="location-details">
                <h1 class="city" id="city">Ayr</h1><span class="country">United Kingdom</span>
                <hr>
                <!-- <div class="date" id="date">--<span class="day">--</span> --</div> -->
            </div>
<!-- Retriving data from MYSQL table-->
            <div class="weather-status">
                <span>Temperature: </span><span class="temp" id="temp"></span><br>
                <span>Wind: </span> <span id="speed"></span><br>
                <span>Pressure: </span><span id="pressure"></span><br>
                <span>Humidity: </span><span id="humidity"></span><br>
                <span class="desc" id="desc"></span><br>
                
            </div>
        </div>
    </div>
     <script>
        <?php if(!$con->connect_error){?>
            function updateWeather(){
                window.localStorage.setItem("tmp","<?php print_r(($rowdata->temp). "°C")?>");    
                window.localStorage.setItem("wind","<?php print_r(($rowdata->wind). "m/s")?>");
                window.localStorage.setItem("pressure","<?php print_r(($rowdata->pressure). "hpa")?>");
                window.localStorage.setItem("humidity","<?php print_r(($rowdata->humidity). "%")?>");
                window.localStorage.setItem("desc","<?php print_r(($rowdata->main))?>");
                setTimeout(()=>updateWeather(),5000);
            }
            updateWeather();
        <?php } ?>
        document.getElementById("temp").innerHTML = window.localStorage.getItem("tmp");
            document.getElementById("speed").innerHTML = window.localStorage.getItem("wind");
            document.getElementById("pressure").innerHTML = window.localStorage.getItem("pressure");
            document.getElementById("humidity").innerHTML = window.localStorage.getItem("humidity");
            document.getElementById("desc").innerHTML = window.localStorage.getItem("desc");
     </script>
</body>
</html>