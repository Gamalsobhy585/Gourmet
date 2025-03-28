<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\NodeFinder;

class RemoveUnusedImports extends Command
{
    protected $signature = 'clean:imports {file : The path to the PHP file}';
    protected $description = 'Remove unused imports from a specified PHP file';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return 1;
        }

        $content = file_get_contents($filePath);
        $originalContent = $content;
        
        try {
            $content = $this->removeUnusedImports($content);
        } catch (\Exception $e) {
            $this->error("Error processing file: " . $e->getMessage());
            return 1;
        }

        if ($originalContent !== $content) {
            file_put_contents($filePath, $content);
            $this->info("Unused imports removed from: $filePath");
            return 0;
        }

        $this->info("No unused imports found in: $filePath");
        return 0;
    }

    private function removeUnusedImports(string $content): string
    {
        // Get all used names from the code
        $usedNames = $this->getUsedNamesFromCode($content);
        
        // Match all use statements
        preg_match_all('/^use\s+([^;]+);/m', $content, $matches);
        
        if (empty($matches[1])) {
            return $content; // No imports found
        }

        foreach ($matches[1] as $import) {
            $shortName = $this->getClassShortName($import);
            
            // Check if the class is used
            $isUsed = in_array($import, $usedNames) || 
                     in_array($shortName, $usedNames) ||
                     $this->isClassUsedInDocBlocks($content, $shortName);

            if (!$isUsed) {
                // Remove the import line
                $content = preg_replace('/^use\s+'.preg_quote($import, '/').';\n?/m', '', $content);
            }
        }

        // Clean up multiple empty lines
        $content = preg_replace("/\n\n+/", "\n\n", $content);

        return $content;
    }

    private function getUsedNamesFromCode(string $content): array
    {
        $usedNames = [];
        
        // 1. Find class inheritance (extends)
        preg_match_all('/class\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\s+extends\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', $content, $matches);
        $usedNames = array_merge($usedNames, $matches[1]);
        
        // 2. Find interface implementations
        preg_match_all('/class\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\s+implements\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?:\s*,\s*[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*)/', $content, $matches);
        $implementations = array_map('trim', explode(',', implode(',', $matches[1])));
        $usedNames = array_merge($usedNames, $implementations);
        
        // 3. Find trait usage
        preg_match_all('/use\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*;/', $content, $matches);
        $usedNames = array_merge($usedNames, $matches[1]);
        
        // 4. Find new ClassName()
        preg_match_all('/new\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\(/', $content, $matches);
        $usedNames = array_merge($usedNames, $matches[1]);
        
        // 5. Find ClassName::method()
        preg_match_all('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)::/', $content, $matches);
        $usedNames = array_merge($usedNames, $matches[1]);
        
        // 6. Find type hints and return types
        preg_match_all('/(?:function\s+\w+\s*\([^)]*?\s([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s|\:\s*([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\{)/', $content, $matches);
        $usedNames = array_merge($usedNames, $matches[1], $matches[2]);
        
        // 7. Find catch blocks
        preg_match_all('/catch\s*\(\s*([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', $content, $matches);
        $usedNames = array_merge($usedNames, $matches[1]);
        
        // 8. Find fully qualified class names
        preg_match_all('/\\\\([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?:\\\[a-zA-Z0-9_\x7f-\xff]+)*)/', $content, $matches);
        $usedNames = array_merge($usedNames, $matches[1]);
        
        // 9. Find class names in annotations
        preg_match_all('/@(?:var|return|param|throws)\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\b/', $content, $matches);
        $usedNames = array_merge($usedNames, $matches[1]);
        
        return array_unique($usedNames);
    }

    private function isClassUsedInDocBlocks(string $content, string $className): bool
    {
        $patterns = [
            '/\@var\s+'.$className.'\b/',
            '/\@return\s+'.$className.'\b/',
            '/\@param\s+'.$className.'\b/',
            '/\@throws\s+'.$className.'\b/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    private function getClassShortName(string $fullName): string
    {
        $parts = explode('\\', $fullName);
        return end($parts);
    }
}