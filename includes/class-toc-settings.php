<?php


class TOCSetting
{

    function __construct()
    {
        add_action('admin_menu', [$this, 'registerSettingsPage']);
        add_action('admin_init', [$this, 'registerTOCSettings']);

        add_action('admin_enqueue_scripts', [$this, 'adminScripts']);
    }

    function adminScripts($hook)
    {


        if ($hook !== 'toplevel_page_toc-setting') {
            return;
        }

        wp_enqueue_style('TOC-admin-settings', TOC_URL . 'assets/css/admin/admin.css', [], time(), 'all');

        wp_enqueue_script('TOC-admin-settings', TOC_URL . 'assets/js/admin/admin.js',  [], time(), true);
    }


    function registerSettingsPage()
    {

        add_menu_page('TOC Setting', 'TOC', 'manage_options', 'toc-setting', [$this, 'settingsPage_html'], 'dashicons-editor-table', 80);
    }

    function settingsPage_html()
    { ?>

        <div class='wrap'>
            <h1 class=''>TOC Settings</h1>

            <div class="tabs__wrapper">
                <div class="tabs__headings">
                    <ul class="toc__settings settings__tab">
                        <li class="setting__tabItem active"><a class="setting_nav" href="#general-settings">General Settings</a></li>
                        <li class="setting__tabItem"><a class="setting_nav" href="#appearance-settings">Appearance</a></li>
                        <li class="setting__tabItem"><a class="setting_nav" href="#behavioral-settings">Behavioral Settings</a></li>
                        <li class="setting__tabItem"><a class="setting_nav" href="#heading-settings">Heading Control</a></li>
                        <li class="setting__tabItem"><a class="setting_nav" href="#shortcode-settings">Shortcode Settings</a></li>
                        <li class="setting__tabItem"><a class="setting_nav" href="#advanced-settings">Advanced</a></li>
                    </ul>
                </div>
                <div class="tabs__content">
                    <div class="settings__content active" id="general-settings" class="">
                        <h2 class="setting__heading">
                            General Settings

                        </h2>
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('toc_settings_group');
                            do_settings_sections('toc-setting');
                            submit_button();
                            ?>
                        </form>
                    </div>
                    <div class="settings__content " id="appearance-settings">
                        <h2 class="setting__heading">
                            Appearance Settings

                        </h2>
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('toc_appearance_group');
                            do_settings_sections('toc-appearance');
                            submit_button();
                            ?>
                        </form>
                    </div>
                    <div class="settings__content" id="behavioral-settings">
                        <h2 class="setting__heading">
                            Behavioral Settings

                        </h2>
                    </div>
                    <div class="settings__content" id="heading-settings">
                        <h2 class="setting__heading">
                            Headings Settings

                        </h2>
                    </div>
                    <div class="settings__content" id="shortcode-settings">
                        <h2 class="setting__heading">
                            Shortcode Settings

                        </h2>
                    </div>
                    <div class="settings__content" id="advanced-settings">
                        <h2 class="setting__heading">
                            Advanced Settings
                        </h2>
                    </div>
                </div>
            </div>


        </div>


    <?php }

    function registerTOCSettings()
    {

        
        add_settings_section(
            'toc_general_section',
            '',
            '',
            'toc-setting'
        );

        add_settings_section(
            'toc_appearance_section',
            '',
            '',
            'toc-appearance'
        );


        register_setting(
            'toc_settings_group',
            'toc_enabled'
        );
       
        register_setting(
            'toc_settings_group',
            'toc_posts_field'
        );
        register_setting(
            'toc_settings_group',
            'toc_collapsible_field'
        );

        register_setting(
            'toc_settings_group',
            'toc_min_heading'
        );

        register_setting(
            'toc_settings_group',
            'toc_position_field'
        );

        register_setting(
            'toc_appearance_group',
            'toc_class_field'
        );
        register_setting(
            'toc_appearance_group',
            'toc_title_heading'
        );

        register_setting(
            'toc_appearance_group',
            'toc_select_field'
        );




        /***
         * 
         * 
         * general Settings
         * 
         */

        add_settings_field(
            'toc_enabled_field',
            'Enable TOC',
            [$this, 'checkboxHtml'],
            'toc-setting',
            'toc_general_section'
        );

         add_settings_field(
            'toc_position_field',
            'Choose Position',
            [$this, 'choosePosition'],
            'toc-setting',
            'toc_general_section'
        );
        add_settings_field(
            'toc_posts_field',
            'Select Posts',
            [$this, 'selectPostsTypes'],
            'toc-setting',
            'toc_general_section'
        );

        add_settings_field(
            'toc_min_heading',
            'Select min no of count',
            [$this, 'selectCount'],
            'toc-setting',
            'toc_general_section'
        );
    
        add_settings_field(
            'toc_collapsible_field',
            'Enable Collapsible TOC',
            [$this, 'collapsibleHtml'],
            'toc-setting',
            'toc_general_section'
        );

        /***
         * 
         * 
         * general Settings
         * 
         */



        /***
         * 
         * 
         * appearance settings
         * 
         */



        add_settings_field(
            'toc_select_field',
            'Select Tag',
            [$this, 'selectTag'],
            'toc-appearance',
            'toc_appearance_section'
        );

        add_settings_field(
            'toc_class_field',
            'Add Class',
            [$this, 'addClassToTag'],
            'toc-appearance',
            'toc_appearance_section'
        );

        add_settings_field(
            'toc_title_heading',
            'TOC Title',
            [$this, 'tocTitle'],
            'toc-appearance',
            'toc_appearance_section'
        );

        /***
         * 
         * 
         * appearance settings
         * 
         */

        
        

        

       
    }

