<?php
namespace AOE\HappyFeet\Typo3\Hook;

use AOE\HappyFeet\Service\AbstractService;
use AOE\HappyFeet\Service\RenderingService;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\Typolink\UnableToLinkException;

class LinkRenderer extends AbstractService{

    public function build(array &$linkDetails, string $linkText, string $target, array $conf): array
    {
        $footnoteHtml = $this->getRenderingService()->renderFootnotes(array($linkDetails['uid']));
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
        /** @var RenderingService $renderingService */
        $renderingService = $this->getObjectManager()->get(RenderingService::class);
        return $renderingService;
    }
}