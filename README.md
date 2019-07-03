# Introduction

Monahan Variables allows content editors to manage pieces of content
not tied to a particular node without having to set up blocks or go
into the Views UI. Example uses are text or configuration settings 
in the header or footer or text content that's included as part of a
template, such as how to display the author's name on a node.

The module uses a custom content entity for each group of variables and
bundles to control what fields are included in a group.

Inspired by [Low Variables](http://gotolow.com/addons/low-variables) for
ExpressionEngine.

# Requirements
No special requirements

# Configuration
1. Create new variable groups in Administration > Structure > Monahan Variable 
Groups. Add and configure fields just as you would on a content type.
2. Create a new instance of your group in Administration > Content > Monahan 
Variables. (To make a new set of editable fields, you must both configure the 
bundle **and** create a new content entity.)
3. Configure user permissions in Administration > Stucture > People > 
Permissions.
4. To translate content, enable the Language and Content Translation modules
and configure custom language settings for Monahan Variables in Configuration > 
Content language and translation. 

# Usage    
To display content on the front-end, you'll need to pull in the variable group
or individual field via a preprocess function. The `monahan_variables.manager` 
service contains three methods for doing this: `getVariables()`, which returns the 
full render array for a variable group, `getValue()`, which returns the value 
of a single field within the group, and `getAllValues()`, which returns the
string value of all fields in a variable group.

Examples:
```
/**
 * Implements hook_preprocess_page().
 */
function demo_preprocess_page(&$variables) {
  $global = \Drupal::service('monahan_variables.manager')
    ->getVariables('global');
  $variables['global'] = $global;
}
```
This adds a `global` render array to the page template that contains the 
field(s) in the Global variables group. These fields can then be rendered in the 
page.html.twig template with `{{ global }}`.

```
/**
 * Implements hook_preprocess_page().
 */
function demo_preprocess_page(&$variables) {
  $copyright = \Drupal::service('monahan_variables.manager')
    ->getValue('global', 'field_copyright');
  $variables['copyright'] = $copyright[0]['value'];
}
```
This adds a string value called `copyright` to the page template that contains
the value of just the copyright field within the Global variables group.

# Credits
Created by Kelli Monahan at [Blue State Digital](http://www.bluestatedigital.com).
