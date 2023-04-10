# Directory Renamer and File Content Replacer

[![phpunit](https://github.com/epitcher/rename-replacer-php/actions/workflows/php.yml/badge.svg)](https://github.com/epitcher/rename-replacer-php/actions/workflows/php.yml)

**Disclaimer: This is a translation of [rename-replacer](https://github.com/epitcher/rename-replacer) which I threw together, I would heavily recommend using the original.** 

This is a php script which can **rename files**, **rename directories** and **replace text** within files in a configured path and its subdirectories, based on key-value pairs that you specify in a JSON configuration file.

## Prerequisites

- PHP 5.6+
- A JSON config file with the following structure:

    ```json
    {
        "directory": "/path/to/directory",
        "rename_file": {
            "old_string": "new_string",
            "another_old_string": "another_new_string"
        },
        "replace_content": {
            "old_text": "new_text",
            "another_old_text": "another_new_text"
        }
    }
    ```

### Note
 - The `directory` key should specify the directory that the script will start in.
 - The `rename_file` key is a dictionary where the keys are the strings to be replaced in the file and directory names of all the files within the directory and subdirectories and the values are the strings to replace them with. 
 - The `replace_content` key is a dictionary where the keys are the strings to be replaced within the contents of all the files in the directory and subdirectories and the values are the strings to replace them with.


## Usage
1. Create/Edit JSON config file with the above structure and save it in the repo's root as `config.json`.

2. Run the script using the following command:
    ```bash
    php main.php
    ```

3. The script will walk through the configured directory and its subdirectories, and **rename files** and **replace text** within files based on the key-value pairs specified in the JSON config file.

## Contributing
Contributions to this script are welcome! If you find a bug or have an idea for a new feature, please open an issue or submit a pull request.
