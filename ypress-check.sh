#!/bin/bash

# Load environment variables from .env file
if [ -f .env ]; then
  export $(grep -v '^#' .env | xargs)
fi

# Check if CYPRESS_BASE_URL is empty
if [ -z "$CYPRESS_BASE_URL" ]; then
  echo "❌ Please add CYPRESS_BASE_URL to your .env file"
  exit 1
fi

# Run Cypress
npx cypress run
