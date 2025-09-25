# Media Metadata Injector

A WordPress plugin that automatically injects IPTC/EXIF metadata (title, description, keywords, GPS, copyright, etc.) into all media uploads. It can also retroactively update existing images in the WordPress Media Library.

---

## ✨ Features
- Injects IPTC/EXIF metadata into every new image upload.
- Retroactively apply metadata to all existing images via Tools menu.
- Stores metadata in WordPress attachment meta if ExifTool is unavailable.
- SEO-friendly: improves image discoverability and protects copyright.
- Built with extensibility in mind.

---

## 🛠 Requirements
- **WordPress 6.x** (should work with WP 5.8+).
- **PHP 7.4+**.
- (Optional, recommended) **ExifTool** installed on the server:
  - Debian/Ubuntu: `apt install libimage-exiftool-perl`
  - CentOS/Alma/Rocky: `yum install perl-Image-ExifTool`
- PHP functions `exec()` or `shell_exec()` enabled (required for ExifTool integration).

If ExifTool is missing or blocked, metadata is stored inside WordPress only (not written into the image file).

---

## 🚀 Installation
1. Download or clone this repository.
2. Upload the plugin folder `media-metadata-injector/` to:
   ```
   wp-content/plugins/
   ```
3. Activate the plugin in **WordPress → Plugins**.

---

## ⚙️ Usage
- **New images:** Metadata is injected automatically at upload.
- **Existing images:**
  1. Go to **Tools → Media Metadata Injector**.
  2. Click **Inject Metadata into All Images**.

---

## 🔍 Testing Metadata
- Verify via terminal:
  ```bash
  exiftool yourimage.jpg
  ```
- Or download an image and inspect its **Properties → Details** (Windows) or **Get Info → More Info** (Mac).

---

## 🧭 Default Metadata Fields
These are placeholders in the plugin code. Customize them in the `$metadata` array or future settings page:
- Title: `Your Business – Media Hub`
- Description: `Generic description here.`
- Keywords: `Keyword1, Keyword2, Keyword3`
- Creator: `Your Business`
- Copyright: `© Your Business 2025. All rights reserved.`
- Credit: `Your Credit Line`
- Source: `https://yourwebsite.com`
- Email: `contact@yourwebsite.com`
- Phone: `+00 0000 000000`
- Address: `Your Address Here`
- GPS: `00.0000, 0.0000`

---

## 🧩 Roadmap
- WordPress admin settings page for metadata customization.
- Scheduled cron jobs to reapply metadata.
- Dashboard warnings if ExifTool is missing.
- Bulk keyword management by category.
- SEO plugin integration (Yoast, RankMath).

---

## 📸 Screenshots
*(Add actual screenshots in `assets/`)*

1. Admin Tools Page
2. Media Metadata Injector button
3. Example file metadata in system properties

---

## 📜 License
This plugin is licensed under the **GPL2**.

---

## 👤 Author
Built by **Matt Adlard**  
GitHub: [mattadlard](https://github.com/mattadlard)
