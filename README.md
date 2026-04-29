# PhpSPA React Template

**Run React directly with PHP** - No Node.js build step required for development. This template combines React + TypeScript + Vite with PHP backend in a single project, running on the same host.

## Installation

```bash
composer create-project dconco/phpspa-react
```

## Architecture

This project runs **PHP and React on the same host** without CORS issues. PHP serves the HTML shell and can pass data directly to React components through the DOM.

### Project Structure

```
phpspa-react/
├── app/              # PHP components (routes)
│   ├── layout.php    # HTML shell with #app div
│   ├── profile.php   # Example route with data
│   └── default.php   # Default route
├── public/
│   └── index.php     # Entry point
├── src/              # React components
│   └── App.tsx       # Main React app
├── config.php        # Vite/asset configuration
└── composer.json
```

## PHP → React Data Passing

Each PHP route component can inject data into the HTML that React reads on mount. This is done using `<script type="application/json">` tags inside the `#app` div.

### How It Works

1. **PHP renders HTML** with embedded JSON data
2. **React mounts** and reads the JSON from the DOM
3. **React knows the route** and has the data to initialize state

### Example: Profile Route

**PHP Component** (`app/profile.php`):

```php
<?php
use PhpSPA\Component;
use PhpSPA\Http\Request;
return new Component(function(Request $request) {
   $name = $request('name', 'Dave Conco');
   $email = $request('email', 'me@dconco.tech');
   $gender = $request('gender', 'Male');

   $userData = json_encode(compact('name', 'email', 'gender'));

   return <<<HTML
      <h2>Profile Page PHP Render</h2>
      <script type="application/json" id="profile-script">{$userData}</script>
   HTML;
})->route('/profile');
```

**React Component** (`src/App.tsx`):

```tsx
import { useEffect } from 'react'

function App() {
	useEffect(() => {
		const profileScript = document.getElementById('profile-script')

		if (profileScript) {
			// This is Profile Route
			const data = JSON.parse(profileScript.textContent!)
			console.log('Profile data:', data)
			// Initialize state with PHP data
		}
	}, [])

	return <div>...</div>
}
```

## Configuration

### Development Mode

In `config.php`, set `mode` to `'development'` to use Vite dev server:

```php
$config = [
   'mode' => 'development',
   'dev_server_url' => 'http://localhost:5173',
   'dev_url_base' => '/@dev-server'
];
```

### Production Mode

Set `mode` to `'production'` to use built assets from `public/dist/`:

```php
$config = [
   'mode' => 'production',
   'assets_url' => '/dist/',
   'dist_dir' => __DIR__ . '/public/dist',
   'manifest_file' => __DIR__ . '/public/dist/manifest.json',
   'manifest_entry' => 'src/main.tsx',
];
```

## Security Warning

⚠️ **Do not pass sensitive data** (passwords, secret keys, tokens) through PHP → React data passing. The JSON data is visible in the page source code and can be inspected by anyone.
**Safe to pass:**

- User display names
- Public profile information
- Route identifiers
- Non-sensitive configuration
  **Never pass:**
- Passwords
- API secret keys
- Authentication tokens
- Database credentials

## State Management

Use the `@dconco/phpspa` package for shared state between PHP and React:

```tsx
import { setState } from '@dconco/phpspa'

// Update global state
setState('counter', count)

// Read state with callback
setState('counter', prev => {
	setCount(prev ?? 0)
})
```

## License

MIT - Dave Conco (dconco)
