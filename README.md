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
* Create new variable groups in Administration > Structure > Monahan Variable 
Groups. Add and configure fields just as you would on a content type.
* Create a new instance of your group in Administration > Content > Monahan 
Variables. (To make a new set of editable fields, you must both configure the 
bundle *and* create a new entity.)
* Configure user permissions in Administration > Stucture > People > 
Permissions.

# Usage    
To display content on the front-end, you'll need to pull in the variable group
or individual field via a preprocess function. The `monahan_variables.manager` 
service contains two methods for doing this: getVariables(), which returns the 
full render array for a variable group, and getValue(), which returns the value 
of a single field within the group.

# Credits
Created by Kelli Monahan at [Blue State Digital](http://www.bluestatedigital.com).