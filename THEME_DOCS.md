# MS Stavby WordPress Theme

## Theme Overview

Standard WordPress theme following WordPress template hierarchy with semantic CSS naming, dynamic post loops, and proper menu management.

## Theme Files

### Core Templates
- `header.php` - Site header with navigation menu (wp_nav_menu)
- `footer.php` - Site footer with footer menu and copyright
- `home.php` - Homepage displaying latest posts (6 posts per page with pagination)
- `single.php` - Single post page with full content, metadata, navigation, comments
- `index.php` - Fallback template
- `functions.php` - Theme setup, menu registration, plugin management

### Styles & Scripts
- `style.css` - Design-specific styles
- `style-semantic.css` - Semantic CSS classes
- `script.js` - Frontend JavaScript

## Menu Locations

Two menu locations registered in functions.php:

1. **Primary Menu** (primary) - Top navigation in header
2. **Footer Menu** (footer) - Footer navigation

To assign menus:
- WordPress Admin → Appearance → Menus
- Create or select a menu
- Check "Display location" for Primary or Footer Menu

## CSS Classes

Semantic naming convention:
- `.site-header` - Header container
- `.post-card` - Individual post card
- `.entry-content` - Post content
- `.site-footer` - Footer container

## Key Features

- Standard WordPress functions (wp_nav_menu, the_content, paginate_links)
- Bootstrap 5 integration
- Custom logo, post thumbnails, post formats
- Primary & Footer menu locations
- Custom editor styles, HTML5 markup

## Development Workflow

### Build Distribution
```bash
npm run build
```

This command:
1. Minifies JavaScript
2. Copies production files to `dist/msstavby2026/`

### Deploy to Public Repository
```bash
./deploy-public.sh
```

### Daily Development
```bash
# 1. Edit source files
# 2. Build
npm run build

# 3. Deploy (optional - for WordPress updates)
./deploy-public.sh

# 4. Commit
git add dist/
git commit -m "Theme update"
git push origin main
```

## WordPress Installation

Method 1 - Upload Folder:
- Build: `npm run build`
- WordPress Admin → Appearance → Themes → Add New → Upload Theme
- Select folder: `./dist/msstavby2026/`

Method 2 - Upload Zip:
```bash
cd dist && zip -r msstavby2026.zip msstavby2026/
```

Method 3 - SFTP/FTP:
- Upload `dist/msstavby2026/` to `/wp-content/themes/`

## Manual Steps

🚨 After theme activation in WordPress:
1. WordPress Admin → Appearance → Menus
2. Create navigation menus
3. Assign to Primary Menu and/or Footer Menu locations
4. Permalinks automatically set to `/%postname%/`
