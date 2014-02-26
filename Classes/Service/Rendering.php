<?php

class Tx_HappyFeet_Service_Rendering extends Tx_HappyFeet_Service_Abstract
{
    /**
     * @var Tx_HappyFeet_Domain_Repository_FootnoteRepository
     */
    protected $footnoteRepository;

    /**
     * @var Tx_Fluid_View_StandaloneView
     */
    protected $view;

    /**
     * @param Tx_HappyFeet_Domain_Repository_FootnoteRepository $footnoteRepository
     * @return void
     */
    public function injectFootnoteRepository(Tx_HappyFeet_Domain_Repository_FootnoteRepository $footnoteRepository)
    {
        $this->footnoteRepository = $footnoteRepository;
    }

    /**
     * @param $uid
     * @return string
     */
    public function renderFootnote($uid)
    {
        $view = $this->createView();
        $view->assign( 'footnote', $this->footnoteRepository->getFootNoteById( $uid ) );
        return $view->render( 'Markup' );
    }

    /**
     * @return Tx_Fluid_View_StandaloneView
     */
    protected function createView()
    {
        if (null === $this->view) {
            /** @var Tx_Fluid_View_StandaloneView $view */
            $this->view = $this->getObjectManager()->create( 'Tx_Fluid_View_StandaloneView' );
            $this->view->setTemplatePathAndFilename( $this->getTemplatePathAndFilename() );
        }
        return $this->view;
    }

    /**
     * @return string
     */
    protected function getTemplatePathAndFilename()
    {
        return t3lib_extMgm::extPath(
            'happy_feet',
            'Resources' . DIRECTORY_SEPARATOR . 'Private' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'Rendering'
        );
    }

    /**
     * @return Tx_HappyFeet_Domain_Repository_FootnoteRepository
     */
    protected function getFootnoteRepository()
    {
        return $this->getObjectManager()->get( 'Tx_HappyFeet_Domain_Repository_FootnoteRepository' );
    }
}