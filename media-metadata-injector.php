a<?php
/**
 * Plugin Name: Media Metadata Injector
 * Plugin URI: https://github.com/mattadlard/media-metadata-injector
 * Description: Automatically injects IPTC/EXIF metadata (title, description, keywords, GPS, copyright, etc.) into all WordPress media uploads. Can also retroactively update existing images.
 * Version: 1.3.0
 * Author: Matt Adlard
 * Author URI: https://github.com/mattadlard
 * License: GPL2
 */

/**
 * ================================
 * Notes to Self (Matt Adlard)
 * ================================
 * - This plugin solves the problem of untagged images in WordPress by injecting metadata.
 * - Metadata values are currently generic placeholders, users should customize them.
 * - If ExifTool is available, metadata is written directly into the file.
 * - If not, fallback saves metadata in WordPress attachment meta only.
 * - ExifTool installation (server-side requirement):
 *   - Debian/Ubuntu: `apt install libimage-exiftool-perl`
 *   - CentOS/Alma/Rocky: `yum install perl-Image-ExifTool`
 *   - Verify: `exiftool -ver`
 *   - If blocked, metadata injection falls back silently.
 * - Future ideas:
 *   - Admin UI for custom metadata values.
 *   - Cron jobs for periodic re-tagging.
 *   - Error notices if exiftool is missing.
 *   - Integration with SEO plugins.
 */

if (!defined('ABSPATH')) {
    exit;
}

class Media_MetaInjector {

    private $metadata = [
        'title'       => 'Your Business – Media Hub',
        'description' => 'Generic description here. Customize in plugin code or future settings page.',
        'keywords'    => 'Keyword1, Keyword2, Keyword3',
        'creator'     => 'Your Business or Name',
        'copyright'   => '© Your Business 2025. All rights reserved.',
        'credit'      => 'Your Business Credit Line',
        'source'      => 'https://yourwebsite.com',
        'email'       => 'contact@yourwebsite.com',
        'phone'       => '+00 0000 000000',
        'address'     => 'Your Address Here',
        'latitude'    => '00.0000',
        'longitude'   => '0.0000',
        'altitude'    => '0'
    ];

    public function __construct() {
        add_filter('wp_generate_attachment_metadata', [$this, 'inject_metadata'], 10, 2);
        add_action('admin_menu', [$this, 'add_admin_page']);
    }

    public function inject_metadata($metadata, $attachment_id) {
        $file_path = get_attached_file($attachment_id);

        if ($this->is_image($file_path)) {
            $this->write_metadata($file_path, $attachment_id);
        }

        return $metadata;
    }

    private function is_image($file) {
        $mime = mime_content_type($file);
        return strpos($mime, 'image/') === 0;
    }

    private function write_metadata($file_path, $attachment_id) {
        if (shell_exec("which exiftool")) {
            $cmd = "exiftool -overwrite_original \\
                -Title=\"{$this->metadata['title']}\" \\
                -Description=\"{$this->metadata['description']}\" \\
                -Keywords=\"{$this->metadata['keywords']}\" \\
                -Creator=\"{$this->metadata['creator']}\" \\
                -Copyright=\"{$this->metadata['copyright']}\" \\
                -Credit=\"{$this->metadata['credit']}\" \\
                -Source=\"{$this->metadata['source']}\" \\
                -ContactEmail=\"{$this->metadata['email']}\" \\
                -ContactPhone=\"{$this->metadata['phone']}\" \\
                -Address=\"{$this->metadata['address']}\" \\
                -GPSLatitude={$this->metadata['latitude']} \\
                -GPSLongitude={$this->metadata['longitude']} \\
                -GPSAltitude={$this->metadata['altitude']} \\
                \"$file_path\"";
            exec($cmd);
        } else {
            update_post_meta($attachment_id, '_media_metadata_injector', $this->metadata);
        }
    }

    public function add_admin_page() {
        add_management_page(
            'Inject Metadata to Existing Images',
            'Media Metadata Injector',
            'manage_options',
            'media-meta-injector',
            [$this, 'admin_page_html']
        );
    }

    public function admin_page_html() {
        if (isset($_POST['inject_all'])) {
            $this->inject_all_existing();
            echo '<div class="updated"><p>Metadata injected into all existing images.</p></div>';
        }
        echo '<div class="wrap"><h1>Media Metadata Injector</h1>';
        echo '<p>This tool ensures all images in your media library are tagged with IPTC/EXIF metadata for SEO and copyright protection.</p>';
        echo '<form method="post">';
        echo '<input type="submit" name="inject_all" class="button button-primary" value="Inject Metadata into All Images">';
        echo '</form></div>';
    }

    private function inject_all_existing() {
        $args = [
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => -1
        ];
        $images = get_posts($args);

        foreach ($images as $image) {
            $file_path = get_attached_file($image->ID);
            if ($this->is_image($file_path)) {
                $this->write_metadata($file_path, $image->ID);
            }
        }
    }
}

new Media_MetaInjector();

if (!function_exists('get_attachment_id_from_file')) {
    function get_attachment_id_from_file($file_path) {
        global $wpdb;
        $attachment_id = $wpdb->get_var($wpdb->prepare(
            "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s",
            $file_path
        ));
        return $attachment_id;
    }
}
?>
