<?php

namespace KirkwoodSkeleton\Helpers;

class Functions
{
  public static function getRoute()
  {
    return isset($_GET["r"]) ? $_GET["r"] : "/";
  }

  public static function getUriWithoutGetParams()
  {
    $temp = $_SERVER["REQUEST_URI"];
    return preg_replace("/\?.*/", "", $temp);
  }
}