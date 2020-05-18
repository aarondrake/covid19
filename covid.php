<?php

# dateRep,day,month,year,cases,deaths,countriesAndTerritories,geoId,countryterritoryCode,popData2018,continentExp
#    0     1    2    3     4     5          6                   7          8                9            10
define('DAY',1);
define('MONTH',2);
define('YEAR',3);
define('CASES',4);
define('DEATHS',5);
define('COUNTRY',6);
define('GEO',7);
define('POPULATION',9);
define('CONTINENT',10);

$lines = readCovidFile('download.csv');
echo "FILES HAS ".count($lines)." lines\n";
$deaths = [];
$deaths_by_continent = [];
$cases_by_continent = [];
$infections = [];
$skipped = [];
$headers = array_shift($lines);
foreach ($lines as $line)
{
	$day = $line[DAY];
	$yr = $line[YEAR];
	$mth = $line[MONTH];
	$country = $line[COUNTRY];
	$code = $line[GEO];
	$pop = $line[POPULATION];
	$dead = $line[DEATHS];
	$cases = $line[CASES];
	$continent = $line[CONTINENT];
	if (!isset($countries[$country]))
	{
		$countries[$code] = ['country' => $country, 'pop' => $pop,];
	}
	if ($pop > 10000000) {
		$date = "${yr}-" . sprintf('%02d', $mth) . "-" . sprintf('%02d', $day);
		$deaths[$date] = setCounts($deaths, $date, $dead, $code);
		$infections[$date] = setCounts($infections, $date, $cases, $code);
		$deaths_by_continent[$date] = setCounts($deaths_by_continent, $date, $dead, $continent);
		$cases_by_continent[$date] = setCounts($cases_by_continent, $date, $cases, $continent);
	}
	else
	{
		$skipped[$country] = 1;
	}
}
echo "skipped ".count($skipped)." out of ".count($countries)." countries\n";

$continents_file = createMatrix($deaths_by_continent, 'continents.csv');
$countries_file = createMatrix($deaths, 'countries.csv');


#-------------------------------------------------------------

function createMatrix($list, $name = 'data.csv')
{
	$file = fopen($name,'w');
	$firstdate =array_keys($list)[0];
	$headers =  array_keys($list[$firstdate]);
	$heading_line = array_merge(['date'],$headers);
	fputcsv($file, $heading_line);

	foreach ($list as $date => $counts)
	{
		$fields = [$date];
		foreach ($headers as $header)
		{
			$fields[] = $counts[$header];
		}
		fputcsv($file, $fields);
	}

	fclose($file);
	return $fields;
}


function setCounts($data_list = [], $date, $number, $code)
{
	if (!isset($data_list[$date][$code]) )
	{
		$data_list[$date][$code] = 0;
	}
	$data = $data_list[$date];
	$data[$code] += $number;
	return $data;
}

function readCovidFile($name)
{
	if (! file_exists($name))
	{
		$name = "~/Downloads/$name";
	}
	$return_data = [];
	if (($handle = fopen("$name", "r")) !== FALSE)
	{
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
		{
			$return_data[] = $data;
		}
		fclose($handle);
	}
	else
	{
		echo "FILE $name NOT FOUND!\n";
		exit(1);
	}
	return $return_data;
}