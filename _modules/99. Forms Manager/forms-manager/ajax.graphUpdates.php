<?php
require_once("../../library/class.systemConfig.php");
require_once("../../library/class.encryptDecrypt.php");

//VARIABLES TO DIFFERENTIATE BETWEEN GRAPH FUNCTIONS
if(isset($_POST['linegraph'])){$linegraph = $_POST['linegraph'];}else{$linegraph = $_GET['linegraph'];}
if(isset($_POST['piecharts'])){$piecharts = $_POST['piecharts'];}else{$piecharts = $_GET['piecharts'];}

//GET VARIABLES
if(isset($_POST['form'])){$formID = $_POST['form'];}else{$formID = $_GET['form'];}

//SET FOR WHICH FORM INFORMATION TO LOOK FOR
if($formID != 0){
	$form_search = "AND formID = '$formID'";
}else{
	$form_search = "";
}

//SET DATES
$startDate = date('Y-m', strtotime("-365 days")).'-01 00:00:00';
$endDate = date("Y-m").'-31 23:59:59';

//#################################################################
//GET LINE GRAPH INFO
//#################################################################
if($linegraph == 1){
    //CONNECT TO DATABASE
    $connector 	= new DbConnector();

    //GET INFORMATION FROM DATABASE
    $default 	= $connector->query("SELECT * FROM forms_info WHERE (date_time BETWEEN ? AND ?) $form_search ORDER BY date_time ASC", array($startDate, $endDate));

    //GET NUMBER OF YEARS BETWEEN DATES
    $years = strtotime($endDate, 0) - strtotime($startDate, 0);
    $years = round($years / 31557600 );

    //GET NUMBER OF MONTHS BETWEEN DATES
    $months = strtotime($endDate, 0) - strtotime($startDate, 0);
    $months = floor($months / 2628000);

    //GET NUMBER OF DAYS BETWEEN DATES
    $days = strtotime($endDate, 0) - strtotime($startDate, 0);
    $days = round($days / 86400);

    $label = '';

    //GET YEARS
    if($days/365 >= 3){
    	//CREATE START ARRAY WITH CORRECT VALUES
    	$array = array();
    	while($defaultRow	= $connector->fetchArray($default)){
    		$date = explode(' ',$defaultRow['date_time']);
    		$array[].= substr($date[0],0,4);
    	}
    	$dates_array = array_count_values($array);

    	//GET YEARS BETWEEN 2 DATES
    	$label = '';
    	$values = '';
    	$current = strtotime($startDate);
    	$last = strtotime($endDate);
    	while( $current <= $last ) {

    		if($count == $years){
    			$label.= '"'.date("Y", $current).'"';

    			//CHECK IF KEY EXISTS FOR START VALUES
    			if(array_key_exists(date("Y", $current), $dates_array)){
    				$values.= $dates_array[date("Y", $current)].',';
    			}else{
    				$values.= '0,';
    			}

    			$count++;
    		}else{
    			$label.= '"'.date("Y", $current).'",';

    			//CHECK IF KEY EXISTS FOR START VALUES
    			if(array_key_exists(date("Y", $current), $dates_array)){
    				$values.= $dates_array[date("Y", $current)].',';
    			}else{
    				$values.= '0,';
    			}

    			$count++;
    		}

    		$current = strtotime('+1 year', $current);
    	}

    	//FINALISE THE VALUES
    	if(substr($label, -1) == ','){
    		$label = substr($label,0,-1);
    	}
    	$label = str_replace('""','","', $label);
    	$values = substr($values,0,-1);

    //GET MONTHS
    }elseif($months >= 1){
    	//CREATE START ARRAY WITH CORRECT VALUES
    	$array = array();
    	while($defaultRow	= $connector->fetchArray($default)){
    		$date = explode(' ',$defaultRow['date_time']);
    		$array[].= date("F Y",strtotime(substr($date[0],0,7)));
    	}
    	$dates_array = array_count_values($array);

    	//GET MONTHS BETWEEN 2 DATES
    	$count = 0;

    	$label = '';
    	$values = '';
    	$current = strtotime($startDate);
    	$last = strtotime($endDate);
    	while( $current <= $last ) {

    		if($count == $months){
    			$label.= '"'.date("F Y", $current).'"';

    			//CHECK IF KEY EXISTS FOR START VALUES
    			if(array_key_exists(date("F Y", $current), $dates_array)){
    				$values.= $dates_array[date("F Y", $current)].',';
    			}else{
    				$values.= '0,';
    			}

    			$count++;
    		}else{
    			$label.= '"'.date("F Y", $current).'",';

    			//CHECK IF KEY EXISTS FOR START VALUES
    			if(array_key_exists(date("F Y", $current), $dates_array)){
    				$values.= $dates_array[date("F Y", $current)].',';
    			}else{
    				$values.= '0,';
    			}
    		}

    		$current = strtotime('+1 month', $current);
    	}

    	//FINALISE THE VALUES
    	if(substr($label, -1) == ','){
    		$label = substr($label,0,-1);
    	}
    	$values = substr($values,0,-1);

    //GET WEEKS
    }elseif($days/7 >= 2){

    	//CREATE START ARRAY WITH CORRECT VALUES
    	$array = array();
    	while($beginRow	= $connector->fetchArray($default)){
    		$date = explode(' ',$beginRow['date_time']);
    		$array[].= date("j F Y",strtotime(substr($date[0],0,10)));
    	}
    	$dates_array = array_count_values($array);

    	//GET WEEKS BETWEEN 2 DATES
    	$count = 0;

    	$label = '';
    	$values = '';
    	$current = strtotime($startDate);
    	$last = strtotime($endDate);

    	$beginWeek = '';
    	$endWeek = '';

    	$firstTotal = 0;

    	//SET THE BEGINNING AND END OF A WEEK
    	$beginWeek = date("j F Y",$current);
    	$endWeek = date("j F Y",strtotime('+1 week', $current));
    	$endWeekLimit = date("j F Y",strtotime('+1 week + 1day', $current));

    	//LOOP THROUGH UNTIL BEGIN DATE IS EQUAL TO LAST DATE
    	while( $current <= $last ) {

    		if(date("j F Y", $current) == $endWeek){


    			$label.= '"'.date("j F Y", $current).'",';

    			//CHECK IF KEY EXISTS FOR START VALUES
    			if(array_key_exists(date("j F Y", $current), $dates_array)){
    				$firstTotal = $firstTotal + $dates_array[date("j F Y", $current)];
    			}else{
    				$firstTotal = $firstTotal + 0;
    			}

    			//SET GRAPH VALUES
    			$values.= $firstTotal.',';

    			//SET FIRST TOTAL BACK TO 0 TO GET NEW VALUES
    			$firstTotal = 0;

    			$endWeek = date("j F Y",strtotime('+1 week', $current));
    		}else{

    			//CHECK IF KEY EXISTS FOR START VALUES
    			if(array_key_exists(date("j F Y", $current), $dates_array)){
    				$firstTotal =  $firstTotal + $dates_array[date("j F Y", $current)];
    			}else{
    				$firstTotal = $firstTotal + 0;
    			}

    			$current = strtotime('+1 day', $current);
    		}
    	}

    	//FINALISE THE VALUES
    	if(substr($label, -1) == ','){
    		$label = substr($label,0,-1);
    	}
    	$values = substr($values,0,-1);

    //GET DAYS
    }else{
    	//CREATE START ARRAY WITH CORRECT VALUES
    	$array = array();
    	while($defaultRow	= $connector->fetchArray($default)){
    		$date = explode(' ',$defaultRow['date_time']);
    		$array[].= date("j F Y",strtotime(substr($date[0],0,10)));
    	}
    	$dates_array = array_count_values($array);

    	//GET THE DAYS BETWEEN 2 DATES
    	$count = 0;

    	$label = '';
    	$values = '';
    	$current = strtotime($startDate);
    	$last = strtotime($endDate);
    	while( $current <= $last ) {

    		if($count == $days){
    			$label.= '"'.date("j F Y", $current).'"';

    			//CHECK IF KEY EXISTS FOR START VALUES
    			if(array_key_exists(date("j F Y", $current), $dates_array)){
    				$values.= $dates_array[date("j F Y", $current)].',';
    			}else{
    				$values.= '0,';
    			}

    			$count++;
    		}else{
    			$label.= '"'.date("j F Y", $current).'",';

    			//CHECK IF KEY EXISTS FOR START VALUES
    			if(array_key_exists(date("j F Y", $current), $dates_array)){
    				$values.= $dates_array[date("j F Y", $current)].',';
    			}else{
    				$values.= '0,';
    			}

    			$count++;
    		}

    		$current = strtotime('+1 day', $current);
    	}

    	//FINALISE THE VALUES
    	if(substr($label, -1) == ','){
    		$label = substr($label,0,-1);
    	}
    	$values = substr($values,0,-1);

    }

    //CREATE OUTPUT VARIABLE (JSON FORMAT)
    $txt.='{
    		"labels":['.$label.'],

    		"datasets":[{"label":"My First dataset",
    		"fillColor":"rgba(151,187,205,0.2)",
    		"strokeColor":"rgba(151,187,205,1)",
    		"pointColor":"rgba(151,187,205,1)",
    		"pointStrokeColor":"#fff",
    		"pointHighlightFill":"#fff",
    		"pointHighlightStroke":"rgba(151,187,205,1)",
    		"data":['.$values.']}

    		]}';

    echo $txt;
}

