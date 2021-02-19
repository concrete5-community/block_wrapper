<?php 

defined('C5_EXECUTE') or die('Access Denied.');

/** @var boolean $isNormalMode */
/** @var \A3020\BlockWrapper\HtmlObject\Element $element */

if (!$isNormalMode) {
    echo '<div style="font-weight: bold;" class="block-wrapper block-wrapper-open">';
        echo '<i class="fa fa-angle-left"></i> ';
        echo t('Open %s', t('Block Wrapper'));
    echo '</div>';

    return;
}

echo $element->open();
