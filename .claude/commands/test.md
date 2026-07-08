---
description: Run the Pest test suite (optionally filtered).
argument-hint: [filter]
allowed-tools: Bash
---

Run the test suite.

- If `$1` is provided: `vendor/bin/pest --filter=$1`
- Otherwise: `composer test`

Report failures with the relevant output. Do not mark work done while tests are red.
