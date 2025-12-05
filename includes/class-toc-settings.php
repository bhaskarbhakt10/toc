<?php


class TOCSetting
{

    function __construct()
    {
        add_action('admin_menu', [$this, 'registerSettingsPage']);
        add_action('admin_init', [$this, 'registerTOCSettings']);
    }


    function registerSettingsPage()
    {

        add_menu_page('TOC Setting', 'TOC', 'manage_options', 'toc-setting', [$this, 'settingsPage_html'], 'dashicons-editor-table', 80);
    }

    function settingsPage_html()
    { ?>

        <div class='wrap'>
            <h1 class='wp-heading-inline'>TOC Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('toc_settings_group');
                do_settings_sections('toc-setting');
                submit_button();
                ?>
            </form>
        </div>


    <?php }

    function registerTOCSettings()
    {

        register_setting(
            'toc_settings_group',
            'toc_enabled'
        );

        register_setting(
            'toc_settings_group',
            'toc_select_field'
        );
        register_setting(
            'toc_settings_group',
            'toc_class_field'
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
            'toc_position_field'
        );

        add_settings_section(
            'toc_main_section',
            'General Settings',
            '',
            'toc-setting'
        );



        add_settings_field(
            'toc_select_field',
            'Select Tag',
            [$this, 'selectTag'],
            'toc-setting',
            'toc_main_section'
        );

        add_settings_field(
            'toc_class_field',
            'Add Class',
            [$this, 'addClassToTag'],
            'toc-setting',
            'toc_main_section'
        );

        add_settings_field(
            'toc_posts_field',
            'Select Posts',
            [$this, 'selectPostsTypes'],
            'toc-setting',
            'toc_main_section'
        );
        add_settings_field(
            'toc_enabled_field',
            'Enable TOC',
            [$this, 'checkboxHtml'],
            'toc-setting',
            'toc_main_section'
        );

        add_settings_field(
            'toc_collapsible_field',
            'Enable Collapsible TOC',
            [$this, 'collapsibleHtml'],
            'toc-setting',
            'toc_main_section'
        );
        
        add_settings_field(
            'toc_position_field',
            'Choose Position',
            [$this, 'choosePosition'],
            'toc-setting',
            'toc_main_section'
        );
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
    
    function choosePosition(){
        
        $selectedPosition = get_option( 'toc_position_field', true );
        
        ?>
        <select name="toc_position_field" id="">
            <option value="before" <?php echo (strcasecmp($selectedPosition, 'before') === 0) ? 'selected' : ''?> >Before</option>
            <option value="after" <?php echo (strcasecmp($selectedPosition, 'after') === 0) ? 'selected' : ''?> >After</option>
        </select>
        <p class="description">Choose Position To Display Toc</p>
        <?php
    }
}
