---
description: Format, test, and prepare for Git push
---

1. Run `composer run format`
2. If phpstan triggers a warning, fix the issue. Re-run to confirm success.
3. Run `pest`. If test fail, fix tests and report changes. Re-run `composer run format` once suit returns green.
