<?php
/**
 * Plugin Name: Cash Drawer Sample Plugin
 * Description: A sample plugin that demonstrates custom table and REST API.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/installer.php';
require_once plugin_dir_path(__FILE__) . 'includes/rest-routes.php';

register_activation_hook(__FILE__, 'cash_drawer_create_table');
add_action('rest_api_init', 'cash_drawer_register_routes');
add_action('admin_menu', 'cash_drawer_admin_menu');




function cash_drawer_admin_menu() {
    add_menu_page(
        'POS - Cash Drawer',
        'Cash Drawer POS',
        'manage_options',
        'cash-drawer-pos',
        'cash_drawer_pos_page',
        'dashicons-cart',
        6
    );
}




function cash_drawer_pos_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cash_drawer_events';

    // Fetch all events
    $events = $wpdb->get_results("SELECT * FROM {$table_name} ORDER BY created_at DESC");
    ?>
    <div class="wrap">
        <h1>POS - Cash Drawer</h1>

        <div class="mb-3">
            <label for="tender-type" class="form-label">Select Tender Type</label>
            <select id="tender-type" class="form-select">
                <option value="cash">Cash</option>
                <option value="check">Check</option>
                <option value="card">Card</option>
            </select>
        </div>

        <button id="place-order-btn" class="btn btn-primary mb-4">PLACE ORDER</button>

        <!-- Drawer Events Table -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                Drawer Events Log
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event Type</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($events)): ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><?php echo esc_html($event->id); ?></td>
                                    <td><?php echo esc_html(ucfirst($event->event_type)); ?></td>
                                    <td><?php echo esc_html(date('Y-m-d H:i:s', strtotime($event->created_at))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No events logged yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="drawerConfirmModal" tabindex="-1" aria-labelledby="drawerConfirmLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow rounded">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="drawerConfirmLabel">Confirm Drawer Open</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Please confirm that you've opened the drawer using the key.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" id="confirmDrawerBtn">Confirm & Finish Sale</button>
          </div>
        </div>
      </div>
    </div>

    <script>
    jQuery(document).ready(function ($) {
        let originalClickHandler;
        let isDrawerModalOpen = false;

        $('#place-order-btn').on('click', function (e) {
            const tender = $('#tender-type').val();
            if (tender === 'cash' || tender === 'check') {
                e.preventDefault();

                if (!isDrawerModalOpen) {
                    $('#drawerConfirmModal').modal('show');
                    isDrawerModalOpen = true;
                    originalClickHandler = $(this);
                }
            }
        });

        $('#confirmDrawerBtn').on('click', function () {
            const tender = $('#tender-type').val();

            $.ajax({
                url: '/wordpress/wp-json/cash-drawer/v1/event',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ event_type: tender + '-sale' }),
                success: function () {
                    $('#drawerConfirmModal').modal('hide');
                    isDrawerModalOpen = false;

                    alert("Sale finished and drawer open logged.");
                    location.reload(); // Reload to show the new record
                },
                error: function () {
                    alert('Failed to log event.');
                }
            });
        });
    });
    </script>

    <!-- Load Bootstrap 5 from CDN (optional if Metronic already included) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php
}
