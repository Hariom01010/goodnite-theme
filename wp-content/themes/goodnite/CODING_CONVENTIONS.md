# Goodnite India — Engineering & Coding Conventions Guide

## 1. Overview & Core Philosophy

This guide defines the engineering conventions, naming standards, and architectural rules for the **Goodnite India** custom WordPress theme. 

The primary mandate of this project is to produce **clean, maintainable, enterprise-grade code** that strictly avoids **"vibecoding"** (the anti-pattern of prompt-stacking LLM runtime monkey-patches, post-load DOM deletion hacks, dynamic inline font-resizing, and fragmented CSS overrides).

Every line of code in this project must follow standard WordPress Theme Handbook guidelines and modern web best practices.

---

## 2. Anti-Patterns Forbidden Across the Codebase

| Forbidden Anti-Pattern | Why It Is Forbidden | Approved Architectural Pattern |
| :--- | :--- | :--- |
| **Client-Side DOM Removal** (`removeRequestedCopy`, deleting tags on DOMContentLoaded) | Causes Cumulative Layout Shift (CLS), screen-reader disorientation, and flashes of unstyled content (FOUC). | **Server-Side Clean Templates:** The PHP template must render only the approved, final markup. Unwanted markup should never reach the browser. |
| **Client-Side String Rewrites** (`updateLegacyCopy`, `.innerHTML = '...'`) | Obscures source-of-truth text, breaks SEO indexing, and makes debugging difficult. | **Server-Side Rendered Content:** Copy lives in templates, customizer fields, or WordPress pages/posts. |
| **Runtime Node Relocation** (`insertBefore`, `appendChild` in JS) | Brittle; breaks when HTML attributes or layout tokens change. | **Semantic HTML Flow:** Order elements in template files in natural visual/logical order; use CSS Flexbox/Grid for responsive reordering. |
| **Global Font Normalization Loops** (looping all DOM elements to set `style.fontSize`) | Destroys browser performance, triggers massive reflows on resize, and breaks fluid CSS typography. | **CSS Custom Properties & Fluid Typography:** Manage font sizes declaratively via CSS custom properties (`--type-h1`, `--type-body`, etc.). |
| **Ad-Hoc `!important` Overrides** | Creates CSS specificity wars and unmaintainable bloat. | **BEM & Specificity Control:** Rely on semantic class hierarchies and component scoping. |
| **Hardcoded Catalog Prices in JS** | Breaks eCommerce synchronization and international pricing. | **Server-Side Data Attributes / Template Tags:** Pass data from PHP via template tags or structured data objects. |

---

## 3. Directory & File Naming Conventions

All custom code resides in `wp-content/themes/goodnite/`.

```
wp-content/themes/goodnite/
├── style.css                      # Theme metadata header only
├── screenshot.png                 # Theme preview thumbnail
├── functions.php                  # Master theme bootstrap
├── front-page.php                 # Flagship homepage template
├── index.php                      # Fallback index template
├── page.php                       # Generic WordPress page
├── single.php                     # Generic WordPress post
├── search.php                     # Full search results view
├── 404.php                        # Not found template
├── header.php                     # Shared HTML shell & header
├── footer.php                     # Shared footer & scripts
├── inc/                           # Isolated PHP modules
│   ├── setup.php                  # Theme supports & menu registrations
│   ├── assets.php                 # Enqueue styles & scripts
│   ├── template-tags.php          # Icon helpers, buttons, cards, logo
│   ├── customizer.php             # Native Customizer settings & sanitization
│   └── search-index.php           # Global search compiler & scoring
├── template-parts/                # Modular partial templates
│   ├── header/                    # Header components (nav, mobile drawer, search modal)
│   └── home/                      # Discrete homepage sections (section-hero, section-legacy, etc.)
└── assets/                        # Compiled production assets
    ├── css/theme.css              # Consolidated design system
    ├── js/theme.js                # Clean modular interaction handlers
    └── images/                    # Local optimized WebP/SVG imagery
```

### File Naming Rules:
1. **PHP Template Parts:** Prefixed with `section-` or component name in `kebab-case` (e.g. `section-hero.php`, `mobile-drawer.php`).
2. **PHP Include Modules:** Placed in `inc/` named by responsibility in `kebab-case` (e.g. `template-tags.php`, `customizer.php`).
3. **Asset Files:** Single canonical bundle per type (`assets/css/theme.css`, `assets/js/theme.js`).

