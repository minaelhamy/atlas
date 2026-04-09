# Atlas System Guide

## Overview

Atlas is a PHP-based AI workspace for founders and operators. It combines:

- account and membership management
- company intelligence capture
- AI content generation
- social media campaign and Instagram grid generation
- AI chat agents
- speech and document tools
- a builder handoff flow into external website platforms

Atlas is the main application served from `atlas.hatchers.ai`. It also acts as the entry point for two embedded builder applications that live in the same codebase:

- `Storemart_SaaS` mounted at `/ecom`
- `BookingDo_SaaS` mounted at `/service`

Atlas itself is the user-facing control plane. Bazaar and Servio are the execution platforms for website building.

## What Atlas Does

At a product level, Atlas works in this order:

1. A user signs up or logs in.
2. The user fills in Company Intelligence.
3. Atlas stores business context and uses it across all AI features.
4. The user can generate:
   - documents
   - social campaigns
   - Instagram grids
   - AI chats
   - speech and code outputs
5. If the user wants a website, Atlas detects whether the business needs:
   - ecommerce
   - service / booking
6. Atlas syncs the user into the correct builder platform and redirects them there.

The most important architectural idea is that Atlas owns the business context and user entry flow, while specialized systems handle execution in their own domains.

## High-Level Architecture

Atlas is a hybrid PHP application with a custom bootstrap and route layer, plus two mounted Laravel applications for the website builders.

### Main components

- Front controller: [index.php](/Users/minaelhamy/Downloads/atlas/index.php)
- Route map: [php/_route.php](/Users/minaelhamy/Downloads/atlas/php/_route.php)
- URL/link map: [php/_links.php](/Users/minaelhamy/Downloads/atlas/php/_links.php)
- Shared business logic:
  - [includes/functions/func.global.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.global.php)
  - [includes/functions/func.users.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.users.php)
  - [includes/functions/func.app.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.app.php)
  - [includes/functions/func.social.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.social.php)
- AJAX controller:
  - [php/6a7i8lsi3m.php](/Users/minaelhamy/Downloads/atlas/php/6a7i8lsi3m.php)
  - exposed through [php/user-ajax.php](/Users/minaelhamy/Downloads/atlas/php/user-ajax.php)
- User UI theme:
  - [templates/classic-theme](/Users/minaelhamy/Downloads/atlas/templates/classic-theme)
- Admin panel:
  - [admin](/Users/minaelhamy/Downloads/atlas/admin)

### Builder applications

- Ecommerce builder:
  - [Storemart_SaaS](/Users/minaelhamy/Downloads/atlas/Storemart_SaaS)
- Service / booking builder:
  - [BookingDo_SaaS](/Users/minaelhamy/Downloads/atlas/BookingDo_SaaS)

These are deployed under the same server root and mounted by Atlas:

- `/ecom/...` delegates into `Storemart_SaaS`
- `/service/...` delegates into `BookingDo_SaaS`

That mount logic lives in [index.php](/Users/minaelhamy/Downloads/atlas/index.php).

## Request Lifecycle

### 1. Incoming request

All requests first hit [index.php](/Users/minaelhamy/Downloads/atlas/index.php).

That bootstrap does three jobs:

- detect whether the request is for `/ecom` or `/service`
- if yes, delegate the request into the correct builder app
- if not, continue booting the main Atlas app

### 2. Atlas bootstrap

For a normal Atlas request, the bootstrap:

- defines `ROOTPATH` and `APPPATH`
- loads environment-backed config from [includes/config.php](/Users/minaelhamy/Downloads/atlas/includes/config.php)
- loads AltoRouter
- loads the route map from [php/_route.php](/Users/minaelhamy/Downloads/atlas/php/_route.php)
- loads the app autoloader and language files
- starts the secure session
- opens the MySQL connection
- loads the current user if logged in

### 3. Route dispatch

Atlas uses AltoRouter route mapping. Routes point directly to PHP controller files inside `php/app` or `php/global`.

Examples:

- `/dashboard` -> [php/app/dashboard.php](/Users/minaelhamy/Downloads/atlas/php/app/dashboard.php)
- `/company-intelligence` -> [php/global/company-intelligence.php](/Users/minaelhamy/Downloads/atlas/php/global/company-intelligence.php)
- `/ai-images` -> [php/app/ai-images.php](/Users/minaelhamy/Downloads/atlas/php/app/ai-images.php)
- `/build-website` -> [php/app/build-website.php](/Users/minaelhamy/Downloads/atlas/php/app/build-website.php)

### 4. Template rendering

Controllers typically gather data and then render a Classic Theme template through the template system.

Examples:

