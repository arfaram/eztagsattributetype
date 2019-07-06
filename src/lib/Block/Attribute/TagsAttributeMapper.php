<?php

declare(strict_types=1);

namespace EzSystems\TagsFormType\Block\Attribute;

use eZ\Publish\API\Repository\FieldTypeService;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Attribute\FormTypeMapper\AttributeFormTypeMapperInterface;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;
use EzSystems\TagsFormType\Form\Type\AttributeTagsType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class TagsAttributeMapper.
 */
class TagsAttributeMapper implements AttributeFormTypeMapperInterface
{
    /** @var \eZ\Publish\API\Repository\FieldTypeService */
    private $fieldTypeService;

    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    /**
     * TagsAttributeMapper constructor.
     * @param \eZ\Publish\API\Repository\FieldTypeService $fieldTypeService
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(
        FieldTypeService $fieldTypeService,
        RequestStack $requestStack
    ) {
        $this->fieldTypeService = $fieldTypeService;
        $this->requestStack = $requestStack;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     * @param \EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition $blockDefinition
     * @param \EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition $blockAttributeDefinition
     * @param array $constraints
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     * @throws \Exception
     */
    public function map(
        FormBuilderInterface $formBuilder,
        BlockDefinition $blockDefinition,
        BlockAttributeDefinition $blockAttributeDefinition,
        array $constraints = []
    ): FormBuilderInterface {
        return $formBuilder->create(
            'value',
            AttributeTagsType::class,
            [
                'tags_options' => $blockAttributeDefinition->getOptions() ?? '',
                'language_code' => $this->getPageBuilderLanguageCode(),
                'constraints' => $constraints,
            ]
        );
    }

    /**
     * @return |null
     */
    private function getPageBuilderLanguageCode()
    {
        $request = $this->requestStack->getCurrentRequest();
        $requestBlockConfiguration = $request->get('request_block_configuration');

        return $requestBlockConfiguration['language'] ?? null;
    }
}
