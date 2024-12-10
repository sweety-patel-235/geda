<?php
//DB details
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'ahasolar';

//Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Unable to connect database: " . $db->connect_error);
}

//open uploaded csv file with read only mode
$current_dir	= __DIR__;
$csvpath 		= $current_dir."/tmp/member.csv";
$csvFile 		= fopen($csvpath, 'r');

//skip first line
fgetcsv($csvFile);

//parse data from csv file line by line
while(($line = fgetcsv($csvFile)) !== FALSE) {
	/*
	0 id
	1 Empid
	2 Name
	3 Designation
	4 Area_code
	5 Circle
	6 Div_code
	7 Sub_Division
	8 Sec_code
	9 phone
	10 Email
	11 Aadhar
	12 level
	13 status
	14 Roll
	15 usercode
	16 Designationname
	*/

    $area 			= $line[4];
    $circle 		= $line[5];
    $division 		= $line[6];
    $subdivision 	= $line[7];
    $section 		= $line[8];
    $member_type 	= 6002; //Discom
    $name 			= $line[2];
    $designation 	= $line[16];
    $password 		= "";
    $state 			= 22; //Jarkhand
    $email 			= $line[15];
    $member_email 	= trim(str_replace("\n","",$line[10]));
    $mobile			= $line[9];
    $ref_emp_id		= $line[1];
    $status 		= 1;
    $created 		= date("Y-m-d H:i:s");
    $modified 		= date("Y-m-d H:i:s");

    $INSERTSQL 		= "	INSERT INTO members SET 
    					area = '".$area."',
    					circle = '".$circle."',
    					division = '".$division."',
    					subdivision = '".$subdivision."',
    					section = '".$section."',
    					member_type = '".$member_type."',
    					name = '".$name."',
    					designation = '".$designation."',
    					password = '".$password."',
    					email = '".$email."',
    					member_email = '".(strtolower($member_email) != "email"?$member_email:"")."',
    					state = '".$state."',
    					mobile = '".$mobile."',
    					ref_emp_id = '".$ref_emp_id."',
    					status = '".$status."',
    					created = '".$created."',
    					modified = '".$modified."'";
    //$db->query($INSERTSQL);
}
//close opened csv file
fclose($csvFile);