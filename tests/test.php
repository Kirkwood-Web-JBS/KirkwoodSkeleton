<?php
require_once __DIR__ . '/../vendor/autoload.php';
//session_start();

use KirkwoodSkeleton\Layouts\LogInOut;
use KirkwoodSkeleton\Layouts\DefaultHeaderBody1Page2Col;

$page = new LogInOut("test.php?r=" . \KirkwoodSkeleton\Helpers\Functions::getRoute());
if (!$page->draw()){
  $page = new DefaultHeaderBody1Page2Col();
  $page->getHeader()->setTitle("Whoa...sweet trick");
  $page->draw();
}

?>



