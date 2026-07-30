# SFD (Search Filter Display)

A WordPress plugin to create frontend filters. Built around and requires [Pods](https://github.com/pods-framework/pods).

## Installation

1. Download the [latest release](https://github.com/jemauch/search-filter-display/releases/latest).
2. Upload the .zip file through the Add Plugin page in WordPress.
3. Activate the plugin.

## How to Use

### Shortcode

Use the shortcode `[sfd]` with the following shortcode attributes:

- `pod`: Slug of the pod you want to pull posts from. (REQUIRED)
- `grid`: Name of the Pods template to use for the grid view. (REQUIRED)
- `table`: Name of the Pods template to use for the table view. (REQUIRED)
- `display`: Default display. Choose between "grid" or "table". (OPTIONAL; set to "grid" by default)
- `cache`: Whether to use caching for filter results. Boolean. (OPTIONAL; set to "false" by default) (DISABLED IN 2.3.0)

#### Shortcode Example

`[sfd pod="archive_inventory" grid="Inventory Cards Template" table="Inventory Table Template" display="table" cache="true"]`

### Filter Config

The filter can only be displayed when a pod is configured inside [SFD_Config](classes\sfd-config.php).

To edit the config, add the pod slug to the config array. In the order that you want them to appear on the frontend, add inputs. There are four types of inputs you can use: taxonomy, year, conference, and checkbox. Checkbox requires its own array of options for the input. There is a config for the order that posts will display as well.

In the future, the config should be editable through a settings page in the admin dashboard.

### Caching

The cache option, if enabled, uses functions from Pods to save the HTML results to the wp_options table. These options are set to expire after 24 hours. However, if object cache is not enabled, install a plugin to automatically clear out expired options. Expired options will sit in the table and take up storage otherwise.