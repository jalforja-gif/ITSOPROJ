<?php
$host = "127.0.0.1";
$port = 5000;

// Log file para sa ML API
$log_file = "C:\\xampp\\htdocs\\ITSO_System\\ml_api\\ml_api.log";

// Full path sa Python executable at serve.py
// Gumamit ng python.exe para makita ang output
$python_exe = "C:\\Users\\weji\\AppData\\Local\\Programs\\Python\\Python311\\python.exe";
$ml_api_path = "C:\\xampp\\htdocs\\ITSO_System\\ml_api\\serve.py";

// Check kung ML API ay running
$connection = @fsockopen($host, $port);

if (!$connection) {
    // Launch ML API in background; models may take a long time to load
    $cmd = "start /B \"\" cmd /c \"\"$python_exe\" \"$ml_api_path\" >> \"$log_file\" 2>&1\"";
    pclose(popen($cmd, "r"));

    // try once or twice quickly just to see if port opens
    $fp = @fsockopen($host, $port, $errno, $errstr, 1);
    if ($fp) {
        fclose($fp);
    } else {
        // still not ready; we'll let it continue loading in background
        // writing to the log may fail if another process has it open, so suppress warnings
        @error_log("[start_ml] ML API not ready yet; continuing without blocking.\n", 3, $log_file);
    }
} else {
    // Already running
    fclose($connection);
}
?>