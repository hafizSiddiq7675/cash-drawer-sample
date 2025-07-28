# Cypress Test Suite

## Setup Instructions

### 1. Set Environment Variables

Copy the example environment file to `.env`:

```bash
cp example.env .env
```

and update `CYPRESS_BASE_URL` with you domain like `<your-domain>` e.g : `http://localhost/cash-drawer-sample`
```bash
CYPRESS_BASE_URL=http://localhost/cash-drawer-sample
```


### 2. Install Dependencies

Install the required dependencies:

```bash
npm install
```

### 3. Run Cypress Tests For Event (Add, Edit, Get, Delete, 404)

Run tests:

```bash
npx cypress run
```
