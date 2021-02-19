<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var boolean $isNormalMode */
/** @var \A3020\BlockWrapper\HtmlObject\Element $element */

if (!$isNormalMode) {
	echo '<div style="font-weight: bold;" class="block-wrapper block-wrapper-close">';
        echo t('Close %s', t('Block Wrapper'));
        echo ' <i class="fa fa-angle-right"></i>';
	echo '</div>';

	return;
}

echo $element->close();
