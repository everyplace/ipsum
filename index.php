<?php 

//force utf8
//header('Content-Type: text/json; charset=UTF-8' );

$term = isset($_GET['term']) ? $_GET['term'] : 'star trek';
$url_to_get = "http://en.wikipedia.org/w/api.php?action=opensearch&limit=10&namespace=0&format=xml&search=" . urlencode($term);
// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $url_to_get);
curl_setopt($ch, CURLOPT_USERAGENT, 'pinion.me search interface');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: text/xml"));

// grab URL and pass it to the browser
$output = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

$xml = new SimpleXMLElement($output);

$response = array();

foreach($xml->Section->Item as $item) {
	//print_r($item);
	
	//[Text] => Cascading Style Sheets...
    //[Description] => Cascading Style Sheets (CSS) is a ...
    //[Url] => http...
    
	$result = new stdclass();
	$result->id = (string)$item->Url;
	$result->label = (string)$item->Text;
	$result->value = (string)$item->Text;
	$result->description = (string)$item->Description;
	//$result->value = (string)$item->Description; NOTE: disabled due to autocomplete bug

	//filter out results from wikipedia that are like "ASDF may refer to:"
	if(substr($result->description, -1) != ":") {
		$response[] = $result;
	}
	
}

echo(print_r($response));
