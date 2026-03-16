# Atlas Deployment

## Stack

- Source control: GitHub
- Delivery: cPanel Git Version Control
- Hosting: Namecheap shared hosting

## Deployment File

Atlas uses [`.cpanel.yml`](/Users/minaelhamy/Downloads/atlas/.cpanel.yml) for cPanel deployment.

Before using it, replace:

- `YOUR_CPANEL_USER`
- `YOUR_DOMAIN_OR_SUBDOMAIN_ROOT`

Example:

```yaml
---
deployment:
  tasks:
    - export DEPLOYPATH=/home/hatchwan/atlas.hatchers.ai/
    - /bin/mkdir -p "$DEPLOYPATH"
    - /bin/rsync -a --delete --chmod=Du=rwx,Dgo=rx,Fu=rw,Fgo=r --exclude '.git' --exclude '.github' --exclude '.cpanel.yml' --exclude '.env' --exclude '.env.local' --exclude 'AGENTS.md' --exclude 'storage/' --exclude 'admin/uploads/' --exclude 'install/' --exclude 'error_log' --exclude 'php/error_log' ./ "$DEPLOYPATH"
```

## Server Environment

Create a `.env` file in the live deploy root using [`.env.example`](/Users/minaelhamy/Downloads/atlas/.env.example) as the template.

Minimum required values:

- `APP_INSTALLED`
- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `DB_PREFIX`
- `SITE_URL`
- `APP_URL`
- `OPENAI_API_KEY`

## Normal Flow

1. Push code to GitHub.
2. In cPanel, open `Git Version Control`.
3. Pull the latest changes from GitHub.
4. Click `Deploy HEAD Commit`.
5. cPanel reads `.cpanel.yml` and syncs the checked-out repo into the live folder.

## Notes

- `.env` stays only on the server and is excluded from deploys.
- `storage/` is excluded so media, uploads, logos, avatars, generated images, and audio are preserved on the server.
- `install/` is excluded because Atlas no longer uses the installer flow in production.
- `admin/uploads/` is excluded because it is runtime/admin content, not source code.
