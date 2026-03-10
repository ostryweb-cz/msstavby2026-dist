# MS Stavby WordPress Theme

## Theme Overview

Standard WordPress theme following WordPress template hierarchy with semantic CSS naming, dynamic post loops, and proper menu management.

## Coding Standards

**Styling Guidelines:**
- All styles must be in CSS files (style.css, style-semantic.css)
- Exception: special elements controlled by JavaScript may use inline styles
- Avoid inline styles in HTML templates whenever possible

## Legacy/old Version Reference

When referring to the legacy version or old version, the codebase is in `../msstavby-old/`. This previous generation has different structure and scipt names, but hase some features that are modernized, but have the same purpose.

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
- `script.js` - Frontend JavaScript (source)
- `script.min.js` - Minified JavaScript (generated in dist/ only)

## Menu Locations

Four menu locations registered in functions.php:

1. **Primary Menu** (primary) - Top navigation in header
2. **Footer Menu** (footer) - Footer navigation (column 3)
3. **Social Networks** (social) - Social media links (column 1)
4. **Learn More** (learn-more) - Additional links (column 2)

## Categories Filter

A categories filter (Lokality) should be displayed at the top of the page, functioning similarly to the legacy version's Lokality menu in the right sidebar. This filter uses WordPress categories (`wp_list_categories`) to allow filtering posts by locality/category.

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
- Search and login buttons using WordPress jQuery with fadeToggle animation
- Backward compatibility with legacy msstavby theme

## Development Workflow

### Testing
```bash
npm test
```

The test suite validates syntax for:
- JavaScript files (via Node.js)
- PHP files (via PHP lint)
- Shell scripts (via ShellCheck or bash -n)

Tests must pass before releasing.

### Build Distribution
```bash
npm run build
```

This command:
1. Minifies JavaScript using webpack to `dist/msstavby2026/script.min.js`
2. Copies production files to `dist/msstavby2026/`

### Release
```bash
npm run release
```

This command:
1. Runs tests
2. Builds distribution
3. Creates GitHub release with version tag

### Deploy to Public Repository
```bash
./deploy-public.sh
```

### Daily Development
```bash
# 1. Edit source files
# 2. Test
npm test

# 3. Build
npm run build

# 4. Deploy (optional - for WordPress updates)
./deploy-public.sh

# 5. Commit
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
