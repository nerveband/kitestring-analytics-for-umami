# Simply Analytics for Umami 1.1.3 implementation and release plan

The release will remove all reported npm advisories, verify the plugin in its documented test environment, and publish the installable ZIP. WordPress.org publication depends on the directory approving Simply Analytics for Umami and granting repository access.

## Findings

The 1.1.2 npm audit reports seven moderate findings through `@wordpress/env`. Two dependency chains cause them: Express uses a vulnerable `qs`, and js-yaml uses argparse and sprintf-js. sprintf-js has no published fixed version. The plugin ships none of these development tools. The Composer audit is clean.

## Implementation

1. Replace `@wordpress/env` with a project Docker Compose environment using the official WordPress, WP-CLI, and MariaDB images. This removes both vulnerable dependency chains without forcing incompatible dependency overrides.
2. Keep the local site on port 8888. Mount this plugin into WordPress, isolate its database and files in project volumes, wait for database readiness, and initialize the test administrator and plugin automatically.
3. Update the WP-CLI test helper and CI version matrix to use Compose. Preserve the existing tracking, recorder, migration, dashboard-access, and password tests.
4. Add dependency audit checks to CI and the release workflow. A release must pass audits, PHP coding standards, and browser integration tests before packaging.
5. Rename the public plugin to Simply Analytics for Umami, with slug and text domain `simply-analytics-for-umami`, as chosen by the author. Update the main file, admin labels, plugin settings link, packages, workflows, and directory artwork. Keep the existing options, namespaces, constants, script handles, and developer hooks so saved settings and integrations remain usable.
6. Add an explicit independent-maintainer statement to the plugin header and readme. Keep the original GPL attribution. Tell existing GitHub users to deactivate Simply Umami before enabling the renamed plugin.
7. Update the version and changelog to 1.1.3, document the replacement development commands, and exclude development files from WordPress.org packages.

## Verification

- Install JavaScript packages from the lockfile and require zero npm audit findings at every severity.
- Validate Composer metadata and require a clean Composer dependency audit.
- Validate Compose configuration, start the real container environment, and run all eleven Playwright tests against it.
- Run PHP coding standards. Inspect the rendered settings page if implementation changes its appearance.
- Build a production-only plugin ZIP. Check archive integrity, version consistency, source contents, and exclusion of development files and credentials.

## Publication

- Use the existing GitHub tag and release workflow after verification. Confirm the downloadable ZIP matches the tested source.
- Use Secret Gate's Telegram approval flow for the saved WordPress.org Plugin Directory account. Check the plugin review and repository status before publishing.
- If approved, publish 1.1.3 and directory assets through the repository's existing WordPress.org release workflow, then verify the directory version and download.
- If review is pending or repository access is unavailable, publish the GitHub release and report the exact directory blocker. Keep the approval gate in place.

## Recovery

Version 1.1.2 remains available as a GitHub release. Stop the new test containers with the documented command; keep database volumes unless intentionally resetting test data. A failed publication must leave the previous WordPress.org stable release unchanged.

## Results

The npm audit reports zero vulnerabilities after replacing wp-env. Composer validation and its dependency audit pass. All eleven Playwright tests pass against WordPress 7.1.3 and PHP 8.3 in the new Compose environment. PHP coding standards pass with two intentional warnings for service-managed tracker and recorder script versions.

WordPress.org accepted the updated 1.1.3 ZIP for review under Simply Analytics for Umami. The approved reply was sent in the existing review thread, requesting the slug `simply-analytics-for-umami` and confirming independent maintenance, GPL licensing, retained upstream authorship, and no Umami affiliation or endorsement. The assigned slug remains `simply-umami` until the reviewer changes it. Human review and repository access remain pending, so the SVN release gate remains disabled. This is a review submission, not a live public directory release.

The Compose migration passed all eleven tests on WordPress 6.8 with PHP 8.1 before the branding changes. The renamed production package passes Plugin Check with zero errors and two intentional script-version warnings.

Docker reached its disk limit during the renamed-package checks. Only this task's disposable legacy containers, volumes, and fetched legacy image were removed. The renamed package passes all eleven browser tests, including a separate rerun of the dashboard link test after removing a duplicate dashboard navigation. The banner, both settings screenshots, and the plugin settings action link were visually checked. The production ZIP passes archive integrity and source-content checks. The published GitHub ZIP was also checked against the tested source. The test containers are stopped.

[GitHub release 1.1.3](https://github.com/nerveband/simply-umami/releases/tag/v1.1.3) is published as Simply Analytics for Umami. Release CI passed all eleven browser tests, dependency audits, and PHP coding standards before packaging. The only remaining publication dependency is WordPress.org human approval and repository access.

## Writing changes

The plan names the affected dependencies, required checks, release conditions, and recovery steps. General assurances were replaced with checks that can be recorded and verified.
