<?php
namespace AOE\HappyFeet\Typo3\Hook;

use AOE\HappyFeet\Service\RenderingService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\Typolink\AbstractTypolinkBuilder;
use TYPO3\CMS\Frontend\Typolink\UnableToLinkException;

class LinkRenderer extends AbstractTypolinkBuilder
{
    /**
     * @inheritDoc
     * @throws UnableToLinkException
     */
    public function build(array &$linkDetails, string $linkText, string $target, array $conf): array
    {
        $footnoteHtml = $this->getRenderingService()->renderFootnotes([$linkDetails['uid']], $conf);
        // Trim HTML-code of footnotes - Otherwise some ugly problems can occur
        // (e.g. TYPO3 renders p-tags around the HTML-code)
        $linkTextWithFootnote = $linkText . trim($footnoteHtml);

        // nasty workaround so typolink stops putting a link together, there is a link already built
        throw new UnableToLinkException(
            '',
            1491130170,
            null,
            $linkTextWithFootnote
        );
    }

    /**
     * @return RenderingService
     */
    protected function getRenderingService()
    {
        return GeneralUtility::makeInstance(ObjectManager::class)->get(RenderingService::class);
    }
}
