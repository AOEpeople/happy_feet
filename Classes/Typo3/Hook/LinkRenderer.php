<?php
namespace AOE\HappyFeet\Typo3\Hook;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class LinkRenderer {

    public function build(){
        DebuggerUtility::var_dump(func_get_args());
        return 'Ich bin eine userFunc!';
    }
}