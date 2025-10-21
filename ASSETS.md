# Dashboard Launchpad - Assets Guide

This document outlines the visual assets required for WordPress.org submission and general plugin branding.

## Required Assets for WordPress.org

### Plugin Banner

**Location:** `.wordpress-org/` directory (not included in plugin zip)

**Sizes Required:**
- `banner-772x250.png` - Standard resolution (772 x 250 pixels)
- `banner-1544x500.png` - High resolution/Retina (1544 x 500 pixels)

**Design Guidelines:**
- Use the plugin's brand colors (WordPress blue #2271b1 recommended)
- Include plugin name "Dashboard Launchpad"
- Show key features visually (button grid, customization)
- Keep text readable even at small sizes
- Avoid too much detail

**Recommended Tools:**
- Figma, Adobe Photoshop, or Canva
- Export as PNG with transparent or solid background

---

### Plugin Icon

**Location:** `.wordpress-org/` directory (not included in plugin zip)

**Sizes Required:**
- `icon-128x128.png` - Standard resolution (128 x 128 pixels)
- `icon-256x256.png` - High resolution/Retina (256 x 256 pixels)

**Design Guidelines:**
- Simple, recognizable icon
- Represents "launchpad" or "dashboard" concept
- Use a rocket, grid, or dashboard icon
- Match WordPress design language
- Works well at small sizes
- Background should be transparent OR solid color

**Icon Concept Ideas:**
- 🚀 Rocket ship (launchpad concept)
- ⚡ Lightning bolt (speed/quick access)
- 📊 Dashboard/grid pattern
- 🎯 Target (precision)

---

### Screenshots

**Location:** `.wordpress-org/` directory (not included in plugin zip)

**Naming Convention:**
- `screenshot-1.png` - Dashboard view with buttons
- `screenshot-2.png` - Settings page: Buttons tab
- `screenshot-3.png` - Settings page: Appearance tab
- `screenshot-4.png` - Settings page: Role Visibility tab
- `screenshot-5.png` - Mobile/responsive view
- `screenshot-6.png` - Dark mode (optional)

**Sizes:**
- Minimum: 1200 x 900 pixels
- Recommended: 1920 x 1080 pixels (maintains aspect ratio)
- Format: PNG or JPG

**Screenshot Guidelines:**
- Show actual plugin interface
- Use realistic data (not lorem ipsum)
- Highlight key features
- Clean, professional screenshots
- No browser chrome or OS elements
- Add captions in readme.txt

**How to Capture:**
1. Set browser to exact size (e.g., 1920px wide)
2. Use browser dev tools or screenshot tool
3. Crop to show only relevant interface
4. Optimize file size (use TinyPNG)

---

## Directory Structure

```
Dashboard-Launchpad/
├── .wordpress-org/          ← Assets for WordPress.org (not in plugin zip)
│   ├── banner-772x250.png
│   ├── banner-1544x500.png
│   ├── icon-128x128.png
│   ├── icon-256x256.png
│   ├── screenshot-1.png
│   ├── screenshot-2.png
│   ├── screenshot-3.png
│   ├── screenshot-4.png
│   ├── screenshot-5.png
│   └── screenshot-6.png
├── assets/                  ← Plugin CSS/JS assets
│   ├── css/
│   └── js/
└── ... (plugin files)
```

---

## Creating the Banner

### Option 1: Using Figma (Recommended)

1. Create new file: 1544 x 500 px
2. Add background gradient (use WordPress blue #2271b1)
3. Add plugin name "Dashboard Launchpad" with clear typography
4. Add mockup of button grid interface
5. Export as PNG at 100% (1544x500) and 50% (772x250)

### Option 2: Using Canva

1. Create custom size: 1544 x 500 px
2. Search for "WordPress plugin banner" templates
3. Customize with Dashboard Launchpad branding
4. Download as PNG

### Option 3: Using Photoshop

1. New document: 1544 x 500 px, 72 DPI
2. Add background layer with gradient
3. Add text layer: "Dashboard Launchpad"
4. Add visual elements (mockup screenshots, icons)
5. Save for Web: PNG-24
6. Resize to 772 x 250 px for standard version

---

## Creating the Icon

### Concept: Rocket Launchpad

**Simple SVG Icon:**
```svg
<svg width="256" height="256" viewBox="0 0 256 256">
  <rect fill="#2271b1" width="256" height="256" rx="32"/>
  <path fill="#fff" d="M128 64l32 96h-64z"/>
  <circle fill="#fff" cx="128" cy="192" r="8"/>
</svg>
```

**Steps:**
1. Create 256x256 artboard
2. Draw simple rocket/grid icon
3. Use max 2-3 colors
4. Export as PNG with transparency
5. Create 128x128 version

---

## Screenshot Checklist

- [ ] Screenshot 1: Dashboard view showing button grid
- [ ] Screenshot 2: Settings - Buttons tab (drag & drop)
- [ ] Screenshot 3: Settings - Appearance (color picker)
- [ ] Screenshot 4: Settings - Role Visibility
- [ ] Screenshot 5: Mobile responsive view
- [ ] Screenshot 6: Dark mode (optional)
- [ ] All screenshots are 1200x900 minimum
- [ ] All screenshots optimized (<500KB each)
- [ ] Captions added to readme.txt

---

## WordPress.org Submission Checklist

### Before Submitting:

- [ ] Banner images created (772x250 and 1544x500)
- [ ] Icon images created (128x128 and 256x256)
- [ ] At least 3 screenshots captured
- [ ] All images optimized (use TinyPNG)
- [ ] Images placed in `.wordpress-org/` directory
- [ ] readme.txt has screenshot captions
- [ ] Plugin tested with WordPress debug mode
- [ ] All assets look good on wordpress.org preview

### File Size Optimization:

- **Banners:** < 200KB each
- **Icons:** < 50KB each
- **Screenshots:** < 500KB each

**Tools:**
- [TinyPNG](https://tinypng.com/) - PNG compression
- [Squoosh](https://squoosh.app/) - Advanced image optimization
- [SVGOMG](https://jakearchibald.github.io/svgomg/) - SVG optimization

---

## Brand Colors

**Primary:**
- WordPress Blue: `#2271b1`
- WordPress Blue Hover: `#135e96`

**Secondary:**
- White: `#ffffff`
- Light Gray: `#f6f7f7`
- Dark Gray: `#2c3338`

**Accent:**
- Success Green: `#00a32a`
- Error Red: `#d63638`
- Warning Yellow: `#dba617`

---

## Design Inspiration

**Similar Plugins:**
- [WordPress.org Plugin Directory](https://wordpress.org/plugins/)
- Search for "dashboard" plugins
- Note successful banner/icon designs

**Icon Libraries:**
- [WordPress Dashicons](https://developer.wordpress.org/resource/dashicons/)
- [Heroicons](https://heroicons.com/)
- [Feather Icons](https://feathericons.com/)

---

## Next Steps

1. **Create Banner:** Use provided specifications
2. **Create Icon:** Simple, recognizable design
3. **Capture Screenshots:** Show all major features
4. **Optimize Images:** Compress for web
5. **Test on WordPress.org:** Use plugin preview
6. **Submit:** Follow WordPress.org guidelines

For questions, see: [WordPress.org Assets Guidelines](https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/)
