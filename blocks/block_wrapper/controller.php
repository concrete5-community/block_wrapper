<?php

namespace Concrete\Package\BlockWrapper\Block\BlockWrapper;

use A3020\BlockWrapper\HtmlObject\Element;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Page\Page;
use Concrete\Core\View\View;
use ReflectionMethod;

class Controller extends BlockController
{
	protected $btTable = 'btBlockWrapper';
	protected $btInterfaceWidth = "450";
	protected $btInterfaceHeight = "500";
	protected $btWrapperClass = 'ccm-ui';
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btDefaultSet = 'basic';

	// Block Data
    protected $id;
    protected $classes;
    protected $attributes;

    public function getBlockTypeName()
    {
        return t('Block Wrapper');
    }

    public function getBlockTypeDescription()
    {
		return t('Wrap one or more blocks in a container element.');
	}

    public function view()
    {
        $this->set('isNormalMode', $this->isNormalMode());
        $this->set('element', $this->getElement());
    }
	
	public function add()
	{
	    $this->addEdit();
	}
	
	public function edit()
	{
	    $this->addEdit();
	}
	
	public function addEdit()
	{
	    $view = View::getInstance();
        $view->requireAsset('selectize');

        $this->set('selectedTemplate', $this->getSelectedTemplate());
	    $this->set('templateOptions', $this->getTemplateOptions());

	    $this->set('classesSelect', $this->getClasses());
	    $this->set('classesSelected', $this->getClassesSelected());
	}

    public function save($data)
    {
        // Concat classes array to a string
        $data['classes'] = is_array($data['classes']) ? implode(' ', $data['classes']) : '';

	    parent::save($data);

        if (isset($data['template'])) {
            /** @see \Concrete\Core\Block\Block::setCustomTemplate **/
            $this->block->setCustomTemplate($data['template']);
        }
	}

    public function getBlockTypeHelp()
    {
        return t('Block Wrapper allows you to wrap one or more blocks in a container.' .
            'The container can then be given classes or an identifier in order to style it, for example.'
        );
    }

    /**
     * Return the container element
     *
     * @return Element
     */
    private function getElement()
    {
        $element = new Element('div');

        $element->addAttribute('id', $this->id);
        $element->addAttribute('class', $this->classes);

        foreach (explode(' ', $this->attributes) as $attribute) {
            $element->addAttribute($attribute, true);
        }

        return $element;
    }

    /**
     * Return a list of template options for a <select> field
     *
     * @return array
     */
    private function getTemplateOptions()
    {
        // This is a manual list because the order matters
        $options = [
            'open.php' => t('Open'),
            'close.php' => t('Close'),
        ];

        // If there are more templates (e.g. the user created some)
        // they are appended to the default templates.
        foreach ($this->getTemplates() as $template) {
            if (!in_array($template, $options)) {
                $options[$template->getTemplateFileFilename()] = $template->getTemplateFileDisplayName();
            }
        }

        return $options;
    }

    /**
     * Return a list of available templates for this block
     *
     * @return \Concrete\Core\Filesystem\TemplateFile[]
     */
    protected function getTemplates()
    {
        // The block property can't be used, because it'll be `null` when adding a block.

        /** @var \Concrete\Core\Entity\Block\BlockType\BlockType $blockType */
        $blockType = BlockType::getByHandle('block_wrapper');

        try {
            $method = new ReflectionMethod(BlockType::class, 'getBlockTypeCustomTemplates');

            if ($method->getNumberOfRequiredParameters() === 0) {
                // Version 8.0.0 doesn't have a required parameter
                // BC bug introduced in https://github.com/concrete5/concrete5/commit/ecdf04115fba220c38740ae46bfd43bb02552d2e#diff-0f10d90dac2ee654feb0184af01d5edaR432
                return $blockType->getBlockTypeCustomTemplates();
            }

            if (is_object($this->block)) {
                return $blockType->getBlockTypeCustomTemplates($this->block);
            }
        } catch (\ReflectionException $e) { }

        return [];
    }

    /**
     * Return the selected template (file name)
     *
     * @return string
     */
    private function getSelectedTemplate()
    {
        $blockObject = $this->getBlockObject();
			
        if (is_object($blockObject)) {
			// A template has been selected before
            return $blockObject->getBlockFilename();
        }

        // The default template name
        return 'open.php';
    }

    /**
     * Return true if the wrapper should be operational
     *
     * @return bool
     */
    private function isNormalMode()
    {
        /** @var Page $currentPage */
        $currentPage = Page::getCurrentPage();

        if ($currentPage->isEditMode()) {
            return false;
        }

        if ($currentPage->isAdminArea()) {
            return false;
        }

        return true;
    }

    /**
     * Get a list of CSS classes for Selectize
     *
     * @return array
     */
    private function getClasses()
    {
        /** @var Page $currentPage */
        $currentPage = Page::getCurrentPage();

        /** @var \Concrete\Core\Page\Theme\Theme $pageTheme */
        $pageTheme = $currentPage->getCollectionThemeObject();

        $classes = [];

        // Each block type can have its own css classes.
        // Here all classes from all block types will be merged.
        foreach ($pageTheme->getThemeBlockClasses() as $blockClasses) {
            foreach (array_values($blockClasses) as $class) {
                $classes[$class] = $class;
            }
        }

        return array_unique($classes);
    }

    /**
     * Return array of classes that have been selected (for Selectize)
     *
     * @return array
     */
    private function getClassesSelected()
    {
        $classes = explode(' ', $this->classes);

        return array_combine($classes, $classes);
    }
}