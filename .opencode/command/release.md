---
description: Cut a release — run the gates, update the CHANGELOG, tag and push. $ARGUMENTS = patch|minor|major.
---

Release a new version (bump: `$ARGUMENTS`, SemVer). Follow `AGENTS.md` §7.

1. On `main`, clean tree. Run `composer test`, `composer lint`, `composer analyse` — all
   must be green.
2. Next version from the latest tag + bump.
3. Move `## [Unreleased]` entries in `CHANGELOG.md` into `## [X.Y.Z] - <today>`; leave a
   fresh empty Unreleased.
4. `git commit -am "release: vX.Y.Z"` then `git tag vX.Y.Z && git push origin main --tags`.

NEVER force-push, delete, move, or rewrite a published tag — the package is public on
Packagist and duplicate/rewritten tags error out. Cut a new patch instead.
