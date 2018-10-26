<?php
    error_reporting(E_ALL & ~ E_NOTICE);

    //include SQLHelper class
    include("SQLHelper.php");

    //database authentication starts
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "sakila";
    //database authentication ends

    //create MySQLdatabase connection
    $sql = new SQLHelper($host, $user, $password, $database);
    //MySQL database connection ends

    //SQL query to list tables in database along with their auto inc columns, max size, auto inc id and ratio of auto inc to max size
    $query = "SELECT TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, DATA_TYPE, COLUMN_TYPE,
    IF( LOCATE('unsigned', COLUMN_TYPE) > 0, 1, 0 ) AS IS_UNSIGNED,
    ( CASE DATA_TYPE WHEN 'tinyint' THEN 255 WHEN 'smallint' THEN 65535 WHEN 'mediumint' THEN 16777215 WHEN 'int' THEN 4294967295 WHEN 'bigint' THEN 18446744073709551615 END >> IF(LOCATE('unsigned', COLUMN_TYPE) > 0, 0, 1) ) AS MAX_VALUE,
    AUTO_INCREMENT,
    AUTO_INCREMENT / ( CASE DATA_TYPE WHEN 'tinyint' THEN 255 WHEN 'smallint' THEN 65535 WHEN 'mediumint' THEN 16777215 WHEN 'int' THEN 4294967295 WHEN 'bigint' THEN 18446744073709551615 END >> IF(LOCATE('unsigned', COLUMN_TYPE) > 0, 0, 1) ) AS AUTO_INCREMENT_RATIO
    FROM INFORMATION_SCHEMA.COLUMNS
    INNER JOIN INFORMATION_SCHEMA.TABLES USING (TABLE_SCHEMA, TABLE_NAME)
    WHERE TABLE_SCHEMA = 'sakila' AND EXTRA='auto_increment' ";
    //SQL query ends

    //execute the query and get the response
    $result = $sql->getData($query)
    	or die ($sql->error);
    //store the entire response
    $response = array();
    //the array that will hold the titles and links
    $posts = array();
    //parse mysql table response to create a php array object
    while($row = mysqli_fetch_assoc($result))
    {
        $table_name = $row['TABLE_NAME'];
        $column_name = $row['COLUMN_NAME'];
        $data_type = $row['DATA_TYPE'];
        $column_type = $row['COLUMN_TYPE'];
        $is_unsigned = $row['IS_UNSIGNED'];
        $max_value = $row['MAX_VALUE'];
        $auto_increment = $row['AUTO_INCREMENT'];
        $auto_increment_ratio = $row['AUTO_INCREMENT_RATIO'];
        //each item from the rows go in their respective vars and into the posts array
        $table[] = array('TABLE_NAME'=> $table_name, 'COLUMN_NAME'=> $column_name, 'DATA_TYPE'=> $data_type, 'COLUMN_TYPE'=> $column_type, 'IS_UNSIGNED'=> $is_unsigned, 'MAX_VALUE'=> $max_value, 'AUTO_INCREMENT'=> $auto_increment, 'AUTO_INCREMENT_RATIO'=> $auto_increment_ratio);
    }

    $response = $table;

    //show alerts for tables having autoinc ids nearing $warning percent completion
    $warning = 0.02;

    //find auto inc ratio's from response and create strings for sending in message
    foreach ($response as $tabble) {
      $value =  $tabble['AUTO_INCREMENT_RATIO'];
      if($value >= $warning){
        $field1Values .= "_".$tabble['TABLE_NAME']."_\n";
        $field4Values .= ($tabble['AUTO_INCREMENT_RATIO']*100)."\n";
      }
    }
    //to remove the newline character at the end
    $field1Values = substr($field1Values, 0, -1);
    $field4Values = substr($field4Values, 0, -1);

    $warning *= 100;

    //create JSON object from php array Object
    $field1 = array(
        "title"=> "Table Name",
        "value"=> $field1Values,
        "short"=> true
    );
    /*
    //to show the max size of the auto inc columns
    $field2 = array(
        "title"=> "MAX_SIZE",
        "value"=> "12345678\n255",
        "short"=> true
    );
    //to show the current auto inc id value in the tables
    $field3 = array(
        "title"=> "AUTO_INC_ID",
        "value"=> "90022\n124",
        "short"=> true
    );
    */
    $field4 = array(
        "title"=> "% COMPLETION OF COLUMN",
        "value"=> $field4Values,
        "short"=> true
    );

    $attachments = array(
        "fallback"=> "This attachement isn't supported.",
        "title"=> "TABLE AUTO_INC_IDS ALERT",
        "color"=> "warning",
        "pretext"=> "Tables in $database database whose auto increment IDs are over $warning% complete",
        "author_name"=> "Shimanta",
        "fields"=> array($field1, $field4),
        "mrkdwn_in"=> ["text", "fields"]
    );

    $alert = array(
        "username"=> "Shimanta Bhuyan",
        "icon_url"=> "example.com/img/icon.jpg",
        "attachments"=> array($attachments),
        "footer"=> "Built with Slack API"
      );

    $alert = json_encode($alert);

    //require this to send to SLACK
    require("sendToSlack.php");

    //webHook URL
    $url  = "https://hooks.slack.com/services/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";

    //send to SLACK!!!
    $msg = sendToSlack($url, $alert);

    //error handling
    if($msg==1)
      echo "Message sent successfully!";
    else
      echo $msg;
?>
