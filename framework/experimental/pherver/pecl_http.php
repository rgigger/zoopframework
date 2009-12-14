#!/usr/bin/env php
<?php
try {
	$pool = new HttpRequestPool(
		new HttpRequest('http://www.google.com/', HttpRequest::METH_HEAD),
		new HttpRequest('http://www.php.net/', HttpRequest::METH_HEAD)
	);
	$pool->send();
	foreach($pool as $request) {
		printf("%s is %s (%d)\n",
			$request->getUrl(),
			$request->getResponseCode() ? 'alive' : 'not alive',
			$request->getResponseCode()
		);
	}
} catch (HttpException $e) {
	echo $e;
}