<?php

require_once('../src/Jobvite.php');
date_default_timezone_set('America/Los_Angeles');

$jobvite = new Jobvite('qzZ9Vfwm');
$jobvite->build();

foreach ($jobvite->categoryListBy('category') as $category => $jobCount) {
  if (count($partTime = $jobvite->positions(array('category' => $category, 'jobType' => 'Part-Time')))) {
    echo "$category part time positions:\n";
    foreach ($partTime as $position) {
      echo "\t{$position->title}\n";
    }
  }
  if (count($fullTime = $jobvite->positions(array('category' => $category, 'jobType' => 'Full-Time')))) {
    echo "$category full time positions:\n";
    foreach ($fullTime as $position) {
      echo "\t{$position['title']}\n";
    }
  }  
  echo "\n";
}