- [templates/classic-theme/dashboard.php](/Users/minaelhamy/Downloads/atlas/templates/classic-theme/dashboard.php)
- [templates/classic-theme/ai-images.php](/Users/minaelhamy/Downloads/atlas/templates/classic-theme/ai-images.php)
- [templates/classic-theme/ai-images-grid.php](/Users/minaelhamy/Downloads/atlas/templates/classic-theme/ai-images-grid.php)

## Code Organization

### `php/app`

Authenticated product features and dashboard screens.

Examples:

- dashboard
- AI templates
- AI chat
- social media generation
- Build Website

### `php/global`

Core site pages and account flows.

Examples:

- login
- signup
- account settings
- membership
- company intelligence
- blog
- payment callbacks

### `includes/functions`

This is the main application logic layer.

Key files:

- [func.global.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.global.php)
  - environment access
  - database connection helpers
  - site settings
  - redirects
  - utility functions
- [func.users.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.users.php)
  - authentication
  - session creation
  - profile access
  - user options
- [func.app.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.app.php)
  - OpenAI model selection and API helpers
- [func.social.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.social.php)
  - company intelligence
  - social campaigns
  - Instagram grids
  - remote image search
  - builder routing and provisioning

### `php/6a7i8lsi3m.php`

This is the main AJAX action controller for user-side features. It handles actions such as:

- content generation
- image generation
- Instagram grid generation
- post regeneration
- image voting
- overlay text save
- company intelligence extraction and save
- AI chat actions

## Data and Persistence

Atlas stores data in MySQL and uses two access styles:

- `mysqli` for some procedural or legacy paths
- Idiorm / Paris-style ORM for many entity-based paths

Configuration comes from:

- [includes/config.php](/Users/minaelhamy/Downloads/atlas/includes/config.php)
- [includes/db.php](/Users/minaelhamy/Downloads/atlas/includes/db.php)

Key environment variables:

- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `DB_PREFIX`

### Atlas user data

Atlas stores:

- user account details
- password hash
- memberships and plans
- AI usage counters
- company intelligence profile
- generated documents
- generated images
- post feedback and votes

### User options

Atlas also uses a `user_options` pattern for flexible per-user state.

Examples of state that can live there:

- usage counters
- company intelligence cached JSON
- builder platform preferences

## Authentication Model

Atlas has its own authentication and session system.

Main logic:

- [includes/functions/func.users.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.users.php)
- [php/global/login.php](/Users/minaelhamy/Downloads/atlas/php/global/login.php)
- [php/global/signup.php](/Users/minaelhamy/Downloads/atlas/php/global/signup.php)

Passwords are stored as PHP `password_hash(...)` hashes. That matters because Bazaar and Servio can reuse the same hash for synchronized accounts.

## Company Intelligence

Company Intelligence is one of the central features of Atlas.

It captures and refines:

- company description
- ICP
- top problems solved
- unique selling points
- brand colors
- tone attributes
- reference brands

This data becomes the shared context for:

- dashboard summaries
- social content generation
- Instagram grids
- website builder routing

Core implementation:

- [php/global/company-intelligence.php](/Users/minaelhamy/Downloads/atlas/php/global/company-intelligence.php)
- [templates/classic-theme/global/company-intelligence.php](/Users/minaelhamy/Downloads/atlas/templates/classic-theme/global/company-intelligence.php)
- [includes/functions/func.social.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.social.php)
- [php/6a7i8lsi3m.php](/Users/minaelhamy/Downloads/atlas/php/6a7i8lsi3m.php)

Atlas can also extract a profile from a website URL and refresh company intelligence with AI.

## Social Media System

The social media system is one of the most custom parts of Atlas.

It supports:

- social campaign post generation
- Instagram grid generation
- image selection from local and remote sources
- OpenAI-based copy generation
- overlay message generation
- per-image voting
- per-tile regeneration
- editable overlays

Core logic lives in [func.social.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.social.php).

### External services used here

- OpenAI
- Unsplash
- Pexels
- Pixabay

Relevant environment variables:

- `OPENAI_API_KEY`
- `OPENAI_IMAGE_API_KEY`
- `OPENAI_MODEL_DEFAULT`
- `OPENAI_CHAT_MODEL_DEFAULT`
- `OPENAI_IMAGE_MODEL_DEFAULT`
- `SOCIAL_REMOTE_ASSETS_ENABLED`
- `SOCIAL_REMOTE_ASSET_SOURCES`
- `UNSPLASH_ACCESS_KEY`
- `PEXELS_API_KEY`
- `PIXABAY_API_KEY`

### Social generation flow

1. Atlas loads company intelligence and recent context.
2. Atlas builds a messaging prompt.
3. OpenAI generates structured campaign or grid content.
4. Atlas searches and scores images from local assets and remote providers.
5. Atlas renders previews into `storage/social_posts`.
6. The user can:
   - regenerate a single image
   - edit overlay text
   - vote thumbs up
   - vote thumbs down
7. Atlas stores feedback so future retrieval can improve.

