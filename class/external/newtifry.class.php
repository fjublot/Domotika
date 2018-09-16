<?php
/**
 * Newtifry someone.
 * @param string $source The source key.
 * @param string $title The title of the notification.
 * @param string $message The message body of the notification.
 * @param string|NULL $url The URL to send along with it.
 * @param int|NULL $priority The message priority (0-3).
 * @param string|NULL $deviceId The target deviceId.
 * @param string $backend The backend URL.
 * @return array An array with a boolean key 'success'. On false, another
 * key is set with 'error', on true, a key is set with 'message'.
 */
function newtifry($source, $title, $message, $url = NULL, $priority = 0, $deviceid = "", $imageUrl = "", $backend = 'https://newtifry.appspot.com/newtifry')
{
    $params = array();
    $params['source'] = $source;
    $params['message'] = $message;
    $params['title'] = $message;
    $params['priority'] = $priority;
    $params['deviceid'] = $deviceid;
    $params['format'] = 'json';
    if( false === is_null($url) )
    {
        $params['url'] = $url;
    }
    if( false === is_null($imageUrl) )
    {
        $params['image'] = $imageUrl;
    }
     
    $encodedParameters = array();
    foreach( $params as $key => $value )
    {
        $encodedParameters[] = $key . "=" . urlencode($value);
    }
    $body = implode("&", $encodedParameters);
    //echo $body . "\n";
    // Using CURL, send the request to the server.
    $c = curl_init($backend);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $body);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER,false);
    $page = curl_exec($c);
    // Parse the result.
    $result = array('success' => false);
    if( $page !== FALSE )
    {
        //echo $page . "\n";
        // The result is JSON encoded.
        $decoded = json_decode($page, TRUE);
        if( $decoded === FALSE )
        {
            $result['error'] = "Failed to decode server response: " . $page;
        }
        else
        {
            if( isset($decoded['error']) )
            {
                $result['error'] = $decoded['error'];
            }
            else
            {
                $result['success'] = true;
                $result['message'] = "Success! Message size " . $decoded['size'];
            }
        }
    }
    else
    {
        $result['error'] = curl_error($c);
    }
 
    curl_close($c);
 
    return $result;
}
 