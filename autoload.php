<?php
/**
 * Anonymous function that registers a custom autoloader
 */
return function ($prefix, $baseDir) {
    spl_autoload_register(function ($class) use ($prefix, $baseDir) {
        // does the class use the namespace prefix?
       
       	//error_log('xxxxx Finding Class '.$class.' '.$prefix .' '.$baseDir);
        $len = strlen($prefix);
        //error_log('xxxxx strlen '.$len);
        if (strncmp($prefix, $class, $len) !== 0) {
            // no, move to the next registered autoloader
            return;
        }

        // get the relative class name
        $relative_class = substr($class, $len);
        // error_log('xxxxx $relative_class '.$relative_class);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $baseDir . str_replace('\\', '/', $relative_class) . '.php';
        // error_log('xxxxx $file '.$file);

        // if the file exists, require it
        if (file_exists($file)) {
            require $file;
        }
    });
};