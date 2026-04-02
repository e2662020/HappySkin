# HappySkin MediaWiki Skin

A clean, black-and-white MediaWiki skin with modern design, similar to Vercel's style.

## Features

- **Black & White Design**: Clean minimalistic aesthetic with white background and black rounded rectangles in light mode, black background and white rounded rectangles in dark mode
- **Dark/Light Mode**: Automatic detection based on system preferences + manual toggle
- **Vector 2022 Layout**: Three-column layout with sidebar, content, and table of contents
- **Responsive Design**: Mobile-friendly with sidebar that slides out from left
- **Custom Theme Color**: Configurable accent color using oklch color space
- **No Homepage Title**: Main page doesn't show the title for cleaner look

## Installation

1. Download or clone this repository into your MediaWiki `skins/` directory:
   ```
   cd skins
   git clone <repository-url> HappySkin
   ```

2. Add the following to your `LocalSettings.php`:
   ```php
   wfLoadSkin( 'HappySkin' );
   ```

3. To set HappySkin as the default skin, add:
   ```php
   $wgDefaultSkin = 'HappySkin';
   ```

## Configuration

### Custom Theme Color

You can customize the accent color by adding this to `LocalSettings.php`:

```php
$wgHappySkinThemeColor = '0.7 0.1 240'; // Default blue
```

The format is `lightness chroma hue` in oklch color space:
- **Lightness**: 0 (black) to 1 (white)
- **Chroma**: 0 (gray) to 0.4 (vibrant)
- **Hue**: 0 (red) to 360 (degrees)

Examples:
- Blue: `0.7 0.1 240`
- Purple: `0.7 0.1 290`
- Green: `0.7 0.1 140`
- Red: `0.7 0.1 25`
- Orange: `0.7 0.1 60`

### Performance Optimizations

HappySkin is optimized for performance:
- Minimal CSS with CSS variables for theme switching
- Lazy loading where appropriate
- Efficient DOM structure
- No unnecessary JavaScript

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Supports CSS Custom Properties (CSS Variables)
- Supports oklch color space

## License

GPL-2.0-or-later
