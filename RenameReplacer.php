<?php

namespace epitcher;

class RenameReplacer
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function get_confirmation()
    {
        echo "WARNING: This script is potentially destructive and may irreversibly modify files in the specified directory and its subdirectories. Do you want to continue?\n";
        echo "We recommend version control be enabled prior to running\n";
        echo "[y/N] > ";
        $response = trim(fgets(STDIN));
        return strtolower($response) == 'y';
    }

    public function process_directory($dir)
    {
        if(empty($dir)) {
            return;
        }
        $entries = scandir($dir);
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..' || $entry[0] === '.') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($path)) {
                $new_path = $this->rename_directory($path);
                $this->process_directory($new_path);
            } else {
                $filename = $this->rename_file($dir, $entry);
                $this->replace_text($dir, $filename);
            }
        }
    }

    public function rename_directory($root)
    {
        foreach ($this->config['rename_file'] as $key => $value) {
            if (strpos(basename($root), $key) !== false) {
                $new_dirname = dirname($root) . DIRECTORY_SEPARATOR . str_replace($key, $value, basename($root));
                rename($root, $new_dirname);
                return $new_dirname;
            }
        }
        return $root;
    }

    public function rename_file($root, $filename)
    {
        foreach ($this->config['rename_file'] as $key => $value) {
            if (strpos($filename, $key) !== false) {
                $new_filename = str_replace($key, $value, $filename);
                rename($root . DIRECTORY_SEPARATOR . $filename, $root . DIRECTORY_SEPARATOR . $new_filename);
                return $new_filename;
            }
        }
        return $filename;
    }

    public function replace_text($root, $filename)
    {
        $file_path = $root . DIRECTORY_SEPARATOR . $filename;
        $file_contents = file_get_contents($file_path);

        if ($file_contents === false) {
            echo "Failed to open file {$file_path}\n";
            return;
        }

        foreach ($this->config['replace_content'] as $key => $value) {
            $file_contents = str_replace($key, $value, $file_contents);
        }

        file_put_contents($file_path, $file_contents);
    }

    public function run()
    {
        if ($this->get_confirmation()) {
            $this->process_directory($this->config['directory']);
        } else {
            echo "Exiting script.\n";
            exit();
        }
    }
}
