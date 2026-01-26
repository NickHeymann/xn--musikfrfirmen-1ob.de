# musikfürfirmen.de - Admin Panel User Guide

## Quick Start

**Admin Panel URL:** https://musikfürfirmen.de.de/admin

**Login Credentials:**
- Email: admin@musikfürfirmen.de.de
- Password: (Contact administrator)

---

## Dashboard Overview

After logging in, you'll see the admin dashboard with the **Content Management** section in the sidebar:

```
📁 Content Management
   ├── Services (3 items)
   ├── Team Members (2 items)
   ├── FAQs (7 items)
   └── Pages (0 items)
```

---

## Managing Services

**Location:** Content Management → Services

Services appear on the homepage as 3 steps in the process timeline.

### Creating a New Service

1. Click **"Create"** button
2. Fill in the form:

**Service Details:**
- **Title**: Short title (e.g., "60 Sekunden", "24 Stunden")
- **Text (before highlight)**: The text that appears before the highlighted portion
- **Highlighted Text**: The text that will be highlighted in turquoise/green
- **Description (after highlight)**: The text that appears after the highlighted portion

**Settings:**
- **Display Order**: Lower numbers appear first (0, 1, 2...)
- **Status**: 
  - `Active` = Visible on website
  - `Inactive` = Hidden from website

3. Click **"Create"**

### Editing a Service

1. Click the **Edit** icon (pencil) next to the service
2. Modify any fields
3. Click **"Save changes"**

### Example Service

```
Title: 60 Sekunden
Text: Wir benötigen
Highlight: maximal 60 Sekunden
Description: für Ihre Kontaktanfrage.

Display Order: 1
Status: Active
```

**Result on website:**
> Wir benötigen **maximal 60 Sekunden** für Ihre Kontaktanfrage.

---

## Managing Team Members

**Location:** Content Management → Team Members

Team members appear on the homepage in the "Team" section.

### Creating a New Team Member

1. Click **"Create"** button
2. Fill in the form:

**Personal Information:**
- **Full Name**: e.g., "Jonas Glamann", "Nick Heymann"
- **Primary Role**: Main title (e.g., "Gründer", "Direkter Kontakt vor Ort")
- **Secondary Role**: Additional description (optional)
- **Profile Image**: Square image recommended (400x400px)
  - Click "Select file" or drag & drop
  - Supported formats: JPG, PNG, WebP

**Biography** (optional, collapsible):
- **Bio Title**: Headline for expanded bio (e.g., "Der Kreative")
- **Bio Text**: Full biography text

**Layout Settings:**
- **Image Position**: `Left` or `Right` (alternates layout)
- **Image CSS Class**: Advanced styling (leave empty unless custom styling needed)
- **Display Order**: Lower numbers appear first (0, 1, 2...)
- **Status**: `Active` or `Inactive`

3. Click **"Create"**

### Image Guidelines

**Recommended:**
- Square aspect ratio (1:1)
- Minimum size: 400×400px
- File size: < 500KB
- Format: JPG or PNG

**Tips:**
- Use high-quality professional photos
- Ensure good lighting
- Plain or simple backgrounds work best

### Editing a Team Member

1. Click the **Edit** icon next to the team member
2. Modify fields or replace image
3. Click **"Save changes"**

---

## Managing FAQs

**Location:** Content Management → FAQs

FAQs appear on the homepage in the FAQ accordion section.

### Creating a New FAQ

1. Click **"Create"** button
2. Fill in the form:

**FAQ Content:**
- **Question**: The question users frequently ask (max 500 characters)
- **Answer**: Detailed answer (supports multiple paragraphs)
- **Contact Link**: Toggle ON to show "Get in touch" link after the answer

**Settings:**
- **Display Order**: Lower numbers appear first (0, 1, 2...)
- **Status**: `Active` or `Inactive`

3. Click **"Create"**

### Writing Good FAQ Answers

**Best Practices:**
- Start with a direct answer in the first sentence
- Keep answers concise but complete
- Use multiple paragraphs for clarity
- End with "Get in touch" link if users might need more info

**Example:**

```
Question: Welche Musikrichtung spielt die Band?

Answer: Die Band spielt eine vielseitige Mischung aus Pop, Rock, 
Schlager und Funk - von aktuellen Hits bis zu zeitlosen Klassikern.

Das Repertoire wird individuell mit Ihnen abgestimmt, um perfekt 
zu Ihrer Veranstaltung und Ihrem Publikum zu passen.

Contact Link: ON (to encourage inquiries)
```

