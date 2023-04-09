<?php

function load_config($file_path)
{
    return json_decode(file_get_contents($file_path), true);
}

function get_confirmation()
{
    echo "WARNING: This script is potentially destructive and may irreversibly modify files in the specified directory and its subdirectories. Do you want to continue?\n";
    echo "We recommend version control be enabled prior to running\n";
    echo "[y/N] > ";
    $response = trim(fgets(STDIN));
    return strtolower($response) == 'y';
}

function process_directory($config, $dir)
{
    $entries = scandir($dir);
    foreach ($entries as $entry) {
        if ($entry === '.' || $entry === '..' || $entry[0] === '.') {
            continue;
        }

        $path = $dir . DIRECTORY_SEPARATOR . $entry;

        if (is_dir($path)) {
            $new_path = rename_directory($config, $path);
            process_directory($config, $new_path);
        } else {
            $filename = rename_file($config, $dir, $entry);
            replace_text($config, $dir, $filename);
        }
    }
}

function rename_directory($config, $root)
{
    foreach ($config['rename_file'] as $key => $value) {
        if (strpos(basename($root), $key) !== false) {
            $new_dirname = dirname($root) . DIRECTORY_SEPARATOR . str_replace($key, $value, basename($root));
            rename($root, $new_dirname);
            return $new_dirname;
        }
    }
    return $root;
}

function rename_file($config, $root, $filename)
{
    foreach ($config['rename_file'] as $key => $value) {
        if (strpos($filename, $key) !== false) {
            $new_filename = str_replace($key, $value, $filename);
            rename($root . DIRECTORY_SEPARATOR . $filename, $root . DIRECTORY_SEPARATOR . $new_filename);
            return $new_filename;
        }
    }
    return $filename;
}

function replace_text($config, $root, $filename)
{
    $file_path = $root . DIRECTORY_SEPARATOR . $filename;
    $file_contents = file_get_contents($file_path);

    if ($file_contents === false) {
        echo "Failed to open file {$file_path}\n";
        return;
    }

    foreach ($config['replace_content'] as $key => $value) {
        $file_contents = str_replace($key, $value, $file_contents);
    }

    file_put_contents($file_path, $file_contents);
}

$config = load_config('config.json');

if (get_confirmation()) {
    process_directory($config, $config['directory']);
} else {
    echo "Exiting script.\n";
    exit();
}

?>
