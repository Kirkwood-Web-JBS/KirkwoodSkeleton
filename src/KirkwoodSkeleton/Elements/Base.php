<?php

namespace KirkwoodSkeleton\Elements;

abstract class Base
{
  protected $defaultConfig = "";
  protected $configs = [];

  public function __construct($configFile = "")
  {
    $this->loadConfigFile($configFile);
  }

  protected function setDefaultConfig($newFile)
  {
    if ($newFile && !$this->defaultConfig) {
      $this->defaultConfig = $newFile;
    }
  }

  public static function createStyleString(array $styles)
  {
    $ret = "";
    foreach ($styles as $key=>$value){
      $ret .= "$key: $value; ";
    }

    return $ret;
  }

  public static function formatStyles(array $styles)
  {
    $strings = [];
    if (count($styles) == 0) {
      return "";
    }
    foreach ($styles as $key => $value) {
      $strings[] = "$key: $value;";
    }

    return "style='" . self::createStyleString($styles) . "' ";
  }

  public static function createClassString(array $classes)
  {
    if (empty($classes)) {
      return "";
    }

    return implode(" ", $classes) . " ";
  }

  public static function formatClasses(array $classes)
  {
    if (count($classes) === 0) {
      return "";
    }
    return "class='" . self::createClassString($classes) . "' ";
  }

  protected function genClassStyles($route)
  {
    $dat = $this->traverseRoute($route . "/classes");
    if (!isset($dat[0]["classes"], $dat[0]["styles"])) {
      $this->handleException(new \Exception("Either classes or styles is not defined for $route"));
    }
    return self::formatClasses($dat[0]["classes"]) . self::formatStyles($dat[0]["styles"]);
  }

  protected function handleException(\Exception $e)
  {
    error_log($e->getMessage());
    error_log($e->getTraceAsString());
    die();
  }

  protected function loadConfigFile($configFile = "")
  {
    try {
      if ($configFile === "") {
        $configFile = $this->defaultConfig;
      }

      if ($configFile === "") {
        throw new \Exception("No default config file provided.");
      }

      $configContents = file_get_contents($configFile);

      if (!$configContents) {
        throw new \Exception("Unable to load config file: $configFile");
      }

      $this->configs = json_decode($configContents, true);


    } catch (\Exception $e) {
      $this->handleException($e);
    }
  }

  protected function setConfigs(array $configs)
  {
    $this->configs = $configs;
  }

  protected function traverseRoute($route)
  {
    try {
      $route = explode('/', $route);
      $current = &$this->configs;
      $routeLength = count($route);
      for ($i = 0; $i < $routeLength - 1; $i++) {
        $item = $route[$i];
        if (!isset($current[$item])) {
          throw new \Exception("Cannot traverse route: $route because $item is not a child.");
        }

        $current = &$current[$item];
      }

      if (!isset($current[$route[$routeLength - 1]])) {
        throw new \Exception("Cannot traverse route: $route because {$route[$routeLength-1]} is not a child.");
      }

      return [&$current, $route[$routeLength - 1]];
    } catch (\Exception $e) {
      $this->handleException($e);
      die();
    }
  }

  public function setStringConfig($route, $value)
  {
    try {
      $dat = $this->traverseRoute($route);
      if (is_array($dat[0][$dat[1]])) {
        throw new \Exception("Cannot set a string to an array-typed configuration.");
      }
      $dat[0][$dat[1]] = $value;
    } catch (\Exception $e) {
      $this->handleException($e);
    }
  }

  public function removeClasses($route, $classes)
  {
    $dat = $this->traverseRoute($route);
    if (!isset($dat[0][$dat[1]]["classes"])) {
      $this->handleException(new \Exception("$route has no classes by default."));
    }
    if (is_array($classes)) {
      $dat[0][$dat[1]]["classes"] = array_diff($dat[0][$dat[1]]["classes"], $classes);
    } elseif ($classes !== "" && ($pos = array_search($classes, $dat[0][$dat[1]]["classes"])) !== false) {
      array_splice($dat[0][$dat[1]]["classes"], $pos, 1);
    }
  }

  public function addClasses($route, $classes)
  {
    $dat = $this->traverseRoute($route);
    if (!isset($dat[0][$dat[1]]["classes"])) {
      $this->handleException(new \Exception("$route has no classes by default."));
    }
    if (is_array($classes)) {
      $dat[0][$dat[1]]["classes"] = array_merge($dat[0][$dat[1]]["classes"], $classes);
    } elseif ($classes !== "" && ($pos = array_search($classes, $dat[0][$dat[1]]["classes"])) === false) {
      $dat[0][$dat[1]]["classes"][] = $classes;
    }
  }

  public function replaceClasses($route, $toRemove, $toAdd)
  {
    $this->removeClasses($route, $toRemove);
    $this->addClasses($route, $toAdd);
  }

  public function removeStyles($route, $styles)
  {
    $dat = $this->traverseRoute($route);
    if (!isset($dat[0][$dat[1]]["styles"])) {
      $this->handleException(new \Exception("$route has no styles by default"));
    }

    if (!is_array($styles)) {
      $styles = [$styles];
    }

    foreach ($styles as $style) {
      if (isset($dat[0][$dat[1]]["styles"][$style])) {
        unset($dat[0][$dat[1]]["styles"][$style]);
      }
    }
  }

  // adding will not override defaults
  public function addStyles($route, array $styles)
  {
    $dat = $this->traverseRoute($route);
    if (!isset($dat[0][$dat[1]]["styles"])) {
      $this->handleException(new \Exception("$route has no styles by default"));
    }

    $dat[0][$dat[1]]["styles"] = array_merge($styles, $dat[0][$dat[1]]["styles"]);
  }

  //override defaults
  public function overrideStyles($route, array $styles)
  {
    $dat = $this->traverseRoute($route);
    if (!isset($dat[0][$dat[1]]["styles"])) {
      $this->handleException(new \Exception("$route has no styles by default"));
    }

    $dat[0][$dat[1]]["styles"] = array_merge($dat[0][$dat[1]]["styles"], $styles);
  }

  // will not override defaults still existing after removal
  public function removeAndAddStyles($route, $toRemove, array $toAdd)
  {
    $this->removeStyles($route, $toRemove);
    $this->addStyles($route, $toAdd);
  }

  // will override defaults still existing after removal
  public function removeAndOverrideStyles($route, $toRemove, array $toAdd)
  {
    $this->removeStyles($route, $toRemove);
    $this->overrideStyles($route, $toAdd);
  }

  public abstract function draw();
}