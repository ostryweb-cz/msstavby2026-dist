# MS Stavby WordPress Theme - WARP Documentation

## Project Overview
A standard WordPress theme for MS Stavby website following WordPress template hierarchy with semantic CSS naming, dynamic post loops, and proper menu management.

## Theme Architecture

### Standard WordPress Template Hierarchy
- **header.php**: Site header with custom logo/title and primary navigation menu (wp_nav_menu)
- **footer.php**: Site footer with footer menu (wp_nav_menu) and copyright
- **home.php**: Homepage displaying latest posts (6 posts per page with pagination)
- **single.php**: Single post detail page with full content, metadata, navigation, and comments
- **index.php**: Fallback template
- **functions.php**: Theme setup, menu registration, plugin management, and custom hooks
- **style.css**: Design-specific styles from original layout
- **style-semantic.css**: Semantic CSS classes (.site-header, .post-card, .site-footer, etc.)
- **script.js**: Frontend JavaScript

### Registered Menu Locations
Two menu locations are registered in functions.php:
1. **Primary Menu** (primary): Top navigation menu displayed in header
2. **Footer Menu** (footer): Footer navigation menu displayed in site footer

To assign menus:
1. Go to WordPress Admin → Appearance → Menus
2. Create or select a menu
3. Check "Display location" for Primary or Footer Menu
4. Save

### Key Features (Version 2026)

#### Standard WordPress Functions
- `wp_nav_menu()`: Used in header.php and footer.php for menu display
- `get_header()` / `get_footer()`: Used in home.php and single.php
- `the_content()`: Used in single.php for post content
- `the_excerpt()`: Used in home.php for post preview
- `get_the_date()`, `get_the_author()`, `the_title()`: Standard post template tags
- `paginate_links()`: Homepage pagination
- `the_post_navigation()`: Post navigation in single.php

#### CSS Naming Convention
All CSS classes use semantic, human-readable names:

**Header/Navigation:**
- `.site-header` - Header container
- `.site-header__wrapper` - Header flex wrapper
- `.site-branding` - Logo/title area
- `.site-title` - Site name
- `.site-description` - Tagline
- `.site-nav` - Navigation container
- `.primary-menu` - Main menu list

**Content:**
- `.site-main` - Main content area
- `.posts-container` - Homepage container
- `.posts-list` - Grid of post cards
- `.post-card` - Individual post card
- `.post-card__header` - Post header with author/date
- `.post-meta` - Author and date info
- `.post-title` - Post headline
- `.post-content` - Post excerpt/content
- `.posts-pagination` - Pagination controls

**Single Post:**
- `.post-detail` - Single post container
- `.entry-header` - Post title and metadata
- `.entry-title` - Post title
- `.entry-meta` - Author, date, categories
- `.entry-thumbnail` - Featured image
- `.entry-content` - Full post content
- `.entry-footer` - Tags and metadata
- `.post-navigation` - Previous/next post links

**Footer:**
- `.site-footer` - Footer container
- `.site-footer__wrapper` - Footer grid wrapper
- `.footer-nav` - Footer menu area
- `.footer-menu` - Footer menu list
- `.site-info` - Copyright info
- `.copyright` - Copyright text

#### Branding Updates
- Theme text domain: 'msstavby'

#### Homepage Display
- Dynamic WP_Query limiting posts to 6 per page
- Post cards with author, date, excerpt, and title
- Pagination with previous/next links
- No hardcoded HTML—uses WordPress query functions

#### Single Post Display
- Full post content with proper formatting
- Post metadata (author, date, categories, tags)
- Featured image support
- Post navigation (previous/next)
- Comment section support

## External Dependencies

### CSS/JS Libraries
- js-alert: https://unpkg.com/js-alert/dist/jsalert.min.js
- Bootstrap 5 (CSS + JS via CDN)

### Plugin Requirements
- msstavby-wordpress-basics
- msstavby-wordpress-forms

## Theme Support
- Custom logo (via Customizer)
- Post thumbnails
- Post formats: image, gallery, video, audio
- Custom menus (Primary, Footer)
- Editor styles
- HTML5 markup

## Distribution Build

### Build Workflow for Distribution
A Webpack-based build system is set up to create clean production distribution in `dist/` directory.

**Available NPM Scripts:**
- `npm run dist` - Production build with minified JavaScript
- `npm run dist:dev` - Development build with source maps
- `npm run dist:clean` - Clean dist directory
- `npm run dist:package` - Build and create tar.gz archive
- `npm run deploy` - Build package with deployment instructions

**Build Process:**
1. Compiles and minifies `script.js` to `script.min.js`
2. Copies all production files to `dist/msstavby2026/`:
   - PHP files
   - CSS files (style.css, style-semantic.css, login.css)
   - Minified JavaScript
   - Templates, Patterns, Parts folders
   - Theme metadata (theme.json, screenshot.png)
   - WARP.md as THEME_DOCS.md
3. Excludes: msstavby-old/, node_modules/, .DS_Store, build artifacts

**First-time setup:**
```bash
cd /Users/hynekstavik/projects/msstavby/msstavby2026
npm install
```

**Build distribution:**
```bash
npm run dist
```

## Development

### Adding Custom Styles
1. Add new semantic classes to style-semantic.css
2. Follow the naming pattern: .element or .element__child or .element--modifier
3. Keep style.css for design-specific overrides if needed

### Creating New Templates
Follow WordPress standard hierarchy:
- `archive.php` for category/tag archives
- `page.php` for static pages
- `search.php` for search results
- `404.php` for 404 errors

### Modifying Navigation
Edit header.php and footer.php to customize menu output:
- Change `menu_class` in wp_nav_menu() args
- Adjust depth, items per menu, etc.

## Deployment Notes

🚨 **Manual Steps Required:**
1. After theme activation, go to WordPress Admin
2. Create Primary Menu with desired navigation items
3. Assign menu to Primary Menu location
4. (Optional) Create and assign Footer Menu

Permalinks automatically set to /%postname%/ on activation.
Admin bar hidden for non-local sites.

## Project Structure

**Key Files:**
- `.warpindexingignore` - Configures Warp to exclude unnecessary folders (msstavby-old, node_modules, dist) from indexing

**Removed Templates:**
- `templates/`, `patterns/`, `parts/` folders removed - contained non-standard HTML templates with ostryweb.io dependencies
- Use standard WordPress page templates and block patterns instead

**External References Cleaned:**
- `ostryweb.io` CDN links replaced with standard Google Fonts
- Custom `ostryweb-block` blocks removed - use standard WordPress blocks
- Old HTML `.html` templates replaced with PHP WordPress templates

## Migration Notes
- Old templates (landing_page_pc.html, project_detail_pc.html) replaced with standard home.php and single.php
- Block editor patterns still registered for custom page building
- Old hashed CSS classes replaced with semantic equivalents in style-semantic.css