    function selectCount(){
        $minHeading = intval(get_option( 'toc_min_heading', true ))  ?? 0;
        
        ?>
        <input value="<?php echo $minHeading;?>" type="number" name="toc_min_heading" id="toc_min_heading" class="regular-text" placeholder="No of min heading">
        <p class="description">No of min heading required on a page to show TOC.</p>
        <?php
    }

    function tocTitle(){
        $title = get_option( 'toc_title_heading', true );
        // var_dump($title);
        ?>
        <input value="<?php echo $title;?>" type="text" name="toc_title_heading" id="toc_title_heading" class="regular-text" placeholder="Table Of content">
        <p class="description">Heading for TOC.</p>
        <?php
    }

    function checkboxHtml()
    {
        $value = get_option('toc_enabled', true);
        // var_dump($value);
    ?>
        <input type="checkbox" name="toc_enabled" value="1" <?php checked(1, $value, true) ?> />
        <p class="description">Check to enable automatic Table of Contents insertion.</p>
    <?php }

    function selectTag()
    {
        $dataValue = get_option('toc_select_field', true);

        // selected( $selected:mixed, $current:mixed, $echo:boolean );

        $options = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

    ?>

        <select name="toc_select_field" id="toc_select_field">
            <option value="">None</option>
            <?php foreach ($options as $key => $value) { ?>
                <option <?php echo  $value === $dataValue ? 'selected' : '' ?> value="<?php echo $value; ?>"><?php echo strtoupper($value) ?></option>
            <?php } ?>

        </select>
        <p class="description">Select a tag which should be used in to create TOC</p>
    <?php }

    function addClassToTag()
    {
        $addedValue = get_option('toc_class_field', true);

    ?>

        <input type="text" class="regular-text" id="toc_class_field" name="toc_class_field" value="<?php echo $addedValue; ?>" placeholder="wp-block-heading">
        <p class="description">Add class to tagname. Skip using class selector use just classname <b>ex:</b>h1.wp-block-heading</p>

        <?php
    }

    function selectPostsTypes()
    {
        $post_types = get_post_types(
            array('public' => true),
            'names'
        );

        $checkedData = (get_option('toc_posts_field', []));
        foreach ($post_types as $key => $value) {
        ?>
            <div>
                <label for="type-<?php echo $value ?>">
                    <input type="checkbox" name="toc_posts_field[]" id="type-<?php echo $value ?>" value="<?php echo $value; ?>" <?php echo is_array($checkedData) && in_array($value, $checkedData) ? 'checked' : ''; ?>>
                    <?php echo ucfirst($value); ?>
                </label>
            </div>
        <?php
        }
        ?>


    <?php
    }

    function collapsibleHtml()
    {
        $collapsibleHTML = get_option('toc_collapsible_field', true);
        // var_dump($collapsibleHTML);
    ?>
        <input type="checkbox" name="toc_collapsible_field" id="" value="1" <?php checked(1, $collapsibleHTML, true) ?>>
        <p class="description">Check to make TOC Collapsible.</p>
    <?php
    }

    function choosePosition()
    {

        $selectedPosition = get_option('toc_position_field', true);

    ?>
        <select name="toc_position_field" id="">
            <option value="before" <?php echo (strcasecmp($selectedPosition, 'before') === 0) ? 'selected' : '' ?>>Before</option>
            <option value="after" <?php echo (strcasecmp($selectedPosition, 'after') === 0) ? 'selected' : '' ?>>After</option>
            <option value="custom" <?php echo (strcasecmp($selectedPosition, 'custom') === 0) ? 'selected' : '' ?>>Custom</option>
        </select>
        <p class="description">Choose Position To Display Toc
            <?php if (strcasecmp($selectedPosition, 'custom') === 0) { ?>
                please use [toc] shortcode to place manually on a page
            <?php } ?>
        </p>
<?php
    }
}
