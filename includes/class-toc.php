<?php


class TOC
{


    function __construct()
    {

        add_filter('the_content', [$this, 'injectTOC']);
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

        $allowedPostType = is_array(get_option( 'toc_posts_field', [] )) ? (get_option( 'toc_posts_field', [] )) : [] ;

        // var_dump($allowedPostType);

        if(!in_array($currentPostType,$allowedPostType)){

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




        $tocHTML = '<ul class="simple__toc">';
        $tocHTML .= '<h3>Table Of Content</h3>';



        foreach ($result as $res) {

            $contentHtml = $res->textContent;

            $id = preg_replace('/ /', '-', strtolower($contentHtml));

            $res->setAttribute('id', $id);

            $tocHTML .= "<li><a href='#" . $id . "'>$contentHtml</a></li>";
        }
        $tocHTML .= '</ul>';



        $updatedContent = $document->saveHTML();


        return  $tocHTML . $updatedContent;
    }
}
