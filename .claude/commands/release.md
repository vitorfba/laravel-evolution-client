---
description: Cut a release — run the gates, update the CHANGELOG, tag and push.
argument-hint: <patch|minor|major>
allowed-tools: Read, Edit, Bash
---

Release a new version. Bump type: **$1** (patch | minor | major — SemVer). Follow
`AGENTS.md` §7.

1. Ensure the branch is `main` and the tree is clean (`git status`).
2. Run all three gates and require green:
   ```bash
   composer test
   composer lint
   composer analyse
   ```
3. Compute the next version from the latest tag (`git describe --tags --abbrev=0`) and the
   `$1` bump.
4. In `CHANGELOG.md`, move `## [Unreleased]` entries into a new
   `## [X.Y.Z] - YYYY-MM-DD` section (today's date), keeping the
   `### Added/Changed/Fixed/Removed` grouping, and leave a fresh empty `## [Unreleased]`.
5. Commit: `git commit -am "release: vX.Y.Z"`.
6. Tag and push: `git tag vX.Y.Z && git push origin main --tags`.

NEVER `git push --force`, never delete or move an existing tag, and never rewrite a
published tag — the package is public on Packagist and a duplicate/rewritten tag errors
out. If a tag was wrong, cut a new patch version instead.
