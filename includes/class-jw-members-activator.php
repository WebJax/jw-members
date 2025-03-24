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
            membership_level varchar(50) NOT NULL,
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

        // Collect previous purchases of specific products
        self::collect_previous_purchases();
    }

    private static function collect_previous_purchases() {
        global $wpdb;

        // Define the product IDs for membership fees
        $membership_product_ids = [123, 456, 789]; // Replace with actual product IDs

        // Query WooCommerce orders for these products
        $args = array(
            'limit' => -1,
            'status' => 'completed',
            'meta_query' => array(
                array(
                    'key' => '_product_id',
                    'value' => $membership_product_ids,
                    'compare' => 'IN'
                )
            )
        );

        $orders = wc_get_orders($args);

        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                if (in_array($item->get_product_id(), $membership_product_ids)) {
                    $user_id = $order->get_user_id();
                    $membership_level = 'default'; // Set appropriate membership level
                    $subscription_status = 'active'; // Set appropriate subscription status
                    $created_at = $order->get_date_created()->date('Y-m-d H:i:s');

                    // Insert data into custom table
                    $wpdb->insert(
                        $wpdb->prefix . 'jw_members',
                        array(
                            'user_id' => $user_id,
                            'membership_level' => $membership_level,
                            'subscription_status' => $subscription_status,
                            'created_at' => $created_at
                        )
                    );
                }
            }
        }
    }
}