//#################################################################
//GET PIE CHARTS INFO
//#################################################################
if($piecharts == 1){
    //CONNECT TO DATABASE
    $connector 	= new DbConnector();

    //###########################################
    // GET ALL CITY INFO
    //###########################################
    //SET VARIABLE TO HOLD INFORMATION
	$city_values = '';
	$count			= 1;
	$others_total	= 0;

	//GET DATES FROM DATABASE
	$default 	= $connector->query("SELECT * FROM forms_info WHERE (date_time BETWEEN ? AND ?) $form_search ORDER BY date_time ASC", array($startDate, $endDate));

	//CREATE ARRAY WITH ALL VALUES COUNTED
	$cityArray = array();
	while($defaultRow	= $connector->fetchArray($default)){
		$cityArray[].= $defaultRow['city'];
	}
	$cities_array = array_count_values($cityArray);

	//SORT ARRAY HIGHEST TO LOWEST VALUE
	arsort($cities_array);

	//GET LENGTH OF ARRAY - NEEDED LATER FOR GETTING "OTHER" RESULTS
	$array_length = count($cities_array);

	//LOOP THROUGH THE NEWLY CREATED ARRAY
	foreach($cities_array as $key => $value){

		if($count == 1){
			$city_values.= '
			{
				"value": '.$value.',
				"color": "#46BFBD",
				"highlight": "#5AD3D1",
				"label": "'.$key.'"
			},';
			$count++;
		}
		elseif($count == 2){
			$city_values.= '
			{
				"value": '.$value.',
				"color": "#F7464A",
				"highlight": "#FF5A5E",
				"label": "'.$key.'"
			},';
			$count++;
		}
		elseif($count == 3){
			$city_values.= '
			{
				"value": '.$value.',
				"color": "#46bf6e",
				"highlight": "#5ad37c",
				"label": "'.$key.'"
			},';
			$count++;
		}
		elseif($count == 4){
			$city_values.= '
			{
				"value": '.$value.',
				"color": "#FDB45C",
				"highlight": "#FFC870",
				"label": "'.$key.'"
			},';
			$count++;
		}
		//GET TOTAL FOR OTHERS
		elseif($count >= 5){
			if($count == $array_length){
				$others_total = $others_total + $value;
				$city_values.= '
				{
					"value": '.$others_total.',
					"color": "#bf46b8",
					"highlight": "#d35ac6",
					"label": "Others"
				},';
				$count++;
			}else{
				$others_total = $others_total + $value;
				$count++;
			}
		}

	}

    //CITIES
	$city_values = substr($city_values,0,-1);

    //###########################################
    // GET ALL LANGUAGE INFO
    //###########################################
    //SET VARIABLE TO HOLD INFORMATION
	$language_values = '';
	$count			= 1;
	$others_total	= 0;

	//GET DATES FROM DATABASE
	$default 	= $connector->query("SELECT * FROM forms_info WHERE (date_time BETWEEN ? AND ?) $form_search ORDER BY date_time ASC", array($startDate, $endDate));

	//CREATE ARRAY WITH ALL VALUES COUNTED
	$languageArray = array();
	while($defaultRow	= $connector->fetchArray($default)){
		$language 		= explode(' ',$defaultRow['browserLanguage']);
		$language		= explode('-', $language[0]);
		$languageArray[].= $language[0];
	}
	$langugaes_array = array_count_values($languageArray);

	//SORT ARRAY HIGHEST TO LOWEST VALUE
	arsort($langugaes_array);

	//GET LENGTH OF ARRAY - NEEDED LATER FOR GETTING "OTHER" RESULTS
	$array_length = count($langugaes_array);

	//LOOP THROUGH THE NEWLY CREATED ARRAY
	foreach($langugaes_array as $key => $value){

        //REMOVE EVERYTHING FROM KEY AFTER "-"
        //$key = substr($key, 0, strpos($key, '-'));

        //IF KEY NAME IS NOT EMPTY/NOTHING
        //if($key != ''){

    		//GET NAME FOR KEY
    		$name_selector = $connector->query("SELECT * FROM all_languages WHERE code = ?", array($key));
    		$name		   = $connector->fetchArray($name_selector);
    		$key		   = $name['name_en'];

    		if($count == 1){
    			$language_values.= '
    			{
    				"value": '.$value.',
    				"color": "#46BFBD",
    				"highlight": "#5AD3D1",
    				"label": "'.$key.'"
    			},';
    			$count++;
    		}
    		elseif($count == 2){
    			$language_values.= '
    			{
    				"value": '.$value.',
    				"color": "#F7464A",
    				"highlight": "#FF5A5E",
    				"label": "'.$key.'"
    			},';
    			$count++;
    		}
    		elseif($count == 3){
    			$language_values.= '
    			{
    				"value": '.$value.',
    				"color": "#46bf6e",
    				"highlight": "#5ad37c",
    				"label": "'.$key.'"
    			},';
    			$count++;
    		}
    		elseif($count == 4){
    			$language_values.= '
    			{
    				"value": '.$value.',
    				"color": "#FDB45C",
    				"highlight": "#FFC870",
    				"label": "'.$key.'"
    			},';
    			$count++;
    		}
    		//GET TOTAL FOR OTHERS
    		elseif($count >= 5){
    			if($count == $array_length){
    				$others_total = $others_total + $value;
    				$language_values.= '
    				{
    					"value": '.$others_total.',
    					"color": "#bf46b8",
    					"highlight": "#d35ac6",
    					"label": "Others"
    				},';
    				$count++;
    			}else{
    				$others_total = $others_total + $value;
    				$count++;
    			}
    		}

    	//}
    }

    //LANGUAGES
	$language_values = substr($language_values,0,-1);

    //###########################################
    // GET ALL BROWSER INFO
    //###########################################
    //SET VARIABLE TO HOLD INFORMATION
	$browser_values = '';
	$count			= 1;
	$others_total	= 0;

	//GET DATES FROM DATABASE
	$default 	= $connector->query("SELECT * FROM forms_info WHERE (date_time BETWEEN ? AND ?) $form_search ORDER BY date_time ASC", array($startDate, $endDate));

	//CREATE ARRAY WITH ALL VALUES COUNTED
	$browserArray = array();
	while($defaultRow	= $connector->fetchArray($default)){
		$browserArray[].= $defaultRow['browser'];
	}
	$browsers_array = array_count_values($browserArray);

	//SORT ARRAY HIGHEST TO LOWEST VALUE
	arsort($browsers_array);

	//GET LENGTH OF ARRAY - NEEDED LATER FOR GETTING "OTHER" RESULTS
	$array_length = count($browsers_array);

	//LOOP THROUGH THE NEWLY CREATED ARRAY
	foreach($browsers_array as $key => $value){

		if($count == 1){
			$browser_values.= '
			{
				"value": '.$value.',
				"color": "#46BFBD",
				"highlight": "#5AD3D1",
				"label": "'.$key.'"
			},';
			$count++;
		}
		elseif($count == 2){
			$browser_values.= '
			{
				"value": '.$value.',
				"color": "#F7464A",
				"highlight": "#FF5A5E",
				"label": "'.$key.'"
			},';
			$count++;
		}
		elseif($count == 3){
			$browser_values.= '
			{
				"value": '.$value.',
				"color": "#46bf6e",
				"highlight": "#5ad37c",
				"label": "'.$key.'"
			},';
			$count++;
		}
		elseif($count == 4){
			$browser_values.= '
			{
				"value": '.$value.',
				"color": "#FDB45C",
				"highlight": "#FFC870",
				"label": "'.$key.'"
			},';
			$count++;
		}
		//GET TOTAL FOR OTHERS
		elseif($count >= 5){
			if($count == $array_length){
				$others_total = $others_total + $value;
				$browser_values.= '
				{
					"value": '.$others_total.',
					"color": "#bf46b8",
					"highlight": "#d35ac6",
					"label": "Others"
				},';
				$count++;
			}else{
				$others_total = $others_total + $value;
				$count++;
			}
		}

	}

    //BROWSERS
	$browser_values = substr($browser_values,0,-1);

	//###########################################
    // GET ALL OPERATING SYSTEM INFO
    //###########################################
	//SET VARIABLE TO HOLD INFORMATION
	$os_values = '';
	$count			= 1;
	$others_total	= 0;

	//GET DATES FROM DATABASE
	$default 	= $connector->query("SELECT * FROM forms_info WHERE (date_time BETWEEN ? AND ?) $form_search ORDER BY date_time ASC", array($startDate, $endDate));

	//CREATE ARRAY WITH ALL VALUES COUNTED
	$osArray = array();
	while($defaultRow	= $connector->fetchArray($default)){
		$osArray[].= $defaultRow['operatingSystem'];
	}
	$oss_array = array_count_values($osArray);

	//SORT ARRAY HIGHEST TO LOWEST VALUE
	arsort($oss_array);

	//GET LENGTH OF ARRAY - NEEDED LATER FOR GETTING "OTHER" RESULTS
	$array_length = count($oss_array);

	//LOOP THROUGH THE NEWLY CREATED ARRAY
	foreach($oss_array as $key => $value){

		if($count == 1){
			$os_values.= '
			{
				"value": '.$value.',
				"color": "#46BFBD",
				"highlight": "#5AD3D1",
				"label": "'.$key.'"
			},';
			$count++;
		}
		elseif($count == 2){
			$os_values.= '
			{
				"value": '.$value.',
				"color": "#F7464A",
				"highlight": "#FF5A5E",
				"label": "'.$key.'"
			},';
			$count++;
		}
		elseif($count == 3){
			$os_values.= '
			{
				"value": '.$value.',
				"color": "#46bf6e",
				"highlight": "#5ad37c",
				"label": "'.$key.'"
			},';
			$count++;
		}
		elseif($count == 4){
			$os_values.= '
			{
				"value": '.$value.',
				"color": "#FDB45C",
				"highlight": "#FFC870",
				"label": "'.$key.'"
			},';
			$count++;
		}
		//GET TOTAL FOR OTHERS
		elseif($count >= 5){
			if($count == $array_length){
				$others_total = $others_total + $value;
				$os_values.= '
				{
					"value": '.$others_total.',
					"color": "#bf46b8",
					"highlight": "#d35ac6",
					"label": "Others"
				},';
				$count++;
			}else{
				$others_total = $others_total + $value;
				$count++;
			}
		}

	}

	//OPERATING SYSTEMS
	$os_values = substr($os_values,0,-1);


    //RETURN ALL PIE CHART INFO
	echo '['.$city_values.']^['.$language_values.']^['.$browser_values.']^['.$os_values.']';
}
?>
