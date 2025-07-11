<?php

class Test_Cash_Drawer_Route extends WP_UnitTestCase {

    public function test_post_event_route() {
        $request = new WP_REST_Request('POST', '/cash-drawer/v1/event');
        $request->set_param('event_type', 'no-sale');
        $response = rest_do_request($request);
        $data = $response->get_data();

        $this->assertTrue($data['success']);
        $this->assertEquals('Event logged', $data['message']);
    }
}
