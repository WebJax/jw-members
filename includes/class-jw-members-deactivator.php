<?php

class JW_Members_Deactivator {
    public static function deactivate() {
        // Delete custom tables
        global $wpdb;
        $table_name = $wpdb->prefix . 'jw_members';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");

        // Remove custom roles
        remove_role('jw_members_admin');
    }
}
