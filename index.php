<?php

function grab_image($URL){
	$filesname= md5($URL).'.txt';
		if(file_exists("cache_gambar/".$filesname)){
			return file_get_contents("cache_gambar/".$filesname);
		}
preg_match('/https?:\/\/([a-zA-Z0-9-_.]+)\/(.*)/i', $URL, $outdomain);
		if(preg_match('/(blogspot.com|imgur.com)/i', $outdomain[1])){
			$IMAGE_URL= $URL;
		}else{
					$IMAGE_URL= 'http://i0.wp.com/'.preg_replace('/https?:\/\//i', '', $URL);
			
		}
		
	$data = curl_init();
	$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
	$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	$header[] = "Cache-Control: max-age=0";
	$header[] = "Connection: keep-alive";
	$header[] = "Keep-Alive: 300";
	$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$header[] = "Accept-Language: en-us,en;q=0.5";
	$header[] = "Pragma: "; // browsers keep this blank.

     curl_setopt($data, CURLOPT_SSL_VERIFYHOST, FALSE);
     curl_setopt($data, CURLOPT_SSL_VERIFYPEER, FALSE);
     curl_setopt($data, CURLOPT_URL, $IMAGE_URL);
	 curl_setopt($data, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
	 curl_setopt($data, CURLOPT_HTTPHEADER, $header);
	 curl_setopt($data, CURLOPT_REFERER, $outdomain[1]);
	 curl_setopt($data, CURLOPT_ENCODING, 'gzip,deflate');
	 curl_setopt($data, CURLOPT_AUTOREFERER, true);
	 curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
	 curl_setopt($data, CURLOPT_CONNECTTIMEOUT, 10);
	 curl_setopt($data, CURLOPT_TIMEOUT, 10);
	 curl_setopt($data, CURLOPT_MAXREDIRS, 3);
	 curl_setopt($data, CURLOPT_FOLLOWLOCATION, true);

     $hasil = curl_exec($data);
     curl_close($data);

if(strlen($hasil) < 150){
	return 'error';
}	
	$fff= fopen("cache_gambar/".$filesname,"w");
	fwrite($fff, $hasil);
	fclose($fff);
return $hasil;	 
}


//CODING happy
if(!file_exists("cache_gambar")){
		$oldmask = umask(0);
		mkdir("cache_gambar", 0777);
		umask($oldmask);
}

	if(preg_match('/^\/https?:\/\//i', $_SERVER['REQUEST_URI'])){
$image_url= preg_replace('/^\/(https?:\/\/)/i', '\\1', $_SERVER['REQUEST_URI']);
	}else{
$image_url= preg_replace('/^\//i', 'http://', $_SERVER['REQUEST_URI']);
	}

$image_string= grab_image($image_url);

	if($image_string == 'error'){
header('HTTP/1.1 400 Bad Request');
header("Content-type: text/plain");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
		exit('Error 0004. Unable to load the image.');
	}



	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
		header('HTTP/1.1 304 Not Modified');
        die();
	}
header('Content-Type: image/jpeg');
header('Cache-control: max-age='.(60*60*365*2));
header('Expires: '.gmdate(DATE_RFC1123,time()+60*60*365*2));
header('Last-Modified: '.gmdate(DATE_RFC1123,time()));

echo $image_string;
