<!DOCTYPE html>
<?php session_start(); ?>
<?php require_once("connection.php"); ?>
<?php $graph_highchart = true; ?>
<html>
<!--     <head>
        <script src="../js/jquery-3.1.1.min.js"></script>
        <script src="../js/highcharts.js"></script>
        <script src="../js/data.js"></script>
        <script src="../js/series-label.js"></script>
        <script src="../js/exporting.js"></script>
        <script src="../js/export-data.js"></script>
        <script src="../js/highslide-full.min.js"></script>
        <script src="../js/highslide.config.js" charset="utf-8"></script>
        <link rel="stylesheet" type="text/css" href="../css/highslide.css" />
        <link rel="stylesheet" href="../css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="../js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        
        <link rel="stylesheet" href="../css/materialize.min.css">
        
        <script src="../js/materialize.min.js"></script>
    </head> -->
<?php require_once("head.php"); ?>
    <body>
        <div class="container">
            <div class="row mt-5">
                <?php require_once("navigation.php"); ?>
            </div>

            <div class="row m-5">
                <!-- <form action="graph.php" method="GET" class="col-12"> -->
                <form action="graph.php" method="GET" class="row">
                    <div class="col mx-1">
                        <label class="">Year</label>
                        <select name="year" class="custom-select ">
                          
                            <?php
                            $myyear = "SELECT COUNT('month'), year FROM tbl_dht11 GROUP BY year";
                            $result_year = $conn->query($myyear);
                            if ($result_year->num_rows > 0) {
                            while($row = $result_year->fetch_assoc()) {
                            echo "<option value=\"".$row["year"]."\">". $row["year"]."</option>";
                            }
                            }
                            
                            ?>
                        </select>
                    </div>
                    <div class="col mx-1">
                        <label class="">Month</label>
                        <select name="month" class="custom-select ">
                            
                            <?php
                            $mymonth = "SELECT COUNT('day'), month FROM tbl_dht11 GROUP BY month";
                            $result_month = $conn->query($mymonth);
                            if ($result_month->num_rows > 0) {
                            while($row = $result_month->fetch_assoc()) {
                            echo "<option value=\"".$row["month"]."\">". $row["month"]."</option>";
                            }
                            }
                            
                            ?>
                        </select>
                    </div>
                    <div class="col mx-1">
                        <label class="">Day</label>
                        <select name="day" class="custom-select ">
                            
                            <?php
                            $myday = "SELECT COUNT('month'), day FROM tbl_dht11 GROUP BY day";
                            $result_day = $conn->query($myday);
                            if ($result_day->num_rows > 0) {
                            while($row = $result_day->fetch_assoc()) {
                            echo "<option value=\"".$row["day"]."\">". $row["day"]."</option>";
                            }
                            }
                            
                            ?>
                        </select>
                    </div>
                    <div class="col ml-1 mt-4">
                        <input type="submit" name"Submit" value="search" class="btn btn-info ">
                    </div>
                </form>
                
            </div>
            <?php  date_default_timezone_set("Asia/Manila"); date('d-m-Y H:i:s A'); ?>
            <hr>
            <?php
            // date_default_timezone_set("Asia/Manila");
            // echo date('d-m-Y H:i:s A');
            ?>
        </div>
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        <hr>
        <script>
        Highcharts.chart('container', {
        chart: {
        zoomType: 'xy'
        },
        title: {
        text: 'Webbase greenhouse soiless substrate an environmental parameters monitoring'
        },
        subtitle: {
        text: null
        },
        xAxis: [{
        categories: [
        <?php 

        $myQuery_date = "SELECT  year, month, day, hour, minutes, seconds FROM tbl_dht11";
        $myQuery_date .= $retVal = (
            isset($_GET['year'])&&
            isset($_GET['month'])&&
            isset($_GET['day'])) ? " WHERE year = '". $_GET['year'] .
             "' AND month = '".$_GET['month'].
             "' AND day = '".$_GET['day']."'" :'';
        $myQuery_date .=" ORDER BY year, month, day, hour, minutes, seconds LIMIT 86400";

        //echo $myQuery_date;
        $resultxx = $conn->query($myQuery_date);
        if ($resultxx->num_rows > 0) {
        while($row = $resultxx->fetch_assoc()) {
        echo "'". $row["year"]."-".$row["month"] ."-".$row["day"]." | ".$row["hour"].":".$row["minutes"].":".$row["seconds"]."',";
        }
        }
        
        ?>
        ],
        crosshair: true
        }],
        yAxis: [{ // Primary yAxis
        labels: {
        format: '{value}°C',
        style: {
        color: "red"
        }
        },
        title: {
        text: 'Temperature',
        style: {
        color: "red"
        }
        },
        opposite: true
        }, { // Secondary yAxis
        gridLineWidth: 0,
        title: {
        text: 'Moisture',
        style: {
        color: "grey"
        }
        },
        labels: {
        format: '{value}%',
        style: {
        color: "grey"
        }
        }
        }, { // Tertiary yAxis
        gridLineWidth: 0,
        title: {
        text: 'Humidity',
        style: {
        color: "rgb(124, 181, 236)"
        }
        },
        labels: {
        format: '{value} mb',
        style: {
        color: "rgb(124, 181, 236)"
        }
        },
        opposite: true
        }],
        tooltip: {
        shared: true
        },
        legend: {
        layout: 'vertical',
        align: 'left',
        x: 90,
        verticalAlign: 'top',
        y: 5,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#a1f1ae6b'
        },
        series: [{
        name: 'Humidity',
        type: 'spline',
        yAxis: 2,
        data: [
        <?php
        //$myQuery_humidity = "SELECT humidity, year, month, day, hour, minutes, seconds FROM tbl_dht11 ORDER BY year, month, day LIMIT 60";

        $myQuery_humidity = "SELECT  humidity, year, month, day, hour, minutes, seconds FROM tbl_dht11";
        $myQuery_humidity .= $retVal = (
            isset($_GET['year'])&&
            isset($_GET['month'])&&
            isset($_GET['day'])) ? " WHERE year = '". $_GET['year'] .
             "' AND month = '".$_GET['month'].
             "' AND day = '".$_GET['day']."'" :'';
        $myQuery_humidity .=" ORDER BY year, month, day, hour, minutes, seconds LIMIT 86400";



        $resultxx = $conn->query($myQuery_humidity);
        if ($resultxx->num_rows > 0) {
        while($row = $resultxx->fetch_assoc()) {
        echo $row["humidity"].",";
        }
        }
        
        ?>
        ],
        marker: {
        enabled: false
        },
        dashStyle: 'shortdot',
        tooltip: {
        valueSuffix: ' %'
        }
        }, {
        name: 'Temperature',
        type: 'spline',
        color: "red",
        data: [
        <?php
        // $myQueryx = "SELECT temperature, year, month, day, hour, minutes, seconds FROM tbl_dht11 ORDER BY year, month, day LIMIT 60";
        $myQuery_temp = "SELECT  temperature, year, month, day, hour, minutes, seconds FROM tbl_dht11";
        $myQuery_temp .= $retVal = (
            isset($_GET['year'])&&
            isset($_GET['month'])&&
            isset($_GET['day'])) ? " WHERE year = '". $_GET['year'] .
             "' AND month = '".$_GET['month'].
             "' AND day = '".$_GET['day']."'" :'';
        $myQuery_temp .=" ORDER BY year, month, day, hour, minutes, seconds LIMIT 86400";

        $resultx = $conn->query($myQuery_temp);
        if ($resultx->num_rows > 0) {
        while($row = $resultx->fetch_assoc()) {
        echo $row["temperature"].",";
        }
        }
        ?>
        ],
        marker: {
        enabled: false
        },
        dashStyle: 'shortdot',
        tooltip: {
        valueSuffix: ' °C'
        }
        }, {
        name: 'Moisture',
        type: 'spline',
        yAxis: 1,
        color: "grey",
        data: [
        <?php
        //$moisture = "SELECT moist, year, month, day, hour, minutes, seconds FROM tbl_moisture ORDER BY year, month, day LIMIT 60";
        $myQuery_moisture = "SELECT  moist, year, month, day, hour, minutes, seconds FROM tbl_moisture";
        $myQuery_moisture .= $retVal = (
            isset($_GET['year'])&&
            isset($_GET['month'])&&
            isset($_GET['day'])) ? " WHERE year = '". $_GET['year'] .
             "' AND month = '".$_GET['month'].
             "' AND day = '".$_GET['day']."'" :'';
        $myQuery_moisture .=" ORDER BY year, month, day, hour, minutes, seconds LIMIT 86400";

        $resultx = $conn->query($myQuery_moisture);
        if ($resultx->num_rows > 0) {
        while($row = $resultx->fetch_assoc()) {
        echo $row["moist"].",";
        }
        }
        ?>
        // =====================
        //
        // Data Here for Moisture
        // =============
        ],
        marker: {
        enabled: false
        },
        dashStyle: 'shortdot',
        tooltip: {
        valueSuffix: ' %'
        }
        }]
        });
        </script>
<?php require_once("footer.php"); ?>
    </body>
</html>
<?php $conn->close(); ?>