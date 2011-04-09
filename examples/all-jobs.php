<?php

require_once('../src/Jobvite.php');
date_default_timezone_set('America/Los_Angeles');

$jobvite = new Jobvite('qzZ9Vfwm');
$jobvite->build();

foreach ($jobvite->positions() as $position) {
  echo "{$position->title}\n";
}