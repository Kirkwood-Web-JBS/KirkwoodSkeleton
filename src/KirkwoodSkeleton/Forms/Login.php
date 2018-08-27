<?php

namespace KirkwoodSkeleton\Forms;

use KirkwoodSkeleton\Flags\Login as LoginFlags;

class Login
{

  protected $defaultConfig;
  protected $configs = [];

  public function __construct($configFile = "")
  {
    $this->defaultConfig = __DIR__ . "/../DefaultConfigs/login.json";
    $this->loadConfigFile($configFile);
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

  public static function createErrorMessage($text)
  {
    return "<small class='text-danger'>$text</small>";
  }

  public static function createSuccessMessage($text)
  {
    return "<small class='text-success'>$text</small>";
  }

  public function export($pathToPage, $errFlag = 0, $wrapWithCard = true, $showLinks = true, $allowAutocomplete = true)
  {
    $knumberInvalid = "";
    $passwordInvalid = "";
    $badLogin = "";
    $loggedOut = "";

    $autoComplete = $allowAutocomplete ? "" : "autocomplete=\"off\"";

    if (strpos($pathToPage, "?") !== false) {
      $pathToPage .= "&login_attempt=true";
    } else {
      $pathToPage .= "?login_attempt=true";
    }

    if (($errFlag & (LoginFlags::EMPTY_KNUMBER || LoginFlags::INVALID_KNUMBER)) > 0) {
      $knumberInvalid = static::createErrorMessage("Your knumber must be non-empty and start with a k");
    }

    if ($errFlag & LoginFlags::EMPTY_PASSWORD) {
      $passwordInvalid = static::createErrorMessage("Your password must be non-empty");
    }

    if ($errFlag & LoginFlags::INVALID_KNUMBER_PASSWORD_COMBO) {
      $badLogin = static::createErrorMessage("Invalid knumber/password combo. Please try again.");
    }

    if ($errFlag & LoginFlags::LOGGED_OUT) {
      $loggedOut = static::createSuccessMessage("You have successfully logged out!");
    }

    echo <<<FORM
<form method="post" action="$pathToPage">
FORM;

    if ($wrapWithCard) {
      echo "<div class=\"card\"><div class='card-body'>";
    }

    ?>
      <div class="form-group">
          <label for="knumber">Knumber</label>
          <input type="text" id="knumber" name="knumber" placeholder="k0000000" class="form-control" <?=$autoComplete;?>>
        <?= $knumberInvalid; ?>
      </div>
      <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" <?=$autoComplete;?>>
        <?= $passwordInvalid; ?>
      </div>
      <div class="form-group" align="right">
          <button class="btn btn-primary" type="submit">Continue</button>
      </div>
      <div class="form-group" align="right">
        <?= $badLogin; ?>
        <?= $loggedOut; ?>
      </div>

    <?php
    if ($showLinks) {
      ?>
        <div class="form-group mt-3 mb-0">
            <div class="d-flex flex-column flex-sm-row justify-content-around">
                <a target="_blank" href="<?= $this->configs["forgotKnumber"]; ?>">
                    <small>Forgot k-number</small>
                </a>
                <span class="d-none d-sm-inline">|</span>
                <a target="_blank" href="<?= $this->configs["forgotPassword"]; ?>">
                    <small>Forgot password</small>
                </a>
                <span class="d-none d-sm-inline">|</span>
                <a target="_blank" href="<?= $this->configs["establishPassword"]; ?>">
                    <small>Establish password</small>
                </a>
                <span class="d-none d-sm-inline">|</span>
                <a target="_blank" href="<?= $this->configs["privacyPolicy"]; ?>">
                    <small>Privacy Policy</small>
                </a>
            </div>
        </div>
      <?php
    }

    if ($wrapWithCard) {
      echo "</div></div>";
    }

    echo "</form>";

  }
}