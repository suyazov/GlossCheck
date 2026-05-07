# GlossCheck DeepSeek Chat

## API key

On a normal WordPress hosting, add the key to `wp-config.php`:

```php
define('GLOSSCHECK_DEEPSEEK_API_KEY', 'your_deepseek_api_key');
```

For the local Docker setup, start WordPress with:

```bash
GLOSSCHECK_DEEPSEEK_API_KEY=your_deepseek_api_key docker compose up -d
```

The key is read only on the server and is never printed into the browser.

## Admin settings

Open `wp-admin` and use `Настройки чата` to edit:

- prompt for new dialogs
- DeepSeek model, default `deepseek-chat`
- dialog history limit, default `20` total messages
- max response tokens, default `1200`
- rate limit, default `12` messages per minute per visitor

The prompt is snapshotted when a new chat is created. Existing chats continue
with their original prompt until the visitor clears or starts a new dialog.

## Storage

Chats and messages are stored in WordPress DB tables:

- `wp_glosscheck_chats`
- `wp_glosscheck_messages`

## Shortcode

Use this shortcode on any WordPress page:

```text
[deepseek_chat]
```
