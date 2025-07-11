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

    // Get Event By ID
    register_rest_route('cash-drawer/v1', '/event/(?P<id>\d+)', array(
        'methods'  => 'GET',
        'callback' => 'cash_drawer_get_event',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'required' => true,
                'type' => 'integer',
            ),
        ),
    ));


    register_rest_route('cash-drawer/v1', '/event/(?P<id>\d+)', array(
        'methods'  => 'PUT',
        'callback' => 'cash_drawer_update_event',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'required' => true,
                'type' => 'integer',
            ),
            'event_type' => array(
                'required' => true,
                'type' => 'string',
            ),
        ),
    ));


    register_rest_route('cash-drawer/v1', '/event/(?P<id>\d+)', array(
        'methods'  => 'DELETE',
        'callback' => 'cash_drawer_delete_event',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'required' => true,
                'type' => 'integer',
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


function cash_drawer_get_event($request) {

    global $wpdb;
    $table = $wpdb->prefix . 'cash_drawer_events';
    $id = intval($request['id']);

    $event = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));

    if (!$event) {
        return new WP_Error('not_found', 'Event not found', ['status' => 404]);
    }

    return rest_ensure_response([
        'id'         => $event->id,
        'event_type' => $event->event_type,
        'created_at' => $event->created_at
    ]);
}


function cash_drawer_update_event($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'cash_drawer_events';
    $id = intval($request['id']);
    $event_type = sanitize_text_field($request->get_param('event_type'));

    $updated = $wpdb->update(
        $table,
        ['event_type' => $event_type],
        ['id' => $id],
        ['%s'],
        ['%d']
    );

    if ($updated === false) {
        return new WP_Error('db_update_error', 'Failed to update event.', array('status' => 500));
    }

    return rest_ensure_response([
        'success' => true,
        'message' => 'Event updated successfully',
    ]);
}


function cash_drawer_delete_event($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'cash_drawer_events';
    $id = intval($request['id']);

    $deleted = $wpdb->delete($table, ['id' => $id], ['%d']);

    if ($deleted === false) {
        return new WP_Error('db_delete_error', 'Failed to delete event.', array('status' => 500));
    }

    return rest_ensure_response([
        'success' => true,
        'message' => 'Event deleted successfully',
    ]);
}
