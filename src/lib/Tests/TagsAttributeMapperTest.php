<?php

declare(strict_types=1);

namespace EzSystems\TagsFormType\Tests;

use eZ\Publish\API\Repository\FieldTypeService;
use EzSystems\TagsFormType\Block\Attribute\TagsAttributeMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TagsAttributeMapperTest extends TestCase
{
    /** @var TagsAttributeMapper */
    public $mapper;

    /** @var FieldTypeService */
    public $fieldTypeService;

    /** @var RequestStack */
    public $requestStack;

    public function setUp()
    {
        $this->requestStack = new RequestStack();
        $this->fieldTypeService = $this->createMock(FieldTypeService::class);
        $this->mapper = new TagsAttributeMapper($this->fieldTypeService, $this->requestStack);
    }

    public function tearDown()
    {
        unset($this->requestStack);
    }

    /**
     * @dataProvider provideGetParameters
     *
     * @param $requestParameters
     * @param $langCode
     * @throws \ReflectionException
     */
    public function testGetParameters($requestParameters, $langCode)
    {
        //$this->assertNull($this->requestStack->getCurrentRequest());

        $request = Request::create('/foo', null, $requestParameters);
        $this->requestStack->push($request);

        $method = self::getMethodFromReflectionMethod('getPageBuilderLanguageCode');

        $this->assertSame($langCode, $method->invoke($this->mapper));
    }

    /**
     * @dataProvider provideLanguageCode
     *
     * @param $langCode
     * @throws \ReflectionException
     */
    public function testLanguageCode($langCode)
    {
        $request = Request::create('/foo', null, ['request_block_configuration' => ['language' => $langCode]]);
        $this->requestStack->push($request);

        $method = self::getMethodFromReflectionMethod('getPageBuilderLanguageCode');

        $this->assertSame($langCode, $method->invoke($this->mapper));
    }

    /**
     * @return array
     */
    public function provideGetParameters(): array
    {
        return [
            'langcode_exist' => [
                ['request_block_configuration' => [
                    'language' => 'ger-DE',
                    ],
                ],
                'ger-DE',
            ],
            'langcode_is_not_set' => [
                ['request_block_configuration' => [
                    'language' => null,
                    ],
                ],
                null,
            ],
            'param_not_exit' => [
                ['request_block_configuration1' => [
                    'language' => 'ger-DE',
                    ],
                ],
                null,
            ],
            'all_param_not_exits' => [
                ['request_block_configuration1' => [
                    'language1' => 'ger-DE',
                    ],
                ],
                null,
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideLanguageCode(): array
    {
        return [
            ['eng-GB'],
            ['ger-DE'],
            ['fre-FR'],
            [''],
        ];
    }

    /**
     * @param $name
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    protected static function getMethodFromReflectionMethod($name)
    {
        $method = new \ReflectionMethod(TagsAttributeMapper::class, $name);
        $method->setAccessible(true);

        return $method;
    }
}
