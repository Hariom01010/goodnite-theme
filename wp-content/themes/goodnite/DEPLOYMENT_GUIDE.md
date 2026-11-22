# Goodnite India — Deployment & Preview Guide

This guide provides step-by-step instructions for previewing the **Goodnite** custom WordPress theme locally under XAMPP, packaging it for distribution, and deploying it to staging or production preview environments.

---

## 1. Local Preview Under XAMPP

Your WordPress instance is located at:
`C:\xampp\htdocs\goodnite\`

The custom theme is located at:
`C:\xampp\htdocs\goodnite\wp-content\themes\goodnite\`

### 1.1 Start XAMPP Services
1. Open the **XAMPP Control Panel**.
2. Start both **Apache** and **MySQL**.
3. Verify that Apache runs on port `80` (or `443` for SSL) and MySQL runs on port `3306`.

### 1.2 Access WordPress Admin
1. Open your browser and navigate to:
   [http://localhost/goodnite/wp-admin/](http://localhost/goodnite/wp-admin/)
2. Log in with your WordPress administrative credentials.

### 1.3 Activate the Goodnite Theme
1. In the WordPress Admin sidebar, go to **Appearance → Themes**.
2. Locate **Goodnite** (represented with the official flagship preview screenshot).
3. Click **Activate**.

### 1.4 Configure Reading Settings
`front-page.php` in the Goodnite theme is configured to automatically serve the flagship editorial layout. To ensure WordPress routes properly:
1. Go to **Settings → Reading**.
2. Under **Your homepage displays**, select **A static page**.
3. Choose your designated **Homepage** (create a page titled "Home" if not already present).
4. Save Changes.

### 1.5 Configure Navigation Menus
1. Navigate to **Appearance → Menus**.
2. Assign or create menus for the registered theme locations:
   - **Primary Navigation:** Links shown alongside the Mattress mega-menu (`Sleep Technology`, `Our Story`, `Hospitality`).
   - **Mobile Navigation:** Navigation links for the mobile drawer.
   - **Footer — Shop:** Links to mattress collections and product filters.
   - **Footer — Discover:** Brand story and innovation links.
   - **Footer — Policies:** Privacy Policy, Terms, Shipping, Returns, and Warranty.
   - **Footer — Social Links:** Social media destinations.
3. *Note:* If no menus are assigned yet, the theme renders elegant, curated default fallback menus automatically.

### 1.6 Customizer Review
1. Navigate to **Appearance → Customize**.
2. Explore the custom **Goodnite** controls:
   - **Brand & Header:** Announcement banner text and official logo artwork.
   - **Hero Section:** Primary headline, body copy, and button destinations.
   - **Legacy Story & Metrics:** Capacity count-up target (`1,500`), worldwide mattresses sold (`5M+`), and countries (`15+`).
   - **Hospitality & Store Locator:** Custom copy, button text, and destination links.
3. Click **Publish** once satisfied with your adjustments.

---

## 2. Instant Client Preview Options

When sharing a live interactive preview with clients or stakeholders prior to public hosting:

### Option A: Cloudflare Tunnel (Recommended, No Port Forwarding Required)
If `cloudflared` is installed on your machine:
```powershell
cloudflared tunnel --url http://localhost:80
```
This generates a secure public HTTPS URL (e.g. `https://random-subdomain.trycloudflare.com/goodnite/`) that stakeholders can view immediately on desktop or mobile.

### Option B: Ngrok Tunnel
```powershell
ngrok http 80 --host-header="localhost"
```

---

## 3. Packaging Theme for Staging / Remote Upload

To deploy the theme to a remote staging server via the standard WordPress theme uploader:

### 3.1 Create Production Zip Archive
Run the following PowerShell command in the workspace:
```powershell
Compress-Archive -Path 'wp-content/themes/goodnite' -DestinationPath 'goodnite-theme.zip' -Force
```

### 3.2 Upload to Remote WordPress
1. On the remote staging site, log into **WP Admin**.
2. Go to **Appearance → Themes → Add New Theme → Upload Theme**.
3. Select `goodnite-theme.zip` and click **Install Now**.
4. Click **Activate**.

---

## 4. Pre-Launch Quality & Verification Checklist

Before sending the preview link for final client sign-off, verify the following checklist:

| Verification Item | Acceptance Criteria | Verified |
| :--- | :--- | :---: |
| **No Layout Shift (CLS)** | Hero, headings, cards, and metrics render immediately without post-load jumping or resizing. | [x] |
| **Console Cleanliness** | Zero JavaScript console errors or warnings upon load, search, filter, or interaction. | [x] |
| **Hero Slider & Controls** | Autoplay rotates smoothly every 6 seconds; pause on hover works; touch swipe functions on mobile. | [x] |
| **Interactive Sleep Finder** | Users can select up to 2 options; limit feedback triggers on 3rd attempt; CTA updates link query params. | [x] |
| **Technology Pillars** | Responsive carousel scrolls smoothly; dot indicators update accurately. | [x] |
| **Product Carousel** | Horizontal scroll snap responds cleanly; arrows move cards; prices and badges display accurately. | [x] |
| **Hospitality Marquee** | Infinite ticker scrolls with zero stuttering; pauses on hover/focus; disables gracefully on reduced motion. | [x] |
| **Store Locator** | Pincode input restricts non-digits; enforces 6 digits; renders dealer preview card for entered area. | [x] |
| **Search Experience** | Live modal opens instantly with Escape support; suggestions trigger immediate filtering. | [x] |
| **Mobile Drawer Accessibility** | Focus is trapped inside sheet while open; `Escape` key closes overlay; scroll lock prevents background jumping. | [x] |
