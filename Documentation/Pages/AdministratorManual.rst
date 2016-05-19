.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================

Describes how to manage the extension from an administrator’s point of
view. That relates to Page/User TSconfig, permissions, configuration
etc., which administrator level users have access to.

Target group: **Administrators**


Installation
------------

You can install / download this extension from `[TER (TYPO3 Extension Repository)] <http://typo3.org/extensions/repository/view/happy_feet>`_.


Configuration
-------------

The rendering template is configurable via TypoScript Setup:

.. code-block:: ruby
   :linenos:

       lib.plugins.tx_happyfeet.view {
            template = EXT:<Extension Name>/Resources/Private/Templates/<Template Name>.html
        }

By default the happy feet template "/Resources/Private/Templates/Rendering/Markup.html" is selected.
