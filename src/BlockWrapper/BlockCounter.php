<?php

namespace A3020\BlockWrapper;

use Concrete\Core\Area\Area;

class BlockCounter
{
    protected $areas = [];

    /**
     * Counts the number of open/close wrappers in an area
     *
     * @param Area $area
     *
     * @return array[
     *   'open' => int
     *   'close' => int
     * ]
     */
    public function countInArea(Area $area)
    {
        // Some caching mechanism (this class is a singleton btw)
        if (isset($this->areas[$area->getAreaHandle()])) {
            return $this->areas[$area->getAreaHandle()];
        }

        $openBlocks = 0;
        $closeBlocks = 0;

        // Loop through all blocks in this area.
        // If it's a block wrapper, we'll count the open and close blocks
        foreach ($area->getAreaBlocksArray() as $block) {
            /** @var \Concrete\Core\Block\Block $block */
            if ($block->getBlockTypeHandle() !== 'block_wrapper') {
                continue;
            }

            if (stripos($block->getBlockFilename(), 'close') !== false) {
                $closeBlocks++;
                continue;
            }

            $openBlocks++;
        }

        $this->areas[$area->getAreaHandle()] = [
            'open' => $openBlocks,
            'close' => $closeBlocks,
        ];

        return $this->areas[$area->getAreaHandle()];
    }
}