### Current UX details

The system now supports:

- regenerate per image in campaigns and grids
- thumbs up / thumbs down per image
- inline overlay text editing
- hidden debug blocks in the UI

## AI Chat and Other AI Features

Atlas also includes:

- AI chat bots
- AI template generation
- speech-to-text
- text-to-speech
- AI code generation

These are surfaced under routes in [php/_route.php](/Users/minaelhamy/Downloads/atlas/php/_route.php) and backed by [func.app.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.app.php), [func.social.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.social.php), and AJAX actions in [php/6a7i8lsi3m.php](/Users/minaelhamy/Downloads/atlas/php/6a7i8lsi3m.php).

## Build Website Flow

Atlas no longer tries to host all website building logic itself.

Instead, the current architecture is:

- Atlas detects business type from Company Intelligence
- ecommerce businesses go to Bazaar
- service businesses go to Servio

### Detection

Builder routing is currently based on signals in Company Intelligence such as:

- company industry
- company description
- key products
- differentiators
- ICP
- company summary

Detection code:

- [func.social.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.social.php)

### Current provisioning model

When a user clicks `Build Website`:

1. Atlas detects the correct platform.
2. Atlas builds a signed payload including:
   - Atlas user id
   - username
   - email
   - phone
   - password hash
   - company intelligence fields
3. Atlas sends that payload to:
   - Bazaar `/atlas/provision`
   - or Servio `/atlas/provision`
4. The target app:
   - finds an existing linked user by `atlas_user_id`
   - or falls back to matching by email
   - creates or updates the workspace
   - copies the Atlas password hash
   - updates profile/company fields
5. Atlas redirects the user to the target app login page.
6. The target app login page shows:
   - `Use your Hatchers credentials to login.`

### Why this matters

This means Atlas users do not need a separate password for Bazaar or Servio.

It also means Atlas does not need direct database credentials for Bazaar or Servio. The integration is API-style, signed, and app-to-app.

### Shared secret

The provisioning flow uses:

- `WEBSITE_PLATFORM_SHARED_SECRET`

This value must match in:

- Atlas
- Bazaar
- Servio

### Mounted builder paths

Atlas mounts the builder apps under:

- `/ecom`
- `/service`

Examples:

- `https://atlas.hatchers.ai/ecom/...`
- `https://atlas.hatchers.ai/service/...`

In addition, the builder-specific domains can still be used directly if configured:

- `https://bazaar.hatchers.ai`
- `https://servio.hatchers.ai`

## UI Layer

Atlas currently uses the Classic Theme UI under:

- [templates/classic-theme](/Users/minaelhamy/Downloads/atlas/templates/classic-theme)

Key UI files:

- [dashboard_sidebar.php](/Users/minaelhamy/Downloads/atlas/templates/classic-theme/dashboard_sidebar.php)
- [dashboard.php](/Users/minaelhamy/Downloads/atlas/templates/classic-theme/dashboard.php)
- [ai-images.php](/Users/minaelhamy/Downloads/atlas/templates/classic-theme/ai-images.php)
- [ai-images-grid.php](/Users/minaelhamy/Downloads/atlas/templates/classic-theme/ai-images-grid.php)

The frontend is mostly server-rendered PHP templates with jQuery-style AJAX interactions to `php/user-ajax.php`.

## Deployment Architecture

Atlas is currently set up for Namecheap shared hosting with cPanel Git deployment.

### Source control and deploy model

- source of truth: GitHub
- server deploy: cPanel Git Version Control
- deploy instructions: [DEPLOYMENT.md](/Users/minaelhamy/Downloads/atlas/DEPLOYMENT.md)
- deploy script: [.cpanel.yml](/Users/minaelhamy/Downloads/atlas/.cpanel.yml)

### How deployment works

1. Push code to GitHub.
2. In cPanel, pull the latest code.
3. Use `Deploy HEAD Commit`.
4. cPanel runs the tasks in `.cpanel.yml`.
5. Files are synced into:
   - `/home/hatchwan/atlas.hatchers.ai/`

### Important exclusions

The deploy excludes:

- `.git`
- `.github`
- `.env`
- `storage/`
- `admin/uploads/`
- `install/`
- `error_log`
- `php/error_log`

This protects runtime content and server-only secrets.

## Environment Variables

Atlas reads configuration from server environment variables.

Main example file:

- [.env.example](/Users/minaelhamy/Downloads/atlas/.env.example)

### Core Atlas variables

- `APP_INSTALLED`
- `APP_VERSION`
- `APP_DEBUG`
- `SITE_URL`
- `APP_URL`
- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `DB_PREFIX`

### AI variables

- `OPENAI_API_KEY`
- `OPENAI_IMAGE_API_KEY`
- `OPENAI_MODEL_DEFAULT`
- `OPENAI_CHAT_MODEL_DEFAULT`
- `OPENAI_IMAGE_MODEL_DEFAULT`
- `OPENAI_PROXY`

