<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;

return Architecture::define()
    ->withPresets(Preset::PSR4(), Preset::CODEQUALITY(), Preset::YAGNI())
    ->layer('Exception', 'src/Exceptions/')
    ->layer('Helper', 'src/Helpers/')
    ->layer('Support', [
        'src/ErrorHandler.php',
        'src/Glob.php',
        'src/Preset.php',
    ])
    ->layer('Gitattributes', 'src/Gitattributes/')
    ->layer('Preset', 'src/Presets/')
    ->layer('Git', 'src/Git/')
    ->layer('Analyser', 'src/Analysers/')
    ->layer('Archive', [
        'src/Archive.php',
        'src/Archive/',
    ])
    ->layer('Command', 'src/Commands/')
    ->layer('Core', [
        'src/Analyser.php',
        'src/ImpactReport.php',
        'src/PhpConfigLoader.php',
        'src/Reformatter.php',
        'src/Tree.php',
    ])
    ->ruleset([
        'Exception'     => [],
        'Helper'        => [],
        'Support'       => [],
        'Gitattributes' => ['Exception'],
        'Preset'        => ['Exception', 'Support'],
        'Git'           => ['Exception', 'Helper'],
        'Archive'       => ['Exception', 'Helper'],
        'Analyser'      => ['+Gitattributes', '+Preset', 'Helper'],
        'Core'          => ['+Analyser', '+Archive'],
        'Command'       => ['+Core', '+Git'],
    ]);
