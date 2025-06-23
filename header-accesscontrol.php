<?php 
//add to your header.php in Style
require_once 'access_control.php';
?>

<?php
//save as  access_control.php and add to your project.
//you can add the allowed ips here or in a settings.conf file. If you lock your self out, just change disable_ip_check to 1.
$configFile = __DIR__ . '/settings.conf';
$config = [
    'allowed_ips' => '',
    'disable_ip_check' => '0'
];

// Load config if exists
if (file_exists($configFile)) {
    $lines = file($configFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') === false) continue;
        list($key, $val) = explode('=', $line, 2);
        if (isset($config[$key])) {
            $config[$key] = $val;
        }
    }
}

// IP restriction (skip if disabled)
function ip_in_range($ip, $range) {
    if (strpos($range, '/') === false) {
        return trim($ip) === trim($range);
    }
    list($subnet, $bits) = explode('/', $range);
    $ip = ip2long($ip);
    $subnet = ip2long($subnet);
    $mask = -1 << (32 - $bits);
    $subnet &= $mask;
    return ($ip & $mask) === $subnet;
}

if ($config['disable_ip_check'] !== '1' && !empty($config['allowed_ips'])) {
    $allowed = false;
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $ip_ranges = array_map('trim', explode(',', $config['allowed_ips']));
    foreach ($ip_ranges as $range) {
        if ($range && ip_in_range($user_ip, $range)) {
            $allowed = true;
            break;
        }
    }
    if (!$allowed) {
        header('HTTP/1.1 403 Forbidden');
        echo "<h2 style='color:red;text-align:center;margin-top:50px;'>Access Denied: Your IP ($user_ip) is not allowed.<br>
        Please contact your administrator.</h2>";
        exit;
    }
}
?>
