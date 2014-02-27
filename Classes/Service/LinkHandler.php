<?php

if (!defined( 'TYPO3_MODE' )) {
    die ( 'Access denied.' );
}

/**
 * @package HappyFeet
 * @subpackage Service_Test
 * @author Timo Fuchs <kevin.schu@aoe.com>
 */
class Tx_HappyFeet_Service_LinkHandler extends Tx_HappyFeet_Service_Abstract
{
    /**
     * @var string
     */
    const KEYWORD = 'happy_feet';

    /**
     * @param string $linktxt
     * @param array $typoLinkConfiguration TypoLink Configuration array
     * @param string $linkHandlerKeyword Define the identifier that an record is given
     * @param string $linkHandlerValue Table and uid of the requested record like "tx_aoefootnote_item:2"
     * @param string $linkParams Full link params like "footnote:tx_aoefootnote_item:2"
     * @param tslib_cObj $pObj
     * @return string
     */
    public function main($linktxt, $typoLinkConfiguration, $linkHandlerKeyword, $linkHandlerValue, $linkParams, $pObj)
    {
        if ($linkHandlerKeyword === self::KEYWORD) {
            $footnote = $this->getRenderingService()->renderFootnote( $this->getFootnoteId( $linkHandlerValue ) );
            return $linktxt . $footnote;
        }
        return $linktxt;
    }

    /**
     * @param $str
     * @return int
     */
    private function getFootnoteId($str)
    {
        $parts = explode( ':', $str );
        return (int) $parts[1];
    }

    /**
     * @return Tx_HappyFeet_Service_Rendering
     */
    private function getRenderingService()
    {
        return $this->getObjectManager()->get( 'Tx_HappyFeet_Service_Rendering' );
    }
}