### Social asset variables

- `SOCIAL_REMOTE_ASSETS_ENABLED`
- `SOCIAL_REMOTE_ASSET_SOURCES`
- `UNSPLASH_ACCESS_KEY`
- `PEXELS_API_KEY`
- `PIXABAY_API_KEY`

### Builder integration variables

- `BAZAAR_URL`
- `SERVIO_URL`
- `WEBSITE_PLATFORM_SHARED_SECRET`

## Current Deployment of Bazaar and Servio

At the moment, Bazaar and Servio are part of the same repository and same server deployment.

Operationally:

- Atlas is the top-level entry point.
- Atlas can internally mount:
  - [Storemart_SaaS](/Users/minaelhamy/Downloads/atlas/Storemart_SaaS)
  - [BookingDo_SaaS](/Users/minaelhamy/Downloads/atlas/BookingDo_SaaS)
- Each app keeps its own database credentials in its own `.env`.
- Atlas should not directly connect to those databases.

This is the clean boundary:

- Atlas owns orchestration and user entry
- Bazaar owns ecommerce data
- Servio owns service / booking data

## How to Use Atlas

### For end users

1. Sign up or log in.
2. Fill out Company Intelligence.
3. Use the dashboard to access:
   - Social Media Automation
   - AI Agents
   - AI templates
   - Documents
   - Images
4. If you want a website, click `Build Website`.
5. Atlas will choose the right builder and redirect you to the correct login page.

### For admins / operators

Use Atlas to:

- manage user accounts and plans
- monitor generated content and AI usage
- review company intelligence quality
- tune prompts and social generation logic
- maintain builder integrations

## Strengths of the Current Architecture

- one main control plane for users
- company intelligence reused across features
- builder apps isolated behind app-to-app integration
- environment-driven deployment
- social media engine is highly customizable
- shared hosting compatible

## Current Limitations

There are also some realities worth documenting clearly:

- Atlas mixes legacy PHP patterns with newer custom feature code
- the AJAX controller is centralized and large
- some persistence paths use `mysqli`, others use ORM
- the builder apps are separate systems with their own conventions
- deployment is simple, but shared hosting adds constraints
- there is no true SSO session sharing across Atlas, Bazaar, and Servio; it is credential sync plus redirect

## Recommended Operating Principles

If Atlas keeps growing, the safest principles are:

- keep Atlas as the orchestration layer
- keep Company Intelligence as the single reusable business context source
- avoid direct cross-app database access
- prefer signed API-style sync between Atlas and builder apps
- keep runtime content out of deploys
- document every new environment variable and route mount

## File Map for New Developers

If someone new joins the project, these are the best starting points:

- Bootstrap and routing:
  - [index.php](/Users/minaelhamy/Downloads/atlas/index.php)
  - [php/_route.php](/Users/minaelhamy/Downloads/atlas/php/_route.php)
  - [php/_links.php](/Users/minaelhamy/Downloads/atlas/php/_links.php)
- Core business logic:
  - [includes/functions/func.global.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.global.php)
  - [includes/functions/func.users.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.users.php)
  - [includes/functions/func.app.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.app.php)
  - [includes/functions/func.social.php](/Users/minaelhamy/Downloads/atlas/includes/functions/func.social.php)
- User-side feature endpoints:
  - [php/6a7i8lsi3m.php](/Users/minaelhamy/Downloads/atlas/php/6a7i8lsi3m.php)
- Main user dashboard:
  - [php/app/dashboard.php](/Users/minaelhamy/Downloads/atlas/php/app/dashboard.php)
  - [templates/classic-theme/dashboard.php](/Users/minaelhamy/Downloads/atlas/templates/classic-theme/dashboard.php)
- Website builder handoff:
  - [php/app/build-website.php](/Users/minaelhamy/Downloads/atlas/php/app/build-website.php)
  - [Storemart_SaaS/app/Http/Controllers/AtlasBridgeController.php](/Users/minaelhamy/Downloads/atlas/Storemart_SaaS/app/Http/Controllers/AtlasBridgeController.php)
  - [BookingDo_SaaS/app/Http/Controllers/AtlasBridgeController.php](/Users/minaelhamy/Downloads/atlas/BookingDo_SaaS/app/Http/Controllers/AtlasBridgeController.php)

## Summary

Atlas is best understood as a founder operating system:

- Atlas collects business context
- Atlas generates AI outputs using that context
- Atlas routes users into the right downstream systems
- Atlas remains the main user-facing workspace

The architecture is not a pure framework app, but it is understandable once you think of it in layers:

- bootstrap and routing
- feature controllers
- shared business logic
- server-rendered UI
- mounted specialist apps

That is the current foundation of how Atlas works today.
