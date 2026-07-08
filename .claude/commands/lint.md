---
description: Check code style (Pint) and run static analysis (Larastan level 5).
allowed-tools: Bash
---

Run the style + static-analysis gates:

```bash
vendor/bin/pint --test
composer analyse
```

If Pint reports style issues, run `composer format` to auto-fix, then re-check.
Fix Larastan errors properly — do not add blanket `ignoreErrors`. Report the final status.
