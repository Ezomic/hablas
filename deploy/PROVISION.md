# Provisioning hablas.thijssensoftware.nl

One-time steps to bring hablas live on the shared production droplet
(`thijssensoftware-prod`, 165.22.203.180). Subsequent releases run automatically
through GitHub Actions (`.github/workflows/deploy.yml`). Every step here touches
production, run them yourself as the `deploy` user, or explicitly hand me
SSH/DNS access.

Prerequisite: the THI-364 branch is merged to `main` so `git clone` gets the app
and the deploy workflow is present.

## 1. DNS

Create an A record for the droplet. The zone is hosted on DigitalOcean and every
sibling app (id, tracker, zero, billr, …) uses an A record only, no AAAA:

    hablas.thijssensoftware.nl.  A  165.22.203.180

Wait for it to resolve (`dig +short hablas.thijssensoftware.nl` → the droplet IP)
before requesting TLS.

## 2. App on the droplet (as `deploy`)

The droplet uses a per-repo deploy key exposed as an SSH host alias
(`github.com-hablas`), matching the other apps. Configure that alias in
`~/.ssh/config` with a read-only deploy key added to the `Ezomic/hablas` repo,
then:

    git clone git@github.com-hablas:Ezomic/hablas.git /home/deploy/hablas
    cd /home/deploy/hablas
    cp .env.production.example .env

Edit `.env` (see `.env.production.example` for the full annotated list):
- `php artisan key:generate`
- `GOOGLE_CLIENT_ID` / `GOOGLE_CLIENT_SECRET` — add `https://hablas.thijssensoftware.nl/auth/google/callback`
  as an authorised redirect URI on the OAuth client.
- `MAIL_*` — a real transactional mailer (the daily digest is emailed; without
  one, `SendDailyDigests` fails silently).

Build and initialise:

    composer install --no-dev --optimize-autoloader
    npm ci && npm run build
    php artisan migrate --force
    php artisan storage:link
    php artisan webpush:vapid          # once; copy the printed pair into .env
    php artisan optimize

### SQLite permissions

php-fpm runs as `www-data`; the queue/scheduler run as `deploy`. The DB file (and
its `-wal`/`-shm` siblings) must be group-writable by both, or you get
"readonly database" 500s:

    touch database/database.sqlite
    sudo chown deploy:www-data database/database.sqlite
    chmod 664 database/database.sqlite

## 3. TLS (shared cert)

On the droplet, in the infra repo (`~/infra`), add the domain to the guarded
source of truth and re-issue. Never hand-run `certbot --expand` (THI-309):

    echo 'hablas.thijssensoftware.nl' >> certs/thijssensoftware.nl.domains
    bin/renew-shared-cert.sh --dry-run   # confirm it keeps every existing SAN
    bin/renew-shared-cert.sh

## 4. Root prerequisite: sudoers rules (one-time, needs full root)

The `deploy` user's passwordless sudo is a hardcoded per-app allow-list; it can
reload/restart services and run certbot, but it CANNOT drop a new app's nginx
vhost or supervisor conf into `/etc`. A root admin adds three lines (mirroring
the existing `zero` rules) to the sudoers drop-in, e.g. `/etc/sudoers.d/deploy`:

    deploy ALL=(ALL) NOPASSWD: /bin/mv /tmp/hablas-nginx.conf /etc/nginx/sites-available/hablas
    deploy ALL=(ALL) NOPASSWD: /bin/ln -sf /etc/nginx/sites-available/hablas /etc/nginx/sites-enabled/hablas
    deploy ALL=(ALL) NOPASSWD: /bin/mv /tmp/hablas-supervisor.conf /etc/supervisor/conf.d/hablas.conf

Validate with `sudo visudo -c`. Once these exist, everything below runs as
`deploy` (no interactive root needed).

## 5. nginx

The vhost is staged at `/tmp/hablas-nginx.conf` (php8.4-fpm socket, shared
`thijssensoftware.nl` cert lineage). Install and reload:

    sudo mv /tmp/hablas-nginx.conf /etc/nginx/sites-available/hablas
    sudo ln -sf /etc/nginx/sites-available/hablas /etc/nginx/sites-enabled/hablas
    sudo systemctl reload nginx

(`nginx -t` needs full root; if unavailable, the reload will still fail loudly
on a bad config rather than applying it.)

## 6. Background workers (supervisor)

One conf file, two programs (`hablas-queue`, `hablas-scheduler`), staged at
`/tmp/hablas-supervisor.conf`:

    sudo mv /tmp/hablas-supervisor.conf /etc/supervisor/conf.d/hablas.conf
    sudo supervisorctl reread && sudo supervisorctl update

These are the groups the deploy workflow restarts
(`hablas-queue:*`, `hablas-scheduler:*`).

## 7. GitHub Actions deploy secrets

So `deploy.yml` can SSH in on every push to `main`, set three repo secrets on
`Ezomic/hablas` (Settings → Secrets and variables → Actions) — the same values
the other apps use:

- `DEPLOY_SSH_HOST` — `165.22.203.180`
- `DEPLOY_SSH_USER` — `deploy`
- `DEPLOY_SSH_KEY` — the private key whose public half is authorised for `deploy`

## 8. Verify

    curl -sS -o /dev/null -w '%{http_code}\n' https://hablas.thijssensoftware.nl/login   # 200

## Subsequent deploys

Automatic: pushing to `main` runs `.github/workflows/deploy.yml`, which resets
the checkout to `origin/main`, reinstalls, rebuilds, migrates, rebuilds caches,
reloads php8.4-fpm, and restarts the scheduler + queue worker. Trigger a manual
run any time from the Actions tab (`workflow_dispatch`).