---

## 4. PHP & WordPress Standards

All PHP files must adhere to the **WordPress Coding Standards (WPCS)** and modern PHP 8+:

### 4.1 File Header & Security
Every PHP file must declare strict types and prevent direct execution:
```php
<?php
/**
 * Component description.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
```

### 4.2 Function & Variable Naming
- **Theme Prefix:** All global functions, constants, and theme mods must be prefixed with `goodnite_` or `GOODNITE_`.
- **Functions:** Use `snake_case` (e.g. `goodnite_asset_url()`, `goodnite_button()`).
- **Variables:** Use `snake_case` (e.g. `$hero_slides`, `$current_index`).
- **Constants:** Use `SCREAMING_SNAKE_CASE` (e.g. `GOODNITE_THEME_VERSION`).

### 4.3 Output Escaping & Input Sanitization
Never echo untrusted or dynamic data without escaping:
```php
// Plain text:
echo esc_html( $text );

// HTML attributes:
echo esc_attr( $value );

// URLs:
echo esc_url( $url );

// Safe rich HTML (allowing <strong>, <br>, <em>):
echo wp_kses_post( $rich_content );

// JSON output in script tags:
echo wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
```

---

## 5. CSS Architecture & Naming Conventions

### 5.1 BEM (Block Element Modifier) Pattern
All classes follow a clean BEM structure with component namespaces:
```css
/* Block */
.gn-sleep-finder { }

/* Element */
.gn-sleep-finder__content { }
.gn-sleep-finder__option { }
.gn-sleep-finder__cta { }

/* Modifier */
.gn-sleep-finder__option[aria-pressed="true"] { }
.gn-sleep-finder__visual--mobile-inline { }
```

### 5.2 CSS Custom Properties for Design Tokens
Never hardcode arbitrary pixel values or colors across multiple rules. Use root tokens:
```css
:root {
  --color-brand-red: #d90000;
  --color-brand-red-hover: #b90000;
  --color-ink: #1c1c1c;
  --color-surface-warm: #f7f6f3;
  --type-display-xl: 76px;
  --type-h2: 48px;
  --type-body: 16px;
}
```

### 5.3 Responsive Breakpoints
Adopt standard, harmonious breakpoints:
- **Desktop Large:** ≥ 1440px
- **Desktop Standard:** 1024px – 1439px
- **Tablet:** 768px – 1023px
- **Mobile:** ≤ 767px

---

## 6. JavaScript Standards

### 6.1 Pure Interaction Model
JavaScript in this project is strictly an **interaction enhancement layer**. The DOM must be complete, readable, and functional without JavaScript.

### 6.2 Implementation Rules:
1. **Self-Contained IIFE or ES Modules:** Wrap in an IIFE (`(() => { 'use strict'; ... })();`) to avoid global namespace pollution.
2. **DOM Selectors via `data-*` Attributes:** Never bind JavaScript behaviors to presentation utility classes. Use semantic data attributes:
   - ✅ `document.querySelector('[data-mega-trigger]')`
   - ❌ `document.querySelector('.mega-btn-red')`
3. **Accessibility First (ARIA):**
   - Manage `aria-expanded="true/false"` on disclosures and drawers.
   - Manage `aria-pressed="true/false"` on preference toggles.
   - Implement keyboard focus trapping for dialog modals.
   - Support `Escape` key listeners for all overlays.
4. **Passive Event Listeners:** Always mark `scroll`, `touchstart`, and `touchmove` listeners as `{ passive: true }`.

---

## 7. Migration Checklist for Future Pages

When converting subsequent pages (e.g. Mattress Detail Pages, Collection Pages, Hospitality Page):

- [ ] **Step 1:** Create `template-parts/{page-slug}/` or appropriate page template.
- [ ] **Step 2:** Ensure all headings, copy, and buttons are rendered directly by PHP.
- [ ] **Step 3:** Verify all images use WebP with responsive `<picture>` tags or WordPress native `wp_get_attachment_image()`.
- [ ] **Step 4:** Ensure interactive elements (accordions, filters, tabs) use semantic buttons with ARIA attributes.
- [ ] **Step 5:** Add component styles to `theme.css` following BEM naming.
- [ ] **Step 6:** Add interaction logic to `theme.js` using `data-*` selectors without modifying DOM text.
- [ ] **Step 7:** Test with JavaScript disabled to confirm zero layout shift and readable content.
