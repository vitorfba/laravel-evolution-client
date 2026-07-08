# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0] - 2026-07-08

### Added
- **Inbound webhook handling.** New `WebhookProcessor` manager parses Evolution payloads
  into a typed `WebhookEvent` (normalised event names, dot-access to `data`, `is*()`
  helpers) and dispatches the `EvolutionWebhookReceived` Laravel event plus a named
  `evolution.webhook.{event}` event. Inline `on()` / `onAny()` callbacks are also
  supported. An optional, config-gated route + `WebhookController` is provided, and a
  queued `ProcessEvolutionWebhook` job lets the endpoint return immediately and process
  off the request lifecycle (`config('evolution.webhook.queue.enabled')`).
- `Message::sendMedia()` for image/video/document/audio media messages.
- `Instance::setPresence()`.
- `Chat`: `markChatUnread()`, `updateMessage()`, `fetchProfilePictureUrl()`,
  `findStatusMessage()`, `getBase64FromMediaMessage()`, `updateBlockStatus()`.
- `Group`: `updateGroupPicture()`, `revokeInviteCode()`, `inviteInfo()`, `sendInvite()`,
  `toggleEphemeral()`, `updateSetting()`.
- Developer tooling: `AGENTS.md` + `CLAUDE.md` guides, `.claude/`, `.opencode/` and
  `.antigravity/` agent configurations, and a Laravel Boost skill at
  `resources/boost/laravel-evolution-client/SKILL.md`.

### Changed
- **Tooling:** replaced `friendsofphp/php-cs-fixer` with **Laravel Pint** (`pint.json`) and
  added **Larastan level 5** (`phpstan.neon.dist`). CI workflows updated accordingly
  (`fix-php-code-style-issues.yml`, `phpstan.yml`).
- Aligned several methods with the Evolution API v2 spec (fixes requests that previously
  hit non-existent v1-style paths). Note the following signature changes:
  - `Chat::markAsRead(array $readMessages)` (was a single string).
  - `Chat::archive(string $chat, array $lastMessageKey)` /
    `Chat::unarchive(string $chat, array $lastMessageKey)`.
  - `Instance::createInstance()` now sends the required `integration`
    (default `WHATSAPP-BAILEYS`) plus optional `token`/`number`/`qrcode`.
  - `Instance::setWebhook()` body remapped (`base64` → `webhookBase64`, adds
    `webhookByEvents`).

### Fixed
- `Instance::restart()` now uses `PUT` (was `POST`).
- `Instance::isConnected()`/`getStatus()` use `GET /instance/connectionState/{instance}`
  (the previous `/instance/status` endpoint does not exist).
- `Message::sendLocation()`, `Chat::markAsRead()`, `Chat::archive()`/`unarchive()`,
  `Chat::startTyping()`/`stopTyping()`, and several `Group` methods (`create`, `all`,
  `find`, `updateSubject`, `updateDescription`, `getInviteCode`, `joinWithInviteCode`,
  `leave`, `updateParticipant`) corrected to the proper v2 verbs, paths and request bodies.
- Guzzle error handling no longer assumes `hasResponse()` on the base `GuzzleException`.
- Replaced the deprecated `React\EventLoop\Factory::create()` with `Loop::get()`.

## [2.0.0] - 2026-06-22

### Added
- Added `Business` resource supporting `getCatalog` and `getCollections` endpoints.
- Added `connectionState()`, `getWebhook()`, and `fetchInstances()` methods to `Instance` resource.
- Added `findChats()`, `findMessages()`, `findContacts()`, and `whatsappNumbers()` to `Chat` resource.
- Added `getParticipants()` and a unified `updateParticipant()` method (handling add/remove/promote/demote actions) to `Group` resource.
- Added `edit()` and `delete()` methods to `Template` resource.
- Exposed the missing `getInstanceName()` and `setInstanceName()` helper methods to the `OpenAIBot` resource.
- Fully registered `Business`, `OpenAIBot`, and `EvolutionBot` resources within `EvolutionApiClient` and the `Evolution` Facade.

### Changed
- Updated `setWebhook()` in `Instance` resource to use the new `/webhook/set/{instanceName}` v2 endpoint format.

## [1.12.2] - 2025-08-22

### Fixed
- Fix connect

## [1.12.1] - 2025-07-24

### Fixed
- Fix Get Qr

## [1.12.0] - 2025-07-22

### Added
- Laravel 12 Support

## [1.0.0] - 2025-04-11

### Added
- Initial package version
- Support for instance management
- Sending text messages, images, documents, location, and contacts
- Chat and group management
- Webhooks for events
