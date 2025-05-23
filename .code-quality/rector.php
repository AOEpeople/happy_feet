<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\YieldDataProviderRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertEmptyNullableObjectToAssertInstanceofRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\UseSpecificWithMethodRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/../Classes',
        __DIR__ . '/../Resources',
        __DIR__ . '/../Tests',
        __DIR__ . '/../.code-quality',
    ])
    ->withPhpSets(
        true
    )
    ->withSets([
        SetList::CODE_QUALITY,
        SetList::STRICT_BOOLEANS,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::PRIVATIZATION,
        SetList::TYPE_DECLARATION,
        SetList::INSTANCEOF,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
    ])
    ->withSkip([
        TypedPropertyFromStrictSetUpRector::class,
        AddMethodCallBasedStrictParamTypeRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        YieldDataProviderRector::class,

        // @todo verify after codestyle update
        UseSpecificWithMethodRector::class,
        TypedPropertyFromCreateMockAssignRector::class,
        AssertEmptyNullableObjectToAssertInstanceofRector::class,
        NullToStrictStringFuncCallArgRector::class,
        ReadOnlyPropertyRector::class,
    ])
    ->withAutoloadPaths([__DIR__ . '/../Classes'])
    ->registerService(RemoveUnusedPrivatePropertyRector::class);
