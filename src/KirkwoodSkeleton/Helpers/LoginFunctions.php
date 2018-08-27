<?php

namespace KirkwoodSkeleton\Helpers;

use KirkwoodSkeleton\Flags\Login as LoginFlags;

class LoginFunctions{


  public static function isLoggedIn()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    return isset($_SESSION["ldap"]);
  }

  public static function didAttemptLogin()
  {
    return isset($_GET["login_attempt"]);
  }

  public static function didLogout()
  {
    return isset($_GET["logout"]);
  }

  public static function attemptLogin()
  {
    $flag = 0;
    if (!isset($_POST["knumber"]) || $_POST["knumber"] === "") {
      $flag |= LoginFlags::EMPTY_KNUMBER;
    } else {
      $knumber = trim(strtolower($_POST["knumber"]));
      if (preg_match("/k\d{7}/", $knumber) !== 1) {
        $flag |= LoginFlags::INVALID_KNUMBER;
      }
    }

    if (!isset($_POST["password"]) || $_POST["password"] === "") {
      $flag |= LoginFlags::EMPTY_PASSWORD;
    }


    if ($flag > 0) {
      return $flag;
    }

    $ldapUser = static::getLdapUser($knumber, $_POST["password"]);
    if ($ldapUser) {
      $_SESSION["ldap"] = $ldapUser;
      static::performAdditionalLoginLogic();
      return $flag;
    } else {
      return LoginFlags::INVALID_KNUMBER_PASSWORD_COMBO;
    }
  }

  private static function getLdapUser($user, $pass)
  {
    include "/www/session/session_ldap.php";

    if (login($user, $pass)) {
      $user = [];
      $user['user_id'] = $_SESSION['user_id'];
      $user['name'] = $_SESSION['name'];
      $user['first_name'] = $_SESSION['first_name'];
      $user['last_name'] = $_SESSION['last_name'];
      $user["full_name"] = $user["first_name"] . " " . $user["last_name"];
      $user['email'] = $_SESSION['employee_email'];

      return $user;
    }
    return false;
  }

  protected static function performAdditionalLoginLogic(){
  }

  public static function logout()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    session_destroy();
  }
}