<?php

class JW_Members_Activator {
    public static function activate() {
        // Create custom tables
        global $wpdb;
        $table_name = $wpdb->prefix . 'jw_members';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            membership_type_id varchar(50) NOT NULL,
            subscription_status varchar(50) NOT NULL,
            created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Add custom roles
        add_role(
            'jw_members_admin',
            __('JW Members Admin'),
            array(
                'read' => true,
                'edit_posts' => true,
                'delete_posts' => true,
                'manage_options' => true,
            )
        );
    }
}
