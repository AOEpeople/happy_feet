<?php
namespace AOE\HappyFeet\Typo3\Hook;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\Typolink\UnableToLinkException;

class LinkRenderer {

    public function build(array &$linkDetails, string $linkText, string $target, array $conf): array
    {
        //@TODO: implement stuff

        DebuggerUtility::var_dump(func_get_args(), __FILE__);
        // nasty workaround so typolink stops putting a link together, there is a link already built
        throw new UnableToLinkException(
            '',
            1491130170,
            null,
            "Ich bin ein Link!"
        );
    }
}