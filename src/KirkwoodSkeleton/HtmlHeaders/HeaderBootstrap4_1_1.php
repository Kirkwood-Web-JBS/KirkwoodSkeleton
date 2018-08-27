<?php

namespace KirkwoodSkeleton\HtmlHeaders;

class HeaderHtmlBootstrap4_1_1 extends HeaderHtml
{
  public function __construct()
  {
    $this->addHeadElements([
        ["meta", ["name" => "viewport", "content" => "width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0"], "viewport"],
        ["meta", ["http-equiv" => "X-UA-Compatible", "content" => "IE=edge"], "IEEdge"],
        ["script", "/includes/bootstrap_4-1-1/js/bootstrap.bundle.min.js", "bootstrap", ["after jQuery"]],
        ["script", "/includes/jquery-3.3.1.min.js", 'jquery'],
        ["script", "/includes/fonts/fontawesome-free-5.1.0-web/js/all.js", "fontawesome", ["after jquery"]],
        ["style", "/includes/bootstrap_4-1-1/css/bootstrap.css", "bootstrap"]
    ]);
  }
}