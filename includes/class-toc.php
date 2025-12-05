<?php


class TOC
{

    private $collapsible = false;

    function __construct()
    {

        add_filter('the_content', [$this, 'injectTOC']);

        $isTocCollapsible = boolval(get_option('toc_collapsible_field', true));
        if ($isTocCollapsible) {

            $this->collapsible = true;
            add_action('wp_enqueue_scripts', [$this, 'collapsibleTOC']);
        }
    }


    function collapsibleTOC()
    {
        wp_enqueue_style('TOC-collapsible', TOC_URL . 'assets/css/collapsible-toc.css', [], time(), 'all');

        wp_enqueue_script('TOC-collapsible', TOC_URL . 'assets/js/collapsible-toc.js',  [], time(), true);
    }

    function injectTOC($content)
    {

        if (is_admin()) {

            return $content;
        }


        $isEnabled  = boolval(get_option('toc_enabled', true));

        $selectedTag  = get_option('toc_select_field', true);

        $selectedClass  = get_option('toc_class_field', true);

        $currentPostType = get_post_type();

        $allowedPostType = is_array(get_option('toc_posts_field', [])) ? (get_option('toc_posts_field', [])) : [];

        // var_dump($allowedPostType);

        if (!in_array($currentPostType, $allowedPostType)) {

            return $content;
        }

        $selectorQuery = null;

        if (!empty($selectedTag) && empty($selectedClass)) {
            $selectorQuery = '//' . $selectedTag . '';
        }

        if (empty($selectedTag) && !empty($selectedClass)) {
            $selectorQuery = '//*[contains(concat(" ", normalize-space(@class), " "), " ' . $selectedClass . ' ")]';
        }

        if (!empty($selectedTag) && !empty($selectedClass)) {
            $selectorQuery = '//' . $selectedTag . '[contains(concat(" ", normalize-space(@class), " "), " ' . $selectedClass . ' ")]';
        }

        if (is_null($selectorQuery)) {

            return $content;
        }


        if (!$isEnabled) {
            return $content;
        }

        $document = new DOMDocument();

        $document->loadHTML($content);

        $selector = new DOMXPath($document);

        $result = $selector->query($selectorQuery);


        if ($result->length <= 0) {

            return $content;
        }




        $tocHTML = '<div class="simple__toc toc__element ' . (($this->collapsible ? 'toc__collapsible' : '')) . '" data-element="toc">';
        $tocHTML .= '<h3 class="toc__heading ' . (($this->collapsible ? 'toc__collapsibleHeading' : '')) . '">Table Of Content</h3>';

        if ($this->collapsible) {
            $tocHTML .= "<div class='toc__collapsibleWrapper collapsible'>";
        }

        foreach ($result as $res) {

            $contentHtml = $res->textContent;

            $id = preg_replace('/ /', '-', strtolower($contentHtml));

            $res->setAttribute('id', $id);

            $tocHTML .= "<div class='toc__item " . (($this->collapsible ? 'toc__collapsibleItem collapsible' : '')) . "'><a href='#" . $id . "'>$contentHtml</a></div>";
        }

        if ($this->collapsible) {

            $tocHTML .= '</div>';
        }

        $tocHTML .= '</div>';



        $updatedContent = $document->saveHTML();


        return  $tocHTML . $updatedContent;
    }
}
