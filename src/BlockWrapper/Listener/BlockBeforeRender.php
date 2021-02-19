<?php

namespace A3020\BlockWrapper\Listener;

use A3020\BlockWrapper\BlockCounter;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;

class BlockBeforeRender implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * Check if there is an even number of wrappers in the area
     *
     * This event is available since concrete5 8.4.0
     *
     * @param \Concrete\Core\Block\Events\BlockBeforeRender $event
     */
    public function handle(\Concrete\Core\Block\Events\BlockBeforeRender $event)
    {
        $block = $event->getBlock();
        if (!is_object($block)) {
            return;
        }

        if ($block->getBlockTypeHandle() !== 'block_wrapper') {
            return;
        }

        /** @var \Concrete\Core\Page\Page $page */
        $page = $block->getBlockCollectionObject();

        // Always show the blocks in edit mode
        if (!is_object($page) || $page->isEditMode()) {
            return;
        }

        /** @var BlockCounter $counter */
        $counter = $this->app->make(BlockCounter::class);
        $count = $counter->countInArea($block->getBlockAreaObject());

        // If there are wrappers missing, this block type won't be rendered
        if ($count['open'] !== $count['close']) {
            $event->preventRendering();
        }
    }
}