### Editing FAQs

1. Click the **Edit** icon next to the FAQ
2. Modify question or answer
3. Click **"Save changes"**

### Reordering FAQs

Use the **Display Order** field to change the sequence:
- 0 = First
- 1 = Second
- 2 = Third
- etc.

---

## Managing Pages

**Location:** Content Management → Pages

Pages are standalone content pages (e.g., Impressum, Datenschutz, AGB).

### Creating a New Page

1. Click **"Create"** button
2. Fill in the form:

**Page Information:**
- **Page Title**: The main title (e.g., "Privacy Policy", "Terms of Service")
- **URL Slug**: Auto-generated from title (e.g., "privacy-policy")
  - Can be edited if needed
  - Only lowercase, hyphens allowed
- **Page Type**: 
  - `Content Page` = General content
  - `Legal Page` = Legal documents (Impressum, Datenschutz)
  - `Info Page` = Information pages

**Page Content:**
- **Content**: Rich text editor with formatting options
  - Bold, Italic, Underline
  - Headings (H2, H3)
  - Bullet lists, Numbered lists
  - Links, Blockquotes
  - Code blocks

**Settings:**
- **Display Order**: Order in navigation (if used)
- **Status**: 
  - `Published` = Visible on website
  - `Draft` = Hidden from website

3. Click **"Create"**

### Rich Text Editor Tips

**Formatting:**
- Use **H2** for main sections
- Use **H3** for subsections
- Use **Bold** for emphasis
- Use bullet lists for easy scanning

**Example Structure:**
```
## Privacy Policy

This privacy policy explains how we collect and use your data.

## Data Collection

We collect the following information:
- Name and email address
- Company information
- Event details

## Contact

For questions, please email: info@musikfürfirmen.de.de
```

### Editing Pages

1. Click the **Edit** icon next to the page
2. Modify content in rich text editor
3. Click **"Save changes"**

---

## Table Features

### Filtering

Each table has filters at the top:
- **Services**: Filter by Status
- **Team Members**: Filter by Status and Position
- **FAQs**: Filter by Status and Contact Link
- **Pages**: Filter by Type and Status

### Searching

Use the search bar to find specific items:
- Services: Search by title or highlight
- Team Members: Search by name or role
- FAQs: Search by question
- Pages: Search by title or slug

### Sorting

Click column headers to sort:
- By default, items are sorted by Display Order (ascending)
- Click "Order" column to manually sort
- Click "Status" to group by active/inactive

### Bulk Actions

1. Select multiple items using checkboxes
2. Click "Bulk actions" dropdown
3. Choose action (e.g., Delete)

---

## Best Practices

### Content Writing

**Clear and Concise:**
- Use simple language
- Keep sentences short
- Avoid jargon unless necessary

**Consistent Tone:**
- Professional yet friendly
- Active voice preferred
- Address readers directly ("you", "your")

**SEO-Friendly:**
- Include relevant keywords naturally
- Use descriptive headings
- Write unique meta descriptions (for pages)

### Images

**Quality Standards:**
- High resolution (at least 72 DPI for web)
- Proper aspect ratios
- Optimized file size (< 500KB)

**Naming:**
- Use descriptive filenames
- Example: `team-jonas-glamann.jpg` (not `IMG_1234.jpg`)

### Content Updates

**Regular Reviews:**
- Review content quarterly
- Update outdated information
- Add seasonal FAQs if relevant

**Testing After Changes:**
- Check homepage after updating services
- Verify FAQ accordion works after changes
- Test page links after creating new pages

---

## Common Tasks

### Task: Update Band Member Photo

1. Go to **Team Members**
2. Click **Edit** on the member
3. In **Profile Image** section:
   - Click the existing image
   - Click "Remove"
   - Upload new image
4. Click **Save changes**
5. Visit homepage to verify new photo appears

### Task: Add Seasonal FAQ

1. Go to **FAQs**
2. Click **Create**
3. Enter seasonal question/answer
4. Set appropriate **Display Order** (lower = higher priority)
5. Set **Status** to `Active`
6. Click **Create**
7. Homepage FAQ section updates automatically

### Task: Temporarily Hide a Service

1. Go to **Services**
2. Click **Edit** on the service
3. Change **Status** from `Active` to `Inactive`
4. Click **Save changes**
5. Service disappears from homepage immediately

### Task: Reorder Team Members

