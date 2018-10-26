<?php

  function sendToSlack($url, $payload){
    //set url
    //$url = "https://hooks.slack.com/services/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";

    //set useragent
    $userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';

    // Get cURL resource
    $ch = curl_init($url);

    // Set some options - we are passing in a useragent too here
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json')
    );

    // Send the request & save response to $result
    $result = curl_exec($ch);

    //check for curl errors
    if (curl_error($ch)) {
        $error_msg = curl_error($ch);
    }

    // Close request to clear up some resources
    curl_close($ch);

    //send appropriate return value based on if errors have occurred or not
    if (isset($error_msg)) {
      return $error_msg;
    }
    else{
      return 1;
    }
  }

?>
