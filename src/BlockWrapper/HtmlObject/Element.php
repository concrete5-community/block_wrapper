<?php

namespace A3020\BlockWrapper\HtmlObject;

class Element
{
    /** @var string */
    protected $tag;

    /** @var array */
    protected $attributes = [];

    public function __construct($tag = 'div')
    {
        $this->setTag($tag);
    }

    /**
     * @return string
     */
    public function
    open()
    {
        return '<' . $this->tag . $this->renderAttributes() . '>';
    }

    /**
     * @return string
     */
    public function close()
    {
        return '</' . $this->tag . '>';
    }

    /**
     * @param string $name
     * @param string|null $value
     */
    public function addAttribute($name, $value = null)
    {
        $this->attributes[] = [
            'name' => $name,
            'value' => $value,
        ];
    }

    /**
     * @return string
     */
    private function renderAttributes()
    {
        $html = '';

        foreach ($this->attributes as $attribute) {
            if ($attribute['value'] === true) {
                $html .= ' ' . $attribute['name'];
            } elseif (!empty($attribute['value'])) {
                $html .= ' ' . $attribute['name'] . '="' . $attribute['value'] . '"';
            }
        }

        return $html;
    }

    /**
     * Override the default tag
     *
     * Examples: 'div', 'section', 'p'
     *
     * @param string $tag
     */
    private function setTag($tag)
    {
        $this->tag = $tag;
    }
}
