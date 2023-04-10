<?php

include __DIR__ . '/../src/RenameReplacer.php';

use epitcher\RenameReplacer;
use PHPUnit\Framework\TestCase;

class RenameReplacerTest extends TestCase
{
    /**
     * @var array<mixed> $config
     */
    private $config;
    /**
     * @var RenameReplacer $renameReplacer
     */
    private $renameReplacer;

    protected function setUp(): void
    {
        $this->config = [
            'directory' => 'tmp_path',
            'rename_file' => [
                'old' => 'new',
            ],
            'replace_content' => [
                'foo' => 'bar',
            ],
        ];
        $this->renameReplacer = new RenameReplacer($this->config);
    }

    public function testRenameDirectory() : void
    {
        // Prepare test directory
        $oldDir = 'tmp_path/old_dir';
        if(!is_dir($oldDir)) {
            mkdir($oldDir, 0777, true);
        }

        // Test renaming the directory
        $newDir = $this->renameReplacer->rename_directory($oldDir);
        $this->assertEquals('tmp_path' . DIRECTORY_SEPARATOR . 'new_dir', $newDir);

        // Cleanup
        rmdir($newDir);
    }

    public function testRenameFile(): void
    {
        // Prepare test file
        $root = 'tmp_path';
        $oldFile = 'old.txt';
        touch($root . DIRECTORY_SEPARATOR . $oldFile);

        // Test renaming the file
        $newFile = $this->renameReplacer->rename_file($root, $oldFile);
        $this->assertEquals('new.txt', $newFile);

        // Cleanup
        unlink($root . DIRECTORY_SEPARATOR . $newFile);
    }

    public function testReplaceText() : void
    {
        // Prepare test file
        $root = 'tmp_path';
        $file = 'replace_test.txt';
        file_put_contents($root . DIRECTORY_SEPARATOR . $file, 'foo');

        // Test replacing text in the file
        $this->renameReplacer->replace_text($root, $file);
        $content = file_get_contents($root . DIRECTORY_SEPARATOR . $file);
        $this->assertEquals('bar', $content);

        // Cleanup
        unlink($root . DIRECTORY_SEPARATOR . $file);
    }
}
