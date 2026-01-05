<?php
// Static site generator for DirList
// Usage: php generate.php <source_directory> <output_directory>

define('DIR_INIT', true);
define('GEN_INIT', true);

// --- Entry Point ---
if ($argc < 3) {
    echo "Usage: php generate.php <source_directory> <output_directory>\n";
    exit(1);
}

$sourceDir = realpath($argv[1]);
$outputDir = $argv[2];

if (!$sourceDir || !is_dir($sourceDir)) {
    echo "Error: Source directory not found or is not a directory.\n";
    exit(1);
}

if (file_exists($outputDir)) {
    // A simple check to avoid accidental overwrites in important directories
    if (!is_dir($outputDir) || count(scandir($outputDir)) > 2) {
        echo "Error: Output directory exists and is not empty. Please use an empty or new directory.\n";
        exit(1);
    }
} else {
    mkdir($outputDir, 0755, true);
}
$outputDir = realpath($outputDir);

echo "Source Directory: $sourceDir\n";
echo "Output Directory: $outputDir\n";
echo "Initializing...\n";

// --- Includes and Configuration ---
require_once __DIR__ . '/_dir/inc.php';

// A minimal static config. We are overriding the one from inc.php
$cdnpublic = 'https://s4.zstatic.net/ajax/libs/';
$conf = [
    'title' => 'Static File Listing',
    'hide_dot_files' => true,
    'page_size' => 0, // No pagination
    'readme_md' => 1,
    'name_encode' => 'utf8',
];
$islogin = false; // We are never logged in for static generation

// --- Main Logic ---
try {
    copy_local_static_assets($outputDir); // Only copy local assets now
    generate_dir($sourceDir, $outputDir, '.');
    echo "\nStatic site generation complete.\n";
} catch (Exception $e) {
    echo "\nAn error occurred: " . $e->getMessage() . "\n";
    exit(1);
}

// --- Function Definitions ---

/**
 * Recursively generates the static pages for a directory.
 */
function generate_dir($source_base, $output_base, $current_rel_path) {
    global $conf, $islogin, $cdnpublic;

    $source_path = $source_base . '/' . $current_rel_path;
    $output_path = $output_base . '/' . $current_rel_path;

    if (!is_dir($output_path)) {
        mkdir($output_path, 0755, true);
    }

    echo "Processing: " . ($current_rel_path === '.' ? '/' : $current_rel_path) . "\n";

    $dirlist = new DirList();
    // Temporarily change CWD so DirList class works correctly on subdirectories
    $original_cwd = getcwd();
    chdir($source_path);
    $r = $dirlist->list_dir('.');
    chdir($original_cwd);

    // Fix navigation array, which is broken by the chdir() call
    if ($current_rel_path !== '.') {
        $navi = [['name' => '首页', 'src' => './']];
        $path_parts = explode('/', $current_rel_path);
        $navi_src = '';
        foreach ($path_parts as $part) {
            $navi_src .= '/' . rawurlencode($part);
            $navi[] = ['name' => $part, 'src' => './?dir=' . $navi_src];
        }
        $r['navi'] = $navi; // Overwrite the broken navigation array
    }

    // Prepare variables for templates
    if ($current_rel_path === '.') {
        $depth = 0;
    } else {
        $depth = substr_count($current_rel_path, '/') + 1;
    }
    $static_path_prefix = str_repeat('../', $depth);

    $title = $conf['title'] . ' - ' . ($current_rel_path === '.' ? 'Home' : $current_rel_path);
    $static_path = $static_path_prefix . 'static/'; // Path for local static assets
    $root_path = $static_path_prefix;
    $parsedown_path = __DIR__ . '/_dir/Parsedown.class.php';
    if($r['readme_md']) $r['readme_md'] = $source_path . '/' . $r['readme_md'];

    // Generate index.html using output buffering
    ob_start();
    require __DIR__ . '/_dir/static_generator/template_header.php';
    require __DIR__ . '/_dir/static_generator/template_content.php';
    require __DIR__ . '/_dir/static_generator/template_footer.php';
    $html_content = ob_get_clean();

    file_put_contents($output_path . '/index.html', $html_content);

    // Copy files and recurse into subdirectories
    foreach ($r['list'] as $item) {
        $item_source_path = $source_path . '/' . $item['name'];
        $item_output_path = $output_path . '/' . $item['name'];
        if ($item['type'] === 'file') {
            copy($item_source_path, $item_output_path);
        } else if ($item['type'] === 'dir') {
            $next_rel_path = ($current_rel_path === '.' ? '' : $current_rel_path . '/') . $item['name'];
            generate_dir($source_base, $output_base, $next_rel_path);
        }
    }
}

/**
 * Copies necessary local static assets into the output directory.
 */
function copy_local_static_assets($outputDir) {
    $static_out = $outputDir . '/static';
    echo "Copying local static assets to $static_out...\n";
    mkdir($static_out, 0755, true);

    // Copy local assets
    rcopy(__DIR__ . '/_dir/static', $static_out);
    if(file_exists($static_out . '/js/admin.js')) {
        unlink($static_out . '/js/admin.js');
    }
}

/**
 * Recursive directory copy.
 */
function rcopy($src, $dst) {
    if (is_dir($src)) {
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                rcopy("$src/$file", "$dst/$file");
            }
        }
    } else if (file_exists($src)) {
        copy($src, $dst);
    }
}
