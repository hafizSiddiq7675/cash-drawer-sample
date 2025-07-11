<?php

function cash_drawer_register_routes() {
    register_rest_route('cash-drawer/v1', '/event', array(
        'methods'  => 'POST',
        'callback' => 'cash_drawer_log_event',
        'permission_callback' => '__return_true',
        'args' => array(
            'event_type' => array(
                'required' => true,
                'type'     => 'string',
            ),
        ),
    ));
}

function cash_drawer_log_event($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'cash_drawer_events';
    $event_type = sanitize_text_field($request->get_param('event_type'));

    $wpdb->insert($table, [
        'event_type' => $event_type,
        'created_at' => current_time('mysql')
    ]);

    return rest_ensure_response([
        'success' => true,
        'message' => 'Event logged',
    ]);
}
