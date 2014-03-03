<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2014 AOE GmbH <dev@aoe.com>
 * All rights reserved
 *
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Render Footnotes for FCE
 *
 * @package HappyFeet
 * @subpackage Service
 * @author Bilal Arslan <bilal.arslan@aoe.com>
 */
class Tx_HappyFeet_Service_FCEFootnoteService extends Tx_HappyFeet_Service_Abstract
{
    /**
     * @var Tx_HappyFeet_Service_Rendering
     */
    private $footnoteRenderer;

    /**
     * @var tslib_cObj
     */
    public $cObj;

    /**
     *
     * @param string $content
     * @param array $conf optional (this will be automatically set, of this method is called via 'TYPOSCRIPT-userFunc')
     * @return string The wrapped index value
     * @throws UnexpectedValueException
     */
    public function renderItemList($content, $conf = array())
    {
        if (!array_key_exists( 'userFunc', $conf ) || !array_key_exists( 'field', $conf )) {
            return '';
        }
        $footnoteUids = $this->getCObj()->getCurrentVal();
        if (strlen( $footnoteUids ) < 1) {
            return '';
        }
        return $this->getRenderingService()->renderFootnotes( explode( ',', $footnoteUids ) );
    }

    /**
     * @param Tx_HappyFeet_Service_Rendering $footnoteRenderer
     */
    public function injectRenderingService(Tx_HappyFeet_Service_Rendering $footnoteRenderer)
    {
        $this->footnoteRenderer = $footnoteRenderer;
    }

    /**
     * @return tslib_cObj
     * @throws UnexpectedValueException
     */
    protected function getCObj()
    {
        if (!$this->cObj instanceof tslib_cObj) {
            throw new UnexpectedValueException( 'cObj was not set', 1393843943 );
        }
        return $this->cObj;
    }

    /**
     * @return Tx_HappyFeet_Service_Rendering
     */
    protected function getRenderingService()
    {
        if (!$this->footnoteRenderer instanceof Tx_HappyFeet_Service_Rendering) {
            $this->footnoteRenderer = $this->getObjectManager()->get( 'Tx_HappyFeet_Service_Rendering' );
        }
        return $this->footnoteRenderer;
    }
}