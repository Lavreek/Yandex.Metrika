<?php
	$options = getopt("f:");
	if (isset($options['f'])) {
		$f = $options['f'];

		if (!empty($f)) {
			if (file_exists($f)) {
				$result = __DIR__."/code-push-result.log";

				$content = file_get_contents($f);
	        	$filename = basename($f);

				$exp = explode("\n", $content);

	        	$calls = join(PHP_EOL, explode("\n", file_get_contents($f)));
	        	$boundary = uniqid();

	        	$data = "--------------------------$boundary\x0D\x0A";
		        $data .= "Content-Disposition: form-data; name=\"file\"; filename=\"$filename\"\x0D\x0A";
		        $data .= "Content-Type: text/csv\x0D\x0A\x0D\x0A";
		        $data .= $calls."\x0A\x0D\x0A";
		        $data .= "--------------------------$boundary--";

		        $counter = file_get_contents(__DIR__."/.secret/counter.txt"); 
		        $token = json_decode(file_get_contents(__DIR__."/.secret/token.txt"), true);

		        $url = "https://api-metrika.yandex.net/cdp/api/v1/counter/$counter/data/simple_orders?merge_mode=APPEND&oauth_token=".$token['access_token'];
		        $headers = array(
		        	"Content-Type: multipart/form-data; boundary=------------------------$boundary",
		        	"Content-Length: ".strlen($data),
					"Authorization: OAuth ".$token['access_token'],
		        );

		        $ch = curl_init($url);

				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_POST, 1); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				
				$response = curl_exec($ch); file_put_contents($result, date("d.m.Y H:i").": ".$response."\n\n", FILE_APPEND);

				curl_close($ch);

				echo "Push complete";
			} else {
				throw new Exception("File is not exists");
			}

		} else {
			throw new Exception("Param -f is empty");
		}

	} else {
		throw new Exception("Use -f param for configure filepath.");
	}