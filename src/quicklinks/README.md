# Quicklinks

A simple component to manage and render a list of quick links in the UI. It offers web routes for viewing and editing the list and CLI commands for managing stored links.

## Routes

All routes are provided by methods annotated with `@web` in `Web.php` and are accessible under `/quicklinks/{method}`. Available routes and parameters:

- GET /quicklinks/show
  - params:
    - command (optional): one of edit | save | "" (empty) — controls which UI is rendered

- POST /quicklinks/add
  - params:
    - referer (string): full URL of the page to store; its path is used as the name by default

- POST /quicklinks/upd
  - params:
    - value (JSON array): list of updates; each entry like {"<id>": "<new name>"} with the array order representing position

- POST /quicklinks/sort
  - params:
    - id (JSON array): order information; items contain an "id" like "item_<id>"; order in array defines new positions

- POST /quicklinks/rem
  - params:
    - name (JSON array): selection payload from UI; contains objects with a "name" that equals the quicklink id to remove

Note: Parameters are passed via query string for GET and request body for POST, as used by the UI templates in `src/quicklinks/ui/*`. 

## CLI

This component also provides a CLI (CliHandler) for scripting and administration.

- Show available commands:
  php index.php /quicklinks/cli -help

- Example: set a quicklink with a name and optional position
  php index.php /quicklinks/cli set "https://example.com/path?x=1" "My Link" 1

- Example: list current quicklinks
  php index.php /quicklinks/cli list

- Example: remove a quicklink by id
  php index.php /quicklinks/cli rem 42
