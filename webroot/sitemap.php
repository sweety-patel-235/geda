<?php
//open uploaded csv file with read only mode
$current_dir	= __DIR__;
$csvpath 		= $current_dir."/tmp/sitemap.csv";
$csvFile 		= fopen($csvpath, 'r');

//skip first line
fgetcsv($csvFile);

//parse data from csv file line by line
$xmlstring = '<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->';
$timestamp  = date("Y-m-d H:i:s");
$timestamp  = date('c', strtotime($timestamp));
$row        = 1;
while(($line = fgetcsv($csvFile)) !== FALSE) {
    if ($row <= 82) {
        $priority = "0.80";
    } else if ($row > 82 && $row <= 122) {
        $priority = "0.64";
    } else {
        $priority = "0.60";
    }
    $url        = str_replace("http://97.107.178.143/","",$line[0]);
    $url        = str_replace("/shop/","shop/",$url);
    $url        = "https://www.doctorsolve.com/".$url;
    $xmlstring .= '
    <url>
        <loc>'.$url.'</loc>
        <lastmod>'.$timestamp.'</lastmod>
        <priority>'.$priority.'</priority>
    </url>';
    $row++;
}
//close opened csv file
fclose($csvFile);
$xmlstring .= '</urlset>';
$xmlpath        = $current_dir."/tmp/sitemap.xml";
$fp = fopen($xmlpath,"rw+");
fwrite($fp,$xmlstring);
fclose($fp);