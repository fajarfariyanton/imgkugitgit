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

	 
	$fff= fopen("cache_gambar/".$filesname,"w");
	fwrite($fff, $hasil);
	fclose($fff);
return $hasil;	 
}

echo $_SERVER['REQUEST_URI'];
