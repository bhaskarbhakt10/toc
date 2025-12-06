<?php


class TOC
{

    private $collapsible = false;
    private $isEnabled, $selectedTag, $selectedClass, $currentPostType, $selectedPosition, $allowedPostType, $title, $minHeading;

    private $document = null;
    function __construct()
    {

        add_filter('the_content', [$this, 'injectTOC']);

        $isTocCollapsible = boolval(get_option('toc_collapsible_field', true));

        $this->isEnabled  = boolval(get_option('toc_enabled', true));

        $this->selectedTag  = get_option('toc_select_field', true);

        $this->selectedClass  = get_option('toc_class_field', true);


        $this->selectedPosition = get_option('toc_position_field', true);


        $this->allowedPostType = is_array(get_option('toc_posts_field', [])) ? (get_option('toc_posts_field', [])) : [];

        $this->title = get_option( 'toc_title_heading', true ) ;

        $this->minHeading = intval(get_option( 'toc_min_heading', true ))  ?? 0;

        $this->document = new DOMDocument();

        if ($isTocCollapsible) {

            $this->collapsible = true;
            add_action('wp_enqueue_scripts', [$this, 'collapsibleTOC']);
        }

        if (strcasecmp($this->selectedPosition, 'custom') === 0) {

            add_shortcode('toc', [$this, 'generateShortcode']);
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


        $currentPostType = get_post_type();



        if (!in_array($currentPostType, $this->allowedPostType)) {

            return $content;
        }


        $selectorQuery = $this->generateSelector();



        if (is_null($selectorQuery)) {


            return $content;
        }


        if (!$this->isEnabled) {
            return $content;
        }


        $tocHTML = $this->getParsedHTML($content, $selectorQuery);


        $updatedContent = $this->document->saveHTML();

        $returnContent = $updatedContent;

        if (strcasecmp($this->selectedPosition, 'before') === 0) {

            $returnContent =  $tocHTML . $updatedContent;
        }

        if (strcasecmp($this->selectedPosition, 'after') === 0) {

            $returnContent =  $updatedContent . $tocHTML;
        }

        return  $returnContent;
    }


    private function generateSelector()
    {
        $selectorQuery = null;

        if (!empty($this->selectedTag) && empty($this->selectedClass)) {
            $selectorQuery = '//' . $this->selectedTag . '';
        }

        if (empty($this->selectedTag) && !empty($this->selectedClass)) {
            $selectorQuery = '//*[contains(concat(" ", normalize-space(@class), " "), " ' . $this->selectedClass . ' ")]';
        }

        if (!empty($this->selectedTag) && !empty($this->selectedClass)) {
            $selectorQuery = '//' . $this->selectedTag . '[contains(concat(" ", normalize-space(@class), " "), " ' . $this->selectedClass . ' ")]';
        }

        return $selectorQuery;
    }

    private function getParsedHTML($content, $selectorQuery)
    {


        $this->document->loadHTML($content);

        $selector = new DOMXPath($this->document);

        $result = $selector->query($selectorQuery);

        

        if ($result->length <= 0 || $result->length <= $this->minHeading) {

            return $content;
        }




        $tocHTML = '<div class="simple__toc toc__element ' . (($this->collapsible ? 'toc__collapsible' : '')) . '" data-element="toc">';
        
        if(is_string($this->title) && !empty($this->title)){

            $tocHTML .= '<h3 class="toc__heading ' . (($this->collapsible ? 'toc__collapsibleHeading' : '')) . '">'.$this->title.'</h3>';
        }
        

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

        return $tocHTML;
    }

    function generateShortcode() {
        global $post;

        $selector = $this->generateSelector();

       return $this->getParsedHTML($post->post_content, $selector);
    }
}
