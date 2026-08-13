# 📰 Mughdo Newspaper - WordPress Theme Repository

**Mughdo Newspaper** is a modern, ultra-fast, Single Page Application (SPA) Bengali News & Magazine WordPress Theme designed for high-traffic digital portals, blogs, and online news agencies.

Developed by **Kawsar Ahmed**.

---

## 📂 Repository Structure

```
Mughdo-Newspaper/
├── 📁 mughdo-newspaper/       # Main WordPress Theme Folder
│   ├── style.css              # Theme Stylesheet & Header Metadata
│   ├── functions.php          # Theme Functions & Module Setup
│   ├── header.php             # Portal Header Template & SPA Bar
│   ├── footer.php             # Footer & SPA Container Close
│   ├── single.php             # Single Article View (Reactions, Bio, TTS)
│   ├── index.php              # Dynamic Homepage Preset Router
│   ├── comments.php           # Bengali Comments Form Template
│   ├── 📁 inc/                # 11 Custom Theme Engine Modules
│   ├── 📁 assets/             # CSS, JS (SPA Router Engine), SVG Graphics
│   ├── 📁 template-parts/     # 23 Layout Block Templates
│   ├── 📁 page-templates/     # Homepage 1, Homepage 2, Homepage 3 Presets
│   └── 📁 languages/          # i18n Translation Catalog (.pot)
│
├── 📁 mughdo-newspaper-child/ # Child Theme Package Folder
│   ├── style.css              # Child Theme Header Metadata (Template: mughdo-newspaper)
│   └── functions.php          # Child Theme Functions
│
├── README.md                  # GitHub Repository Documentation
└── .gitignore                 # Git Ignore Configuration
```

---

## 🌟 Key Features

- **⚡ 100% SPA Navigation (Zero Page Reloads)**: Powered by HTML5 History API & custom SPA router engine.
- **🏛️ 3 Ready-to-Use Homepage Presets**:
  - **Homepage 1**: Classic Prothom Alo Grid
  - **Homepage 2**: Magazine & Video News Portal
  - **Homepage 3**: Visual Media & Live Updates Feed
- **🎛️ 15 Dynamic Homepage Section Blocks**: 100% customizable from WP Customizer (Category, Layout, Post Count 1-15, Position Order).
- **📦 18 Distinct Layout Box Styles**: 3col, 4col, Hero List, 2col Split, Overlay, Compact, Tabbed Categories, Fact Check, Podcast, Video Grid, Photo Gallery, Opinion Cards, Quote Cards, etc.
- **🚀 One-Click Bengali Demo Data Importer**: Auto-populates 10 Categories, 30+ News Articles, Key Pages, and Navigation Menus.
- **🔑 Built-in Theme License Key Verification System**: Manage purchase keys & theme activation in WP Admin.
- **📱 Mobile App Experience**: Off-canvas drawer menu & sticky mobile bottom navigation bar.
- **🔍 Live REST API Search Autocomplete**: Keyboard arrow key navigation (`ArrowUp`, `ArrowDown`, `Enter`, `Escape`).
- **📖 Rich Article Experience**:
  - Reader Emoji Reactions (❤️, 😮, 😢, 😡, 👍)
  - Bengali Text-to-Speech (Voice Reader)
  - Font Size Resizer (+A / -A)
  - Article Print & Copy Link tools
  - Author Bio Card & Next/Prev Article Navigation
- **SEO & Google Snippets**: Built-in Google Schema.org JSON-LD structured data engine.

---

## 📥 Installation Guide

### Option 1: Install Main Theme via WP Admin
1. Compress the `mughdo-newspaper/` folder into `mughdo-newspaper.zip`.
2. Go to **WordPress Admin -> Appearance -> Themes -> Add New -> Upload Theme**.
3. Upload `mughdo-newspaper.zip` and click **Install Now**.
4. Activate **Mughdo Newspaper**.

### Option 2: Install via Git Clone
Clone this repository directly into your WordPress `wp-content/themes/` directory:
```bash
cd wp-content/themes/
git clone https://github.com/kawsarapp/Mughdo-Newspaper.git
mv Mughdo-Newspaper/mughdo-newspaper .
mv Mughdo-Newspaper/mughdo-newspaper-child .
rm -rf Mughdo-Newspaper
```

---

## 🇧🇩 Quick Demo Data Import

1. Navigate to **Appearance -> ডেমো ডাটা ইমপোর্ট (Demo Data)**.
2. Click **"🚀 ৩০+ বাংলা খবর, মেনু ও পেজ ইমপোর্ট করুন"**.
3. All Bengali categories, news articles, pages, and menus will be auto-configured!

---

## 📄 License

Distributed under the GNU General Public License v2 or later.
Copyright © 2026 **Kawsar Ahmed**.
