# Archive Manifest - 2026-05-22

This manifest documents all non-runtime files and legacy planning artifacts moved out of the main repository structure to make the codebase cleaner and more maintainable.

## Archived Files & Directories

### 1. Duplicate Nike Design System Folder
- **Original Path:** `nike/` (specifically `nike/DESIGN.md`)
- **Archived Path:** `.planning/archive/non_runtime_artifacts/2026-05-22/nike/DESIGN.md`
- **Reason:** This is a duplicate of the root-level `DESIGN.md` specification. It was placed inside a non-standard folder at the root level and is not required for application runtime, asset compilation, or testing.
- **Runtime Safety Verification:** The primary system documentation remains at the root level (`DESIGN.md`). No application routes, controllers, views, or scripts reference the `nike/` directory.

### 2. Legacy GSD Planning Artifacts (Phases 0 to 3)
- **Original Path:** `.planning/phases/00-core-architecture/00-01-PLAN.md`
- **Archived Path:** `.planning/archive/non_runtime_artifacts/2026-05-22/phases/00-core-architecture/00-01-PLAN.md`
- **Reason:** Legay planning document for Phase 0 (Core Architecture). Already fully implemented and validated.

- **Original Path:** `.planning/phases/01-product-ecosystem/01-01-PLAN.md`
- **Archived Path:** `.planning/archive/non_runtime_artifacts/2026-05-22/phases/01-product-ecosystem/01-01-PLAN.md`
- **Reason:** Legacy planning document for Phase 1 (Brand Foundation & Landing). Already fully implemented and validated.

- **Original Path:** `.planning/phases/02-shopping-experience/01-01-PLAN.md`
- **Archived Path:** `.planning/archive/non_runtime_artifacts/2026-05-22/phases/02-shopping-experience/01-01-PLAN.md`
- **Reason:** Legacy planning document for Phase 2 (Product Ecosystem). Already fully implemented and validated.

- **Original Path:** `.planning/phases/03-identity-security/01-01-PLAN.md`
- **Archived Path:** `.planning/archive/non_runtime_artifacts/2026-05-22/phases/03-identity-security/01-01-PLAN.md`
- **Reason:** Legacy planning document for Phase 3 (Shopping Experience). Already fully implemented and validated.

- **Runtime Safety Verification:** These are non-code markdown documents used by development agents to track tasks. They have zero integration with the Laravel runtime, database migrations, configuration files, front-end build pipelines, or the PHPUnit test suite.

## Verification Checklist
After moving these files, the following verification commands will be executed to guarantee safety:
- [ ] `php artisan test --compact` (Verify all tests pass)
- [ ] `php artisan route:list --path=admin` (Verify administrative routing remains intact)
- [ ] `cmd /c npm run build` (Verify Tailwind CSS v4 and JS build compilation compiles successfully)
