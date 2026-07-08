---
description: Run the Pest test suite (optionally filtered by $ARGUMENTS).
---

Run the tests. If `$ARGUMENTS` is set, run `vendor/bin/pest --filter=$ARGUMENTS`;
otherwise run `composer test`. Report failures with output; never call the work done while
tests are red.
