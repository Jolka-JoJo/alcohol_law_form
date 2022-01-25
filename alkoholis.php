<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <link rel='stylesheet' type='text/css' href='web_style.css'>

</head>
<?php

    $yearErr=$monthErr=$dayErr="";
    $year_v=$month_v=$day_v="";
    date_default_timezone_set("Europe/Vilnius");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["year"])) {
            $yearErr = "Privaloma užpildyti";
        }else {
            $year_v=test_input($_POST["year"]);
        }

        if (empty($_POST["month"])) {
            $monthErr = "Privaloma užpildyti";
        }else {
            $month_v=test_input($_POST["month"]);
        }
        if (empty($_POST["day"])) {
            $dayErr = "Privaloma užpildyti";
        }else {
            $day_v=test_input($_POST["day"]);
        }
    };

    $month_now = date('m');
    $day_now = date('d');
    
    if(selling_time($month_now, $day_now)) $available = "Alkoholis yra parduodamas";
    else $available = "Šiuo metu alkoholis neparduodamas";


    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function test_age($year_v, $month_v, $day_v){
        if((date('Y')-$year_v)>20) return 1;
        else if ((date('Y')-$year_v)==20 && date('m')>$month_v) return 1;
        else if ((date('Y')-$year_v)==20 && date('m')==$month_v && date('d')>=$day_v) return 1;
        else return 0;
    }

    function selling_time($month_v, $day_v){
        if($month_v!=9 && $day_v!=1)
        {
            if(date('w')==7 &&  '6'< (date('H')+1) && (date('H')+1) <'15') return 1;
            else if('10'< (date('H')+1) && (date('H')+1) <'20') return 1;
        }else return 0;
    }

    function days_left($year_v, $month_v, $day_v){
        $Year_now=date('Y');
        $Month_now=date('m');
        $Day_now=date('d');
        $days_left=0;
        
        for($i=$Year_now; $i<($year_v+20); $i++){
            if($i%4==0) $days_left+=366;
            else $days_left+=365;
        }
        if($month_v<$Month_now)$days_left-=monthcount($month_v, $Month_now, ($year_v+20));
        else if ($month_v>$Month_now) $days_left+=monthcount($Month_now, $month_v, ($year_v+20));
        
        if($day_v>$Day_now) $days_left=$days_left+($day_v-$Day_now);
        else $days_left=$days_left+($Day_now-$day_v);

        return $days_left;
    }
    function monthcount($A, $B, $year){
        $days=0;
        for($i=$A; $i<$B; $i++){
            switch($i){
                case 1 : {
                    $days+=31;
                    break;
                }
                case 2 : {
                    if(($year+20)%4==0) $days+=29;
                    else $days+=28;
                    break;
                }
                case 3 : {
                    $days+=31;
                    break;
                }
                case 4 : {
                    $days+=30;
                    break;
                }
                case 5 : {
                    $days+=31;
                    break;
                }
                case 6 : {
                    $days+=30;
                    break;
                }
                case 7 : {
                    $days+=31;
                    break;
                }
                case 8 : {
                    $days+=31;
                    break;
                }
                case 9 : {
                    $days+=30;
                    break;
                }
                case 10 : {
                    $days+=31;
                    break;
                }
                case 11 : {
                    $days+=30;
                    break;
                }
                case 12 : {
                    $days+=31;
                    break;
                }
            }
        }
        return $days;
    }

?>
<body>
    <div class="container center">
        <div class="card dark">
        <h3 class="card-header"> <?php echo $available ?></h5>
        </div>
    </div>
    <div class="container main_form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="year">Gimimo metai</label>
            <span class="error">* <?php echo $yearErr ;?></span><br>
            <input type="number" name="year" class="form-control" id="year" placeholder="Metai" aria-label="Metai" aria-describedby="basic-addon2"min=1800 max='<?php echo date("Y")?>'>
            
        </div>
        <div class="form-group col-md-4">
            <label for="Month">Gimimo mėnuo</label>
            <span class="error">* <?php echo $monthErr ;?></span><br>
            <input type="number" name="month" class="form-control" id="Month" placeholder="Mėnuo" aria-label="Mėnuo" aria-describedby="basic-addon2" min=1 max=12>
            
        </div>
        <div class="form-group col-md-4">
            <label for="Day">Gimimo diena</label>
            <span class="error">* <?php echo $dayErr ;?></span><br>
            <input type="number" name="day" class="form-control" id="Day" placeholder="Diena" aria-label="Diena" aria-describedby="basic-addon2" min=1 max=31>
            
        </div>
    </div>
        <!-- <input type="submit" name="submit" value="Submit">   -->
        <button type="submit" class="btn btn-primary">Tikrinti</button>
    </form>
    </div>
    <div class="answers">
    <?php
    if(!empty($year_v) && !empty($month_v) && !empty($day_v)){
        echo '<div class="card client justify-content-center;" >';
        echo '<div class="card-body">';
        echo '<h4 class="card-title">Informacija apie klientą</h3>';
        echo '<h4 class="card-subtitle mb-2 text-muted">Gimimo data</h4>';
        echo '       <p class="card-text">' .$year_v.'   '.$month_v.'   '.$day_v.'</p>';
        echo '    <h4 class="card-subtitle mb-2 text-muted">Ar gali vartoti alkoholį?</h4>';
        echo '    <p class="card-text">';       
        if(test_age($year_v, $month_v, $day_v)) echo "Taip"; else echo "Ne";
        echo '</p>';
        if(!test_age($year_v, $month_v, $day_v)){
             echo '    <h4 class="card-subtitle mb-2 text-muted">Galės vartoti alkoholį po '.days_left($year_v, $month_v, $day_v).' d.</h4>';
        }
        echo '</div>';
        echo '</div>';
    }
?>  
  </div>
        </body>
</html>