1. Go to **Team Members**
2. Note current display order
3. Click **Edit** on members you want to reorder
4. Change **Display Order** numbers:
   - Want Jonas first? Set his order to `0`
   - Want Nick second? Set his order to `1`
5. Click **Save changes** on each
6. Homepage reflects new order

---

## Troubleshooting

### Issue: "Changes not appearing on website"

**Solution:**
1. Verify **Status** is set to `Active`
2. Clear browser cache (Ctrl+F5 or Cmd+Shift+R)
3. Check **Display Order** is correct
4. Contact administrator if issue persists

### Issue: "Image upload failed"

**Solutions:**
- Check file size (must be < 2MB)
- Check file format (JPG, PNG only)
- Try a different image
- Contact administrator if error persists

### Issue: "Can't log in"

**Solutions:**
- Verify email and password are correct
- Check Caps Lock is off
- Contact administrator to reset password

### Issue: "Deleted item by accident"

**Solution:**
- Deleted items cannot be restored from admin panel
- Contact administrator immediately for database restoration
- Items are backed up daily

---

## Security & Access

### Password Management

**Change Your Password:**
1. Click your name in top-right corner
2. Select "Profile"
3. Click "Change password"
4. Enter current password
5. Enter new password (twice)
6. Click "Update password"

**Password Requirements:**
- Minimum 8 characters
- Mix of letters and numbers recommended
- Change every 3-6 months

### Session Management

**Automatic Logout:**
- Sessions expire after 2 hours of inactivity
- You'll be redirected to login page
- Simply log in again to continue

**Manual Logout:**
- Click your name in top-right corner
- Select "Logout"

### Access Levels

**Admin Access:**
- Full access to all content types
- Can create, edit, delete items
- Can manage other users (contact developer)

---

## Support

### Getting Help

**For Content Questions:**
- Contact: [Your contact email]
- Phone: [Your phone number]

**For Technical Issues:**
- Developer: Nick Heymann
- Email: [Developer email]
- GitHub: https://github.com/NickHeymann/musikfürfirmen.de

### Training

**Available Resources:**
- This user guide (keep bookmarked)
- Video tutorials (if available)
- Live training sessions (request if needed)

### Feedback

We welcome your feedback to improve the admin panel:
- Report bugs or issues
- Suggest new features
- Request additional content types

---

## Appendix: Field Reference

### Services

| Field | Type | Required | Max Length | Description |
|-------|------|----------|------------|-------------|
| Title | Text | Yes | 255 chars | Service step title |
| Text | Textarea | Yes | - | Text before highlight |
| Highlight | Text | Yes | 255 chars | Highlighted text |
| Description | Textarea | Yes | - | Text after highlight |
| Display Order | Number | Yes | - | Sort order (0-99) |
| Status | Dropdown | Yes | - | Active/Inactive |

### Team Members

| Field | Type | Required | Max Length | Description |
|-------|------|----------|------------|-------------|
| Full Name | Text | Yes | 255 chars | Team member name |
| Primary Role | Text | Yes | 255 chars | Main job title |
| Secondary Role | Text | No | 255 chars | Additional description |
| Profile Image | File | Yes | 2MB | Square photo (400×400px) |
| Bio Title | Text | No | 255 chars | Biography headline |
| Bio Text | Textarea | No | - | Full biography |
| Image Position | Dropdown | Yes | - | Left/Right layout |
| Image CSS Class | Text | No | 255 chars | Advanced styling |
| Display Order | Number | Yes | - | Sort order (0-99) |
| Status | Dropdown | Yes | - | Active/Inactive |

### FAQs

| Field | Type | Required | Max Length | Description |
|-------|------|----------|------------|-------------|
| Question | Text | Yes | 500 chars | FAQ question |
| Answer | Textarea | Yes | - | Detailed answer |
| Contact Link | Toggle | No | - | Show "Get in touch" link |
| Display Order | Number | Yes | - | Sort order (0-99) |
| Status | Dropdown | Yes | - | Active/Inactive |

### Pages

| Field | Type | Required | Max Length | Description |
|-------|------|----------|------------|-------------|
| Page Title | Text | Yes | 255 chars | Main page title |
| URL Slug | Text | Yes | 255 chars | URL-friendly identifier |
| Page Type | Dropdown | Yes | - | Content/Legal/Info |
| Content | Rich Text | No | - | Full page content |
| Display Order | Number | Yes | - | Sort order (0-99) |
| Status | Dropdown | Yes | - | Published/Draft |

---

**Version:** 1.0  
**Last Updated:** 2026-01-14  
**Questions?** Contact your administrator
