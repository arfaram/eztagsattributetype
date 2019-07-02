
# EzSystemsTagsFormTypeBundle

This bundle allows you to add new attribute type `tags` to the Landingpage blocks. It is based on the [NetgenTags Bundle](https://github.com/netgen/TagsBundle) and it uses the same autocomplete functionality on the same way like the eztags FieldType.

You can read about the available attribute type in eZPlatform here: https://doc.ezplatform.com/en/latest/guide/extending_page/

## Requirement

- EzPlatform Enterprise 2.3+

## Installation 
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

<img src="doc/tags_attribute_type.png" />

In your Listner you will get the json string saved in the database table `ezpage_attributes`. below is an example how to access to the keywords

```
        $blockTagsValue = $blockValue->getAttribute('tags')->getValue();
        if ($blockTagsValue === null ) {
            $parameters['keywords'] = '';
            /** @var TwigRenderRequest $renderRequest */
            $renderRequest->setParameters($parameters);
            return;
        }

        $tagsValue = json_decode($blockTagsValue, true);
        $keywords = [];
        foreach ($tagsValue as $value){
            $mainLanguage = $value['main_language_code'];
            $keywords[] = $value['keywords'][$mainLanguage];
        }

        //DO your stuff here

        $parameters['keywords'] = $keywords;

```

##Todo

- Clean up the block configuration template as some values are hard coded. The Idea is to extend the block definition to add some parameters like rootTagId, showRootTag, locales etc.

- Handle tags translation in the AttributeTagsTransformer