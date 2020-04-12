<?php
require('config.inc.php');

if (!isset($config['base_path']) || ($config['base_path'] == NULL)) {
  $config['base_path'] = dirname(__FILE__);
}

$db = mysqli_connect($config['db']['host'],
                     $config['db']['user'],
                     $config['db']['pass'],
                     $config['db']['name']);

/**
 * Function to assist in logging errors
 *
 * @param $db The db object
 *
 * @param $type The error type
 *
 * @param $input The input that caused the error
 *
 * @param $output The errorneous output
 *
 * @param $users_id The logged in user or none
 *
 * Writes to database or text file if db error.
 *
 */
function log_error($db, $type, $input, $output, $users_id = NULL) {
  
  $types = array('error',
                 'notice');

  if ($_SESSION['user']['id'] == NULL) {
    $users_id = 1; // System
  } else {
    $users_id = $_SESSION['user']['id'];
  }
  
  $url = (isset($_SERVER['HTTPS']) &&
         $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
         "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  
  if (in_array($type, $types)) {
    // Type is valid, insert into watchdog table
    $filename = $_SERVER["SCRIPT_NAME"];
    
    $query = "INSERT INTO watchdog (
                users_id,
                type,
                message,
                input,
                output,
                file,
                url
              ) VALUES (
                '" . mysqli_real_escape_string($db, $users_id) . "',
                '" . mysqli_real_escape_string($db, $type) . "',
                '" . mysqli_real_escape_string($db, $message) . "',
                '" . mysqli_real_escape_string($db, $input) . "',
                '" . mysqli_real_escape_string($db, $output) . "',
                '" . mysqli_real_escape_string($db, $filename) . "',
                '" . mysqli_real_escape_string($db, $url) . "'
    )";
    
    if (!mysqli_query($db, $query)) {
      file_put_contents($config['base_path'] . '/watchdog.log',
                        "Watchdog query failed:\n\n" . $query . "\n\n" .
                        mysqli_error($db) . PHP_EOL,
                        FILE_APPEND | LOCK_EX);
    }
  } else {
    file_put_contents($config['base_path'] . '/watchdog.log',
                      "Invalid error type:\n\n" . $type . "\n\n" .
                      $input . "\n\n" . $output . PHP_EOL,
                      FILE_APPEND | LOCK_EX);
  }
}

/**
 * Function to validate an email address
 *
 * @param $value The value to be checked
 *
 * @return array containing the clean value, TRUE or FALSE
 *
 * @todo correct malformed addresses (ex. '.' where '@' expected)
 *
 * This is not just a pass/fail test, but an optimistic function that tries
 * to return a cleanly formatted and usable value from user input.
 *
 */
function validate_email($value) {
  preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
  $value, $matches);
  if (isset($matches[0])) {
    $clean_value = $matches[0];
  } else {
    $clean_value = '';
  }
  if (strlen($clean_value) >= 5
      && strlen($clean_value) <= 256) {
    return array('clean_value' => $clean_value, 'pass' => TRUE);
  } else {
    //Failed validation
    //Set return value
    if (isset($clean_value)) {
      $return_value = $clean_value;
    } else {
      $return_value = '';
    }
    return array('clean_value' => $return_value, 'pass' => FALSE);
  }
}

/**
 * Function to check if current user is flooding us
 *
 * @param $ip The IP address to be checked
 *
 * @return Returns TRUE if user is flooding or FALSE if not
 */
function check_flood($ip) {
  global $db;
  if (isset($db) && $stmt = $db->prepare("
  SELECT
    count(id) as hits
  FROM
    flood
  WHERE
    ip = ?
  AND
    created >= DATE_SUB(NOW(),
    INTERVAL 6 HOUR)
  ")) {
    $stmt->bind_param("s", $ip);
    if ($stmt->execute()) {
      $stmt->bind_result($hits);
      $stmt->fetch();
    }
    $stmt->close();
  } else {
    return FALSE;
  }
  if ($hits > 5) {
    return TRUE;
  } else {
    return FALSE;
  }
}


/**
 * Function to format a duration
 *
 * @param $seconds The number of seconds to format
 *
 * @return Formatted time string.
 */
function format_duration($seconds) {
  $hours = floor($seconds / 3600);
  $minutes = floor(($seconds / 60) % 60);
  $seconds = $seconds % 60;
  
  $duration = "$seconds Seconds";
  
  if ($minutes > 0) {
    $duration = "$minutes Minutes ".$duration;
  }
  
  if ($hours > 0) {
    $duration = "$hours Hours ".$duration;
  }
  
  return $duration;
}

/**
 * Function to trim a string and add trailing dots
 *
 * @param $str The string in question
 *
 * @param $len The max length
 *
 * @return Shortened string.
 */
function format_trim($str, $len) {
  if (strlen($str) > $len) {
    $str = substr($str, 0, $len) . '...';
  }
  return $str;
}

/**
 * Function to format a location
 *
 * @param $city The city
 *
 * @param $state The state
 *
 * @param $country The country
 *
 * @return Formatted string.
 */
function format_location($city, $state, $country) {
  if (strlen($country) > 0) {
    $value = $country;
  }
  
  if (strlen($state) > 0) {
    $value = $state . " " . $value;
  }
  
  if (strlen($city) > 0) {
    $value = $city . ", " . $value;
  }
  
  return $value;
}

/**
 * Function to format a Unix timestamp
 *
 * @param $utime Unix timestamp
 *
 * @return Formatted string.
 */
function format_time($utime) {
  return date("D M j g:i:s A T", $utime);
}

/**
 * Function to make bcrypt password hash
 *
 * @param $pass User entered password
 *
 * @return Hash string.
 */
function mkpass($pass) {
  return password_hash($pass, PASSWORD_BCRYPT);
}
