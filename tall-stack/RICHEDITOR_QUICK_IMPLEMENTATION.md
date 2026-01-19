# RichEditor Quick Implementation Guide
## 15-Minute Setup for musikfürfirmen.de

**Based on:** WYSIWYG_EDITOR_RESEARCH_AND_IMPLEMENTATION.md (comprehensive analysis)

---

## Decision: Use Filament's Native RichEditor ✅

**Why:**
- Native to Filament 4.x (zero configuration)
- TipTap-based modern editor
- JSON storage for future flexibility
- Secure image uploads via Laravel Storage
- No third-party dependencies
- Auto-updates with Filament

---

## Step 1: Update PageForm.php (5 minutes)

```bash
# File: app/Filament/Resources/Pages/Schemas/PageForm.php
```

**Before:**
```php
Textarea::make('content')
    ->label('Content')
    ->required()
    ->rows(10)
```

**After:**
```php
use Filament\Forms\Components\RichEditor; // ← Add import

// In Section::make('Page Content'):
RichEditor::make('content')
    ->label('Page Content')
    ->helperText('Use the toolbar to format text, add images, and create structured content')
    ->required()
    ->json() // ← Store as JSON for flexibility
    ->toolbarButtons([
        'bold',
        'italic',
        'underline',
        'strike',
        'h2',
        'h3',
        'h4',
        'bulletList',
        'orderedList',
        'link',
        'blockquote',
        'codeBlock',
    ])
    ->fileAttachmentsDisk('public')
    ->fileAttachmentsDirectory('page-images')
    ->fileAttachmentsVisibility('public')
    ->columnSpanFull(),
```

---

## Step 2: Update Page Model (5 minutes)

```bash
# File: app/Models/Page.php
```

**Add accessor for HTML rendering:**

```php
use Filament\Support\Components\RichContentRenderer;

class Page extends Model
{
    // ... existing code ...

    /**
     * Get HTML version of content for frontend display
     */
    public function getContentHtmlAttribute(): string
    {
        // If content is stored as JSON
        if ($this->isJsonContent()) {
            return RichContentRenderer::make($this->content)->toHtml();
        }

        // If content is already HTML
        return $this->content;
    }

    /**
     * Check if content is JSON format
     */
    protected function isJsonContent(): bool
    {
        if (empty($this->content)) {
            return false;
        }

        json_decode($this->content);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Get plain text excerpt from content
     */
    public function getExcerptAttribute(int $length = 200): string
    {
        $html = $this->content_html;
        $text = strip_tags($html);
        return substr($text, 0, $length) . (strlen($text) > $length ? '...' : '');
    }
}
```

---

## Step 3: Ensure Storage Link Exists (1 minute)

```bash
php artisan storage:link
```

This creates: `public/storage -> storage/app/public`

---

## Step 4: Test in Admin Panel (5 minutes)

```bash
# Ensure server is running
php artisan serve --port=8001
```

1. Navigate to: http://127.0.0.1:8001/admin/pages
2. Click "Create Page"
3. Test the RichEditor:
   - Add heading (H2, H3)
   - Format text (bold, italic)
   - Create lists
   - Add a blockquote
   - Upload an image (drag & drop)
4. Save the page
5. Edit the page again - verify content persists

---

## Step 5: Update Frontend Views (Optional, 2 minutes)

If you have frontend views displaying pages:

```blade
{{-- resources/views/pages/show.blade.php --}}

<article class="prose prose-lg max-w-none">
    {!! $page->content_html !!}
</article>
```

**Note:** Use Tailwind Typography (`prose` class) for automatic styling.

If not using Tailwind Typography, add to `tailwind.config.js`:

```js
module.exports = {
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
```

---

## Complete Code Changes

### PageForm.php (Full Section)

```php
<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page Information')
                    ->description('Basic page details and metadata')
                    ->schema([
                        TextInput::make('title')
                            ->label('Page Title')
                            ->helperText('e.g., "About Us", "Contact"')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->helperText('URL-friendly version (e.g., "about-us")')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash']),
                    ]),

                Section::make('Page Content')
                    ->description('Main content with rich text formatting')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Page Content')
                            ->helperText('Use the toolbar to format text, add images, and create structured content')
                            ->required()
                            ->json() // ← Store as JSON for flexibility
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'h2',
                                'h3',
                                'h4',
                                'bulletList',
                                'orderedList',
                                'link',
                                'blockquote',
                                'codeBlock',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('page-images')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ]),

                Section::make('Page Status')
                    ->description('Publication settings')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Make this page visible on the website')
                            ->default(false),
                    ]),
            ]);
    }
}
```

---

## Verification Checklist

After implementation, verify:

- [ ] RichEditor appears in Page create/edit form
- [ ] Toolbar buttons work (bold, italic, headings, etc.)
- [ ] Image upload works (drag & drop or button)
- [ ] Uploaded images stored in `storage/app/public/page-images/`
- [ ] Images accessible via `/storage/page-images/[filename]`
- [ ] Content saves as JSON in database
- [ ] Frontend displays content correctly via `$page->content_html`
- [ ] Excerpt extraction works via `$page->excerpt`

---

## Troubleshooting

### Issue: Image Upload Fails

**Solution:**
```bash
# Ensure storage link exists
php artisan storage:link

# Check permissions
chmod -R 775 storage/app/public
```

### Issue: Content Not Rendering

**Check:** Model accessor is defined
```php
// In Page.php
public function getContentHtmlAttribute(): string
{
    return RichContentRenderer::make($this->content)->toHtml();
}
```

### Issue: "Class RichEditor not found"

**Solution:** Add import in PageForm.php:
```php
use Filament\Forms\Components\RichEditor;
```

### Issue: Toolbar Buttons Not Showing

**Check:** toolbarButtons array is correctly defined
```php
->toolbarButtons([
    'bold', 'italic', // ... etc
])
```

---

## Next Steps (Optional Enhancements)

### 1. Add Image Optimization (Future)

Install Intervention Image:
```bash
composer require intervention/image-laravel
```

### 2. Add CDN for Images (Production)

Update `.env`:
```env
CDN_URL=https://cdn.musikfuerfirmen.de
```

Update `config/filesystems.php`:
```php
'public' => [
    'url' => env('CDN_URL', env('APP_URL').'/storage'),
],
```

### 3. Add Full-Text Search (Future)

Create computed column for plain text search:
```php
Schema::table('pages', function (Blueprint $table) {
    $table->text('content_text')->nullable();
    $table->fullText('content_text');
});
```

---

## Database Migration (If Needed)

If your current `content` column is `varchar` or limited, migrate to `text`:

```bash
php artisan make:migration update_pages_content_to_text
```

```php
public function up(): void
{
    Schema::table('pages', function (Blueprint $table) {
        $table->text('content')->change();
    });
}
```

```bash
php artisan migrate
```

---

## Time Estimate

- Step 1 (PageForm): 5 minutes
- Step 2 (Model): 5 minutes
- Step 3 (Storage): 1 minute
- Step 4 (Testing): 5 minutes
- **Total: ~15 minutes**

---

## Support Resources

- **Full Analysis:** See `WYSIWYG_EDITOR_RESEARCH_AND_IMPLEMENTATION.md`
- **Filament Docs:** https://filamentphp.com/docs/4.x/forms/rich-editor
- **TipTap Docs:** https://tiptap.dev/docs
- **Laravel Storage:** https://laravel.com/docs/11.x/filesystem

---

**Last Updated:** 2026-01-15
**Status:** Ready to Implement
