describe('Cash Drawer Plugin Full API Flow', () => {
  let eventId;

  it('creates a new event', () => {
    cy.request({
      method: 'POST',
      url: '/wp-json/cash-drawer/v1/event',
      body: { event_type: 'cash_open' },
      headers: { 'Content-Type': 'application/json' },
    }).then((response) => {
      expect(response.status).to.eq(200);
      expect(response.body.success).to.be.true;
      expect(response.body).to.have.property('id');
      eventId = response.body.id;
    });
  });

  it('retrieves the created event', () => {
    cy.request({
      method: 'GET',
      url: `/wp-json/cash-drawer/v1/event/${eventId}`,
    }).then((response) => {
      expect(response.status).to.eq(200);
      expect(response.body).to.have.property('id', eventId);
      expect(response.body).to.have.property('event_type', 'cash_open');
    });
  });

  it('updates the event', () => {
    cy.request({
      method: 'PUT',
      url: `/wp-json/cash-drawer/v1/event/${eventId}`,
      body: { event_type: 'cash_close' },
      headers: { 'Content-Type': 'application/json' },
    }).then((response) => {
      expect(response.status).to.eq(200);
      expect(response.body.success).to.be.true;
    });
  });

  it('deletes the event', () => {
    cy.request({
      method: 'DELETE',
      url: `/wp-json/cash-drawer/v1/event/${eventId}`,
    }).then((response) => {
      expect(response.status).to.eq(200);
      expect(response.body.success).to.be.true;
    });
  });

  it('returns 404 for deleted event', () => {
    cy.request({
      method: 'GET',
      url: `/wp-json/cash-drawer/v1/event/${eventId}`,
      failOnStatusCode: false,
    }).then((response) => {
      expect(response.status).to.eq(404);
    });
  });
});
