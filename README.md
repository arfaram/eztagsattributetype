
# Tags AttributeType for eZ Platform LandingPage blocks

This bundle allows you to add new attribute type `tags` to the Landingpage blocks. It is based on the [NetgenTags Bundle](https://github.com/netgen/TagsBundle) and it uses the same autocomplete functionality on the same way like the eztags FieldType.

You can read about the available attribute type in eZPlatform here: https://doc.ezplatform.com/en/latest/guide/extending_page/

## Requirement

- Ez Platform **Enterprise** 2.3+

## Installation 

```
composer require arfaram/eztagsattributetype
```

Add below to the AppKernel

```
new EzSystems\TagsFormTypeBundle\EzSystemsTagsFormTypeBundle(),

```

## Usage

Block configuration example:

```
blocks:
    myblockidentifier:
        #...
        attributes:
            tags:
                type: tags

```

You can also add some options:

```
                type: 'tags'
                options:
                    max_tags: 3 # max number of added tags 
                    max_results: 3  # max results in the drop-down list
                    subtree_limit: 513 # restrict access to a specific location in the keywords tree
                    hide_root_tag: true # hide root tag
                    edit_view: 'Default' # or 'Select'. ('Default' per default)
```

- Default View

<img src="doc/tags_attribute_type.png" />

- Select View

<img src="doc/tags_attribute_type_select_view.png" />

In your [Block Rendering Listener](https://doc.ezplatform.com/en/latest/guide/extending_page/#block-rendering-events) you will get the json value saved in the database table `ezpage_attributes`. below is an example how to access the data

```
$blockValue = $event->getBlockValue();
$tagsValue = json_decode($blockValue->getAttribute('tags')->getValue(), true);

```

## Example: Profiling Block (eZ Platform enterprise demo)

https://github.com/arfaram/ezprofilerblock
