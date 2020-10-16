<?php 
error_reporting(E_ALL);
$output = shell_exec("ls");
echo "<pre>$output</pre>"; 
exit;