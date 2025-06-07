#!/bin/bash

# Configuration
BASE_URL="http://localhost:8000/api"
TEST_USER_EMAIL="testuser_$(date +%s)@example.com"
TEST_PASSWORD="SecurePass123!"
ADMIN_EMAIL="admin@bookstore.com"
ADMIN_PASSWORD="password"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Helper functions
print_header() {
    echo -e "\n${YELLOW}=== $1 ===${NC}"
}

print_success() {
    echo -e "${GREEN}✓ Success: $1${NC}"
}

print_error() {
    echo -e "${RED}✗ Error: $1${NC}"
}

test_endpoint() {
    local description=$1
    local method=$2
    local endpoint=$3
    local data=$4
    local expected_status=$5
    local headers=$6

    print_header "$description"
    
    if [ -z "$headers" ]; then
        response=$(curl -s -X "$method" "$BASE_URL$endpoint" \
            -H "Content-Type: application/json" \
            -d "$data" -i)
    else
        response=$(curl -s -X "$method" "$BASE_URL$endpoint" \
            -H "Content-Type: application/json" \
            -H "$headers" \
            -d "$data" -i)
    fi

    # Extract HTTP status
    http_status=$(echo "$response" | grep HTTP | awk '{print $2}')
    body=$(echo "$response" | awk '/^{/,0')

    # Validate
    if [ "$http_status" -eq "$expected_status" ]; then
        print_success "Status $http_status | $method $endpoint"
        echo "Response: $body"
        return 0
    else
        print_error "Expected $expected_status but got $http_status | $method $endpoint"
        echo "Response: $body"
        return 1
    fi
}

# Clean up any existing test user
cleanup() {
    mysql -u root -D bookstore -e "DELETE FROM users WHERE email='$TEST_USER_EMAIL'" 2>/dev/null
}

# Main test sequence
main() {
    cleanup
    
    # 1. Test registration
    test_endpoint "Register new user" POST "/register" \
        "{\"email\":\"$TEST_USER_EMAIL\",\"password\":\"$TEST_PASSWORD\",\"first_name\":\"Test\",\"last_name\":\"User\"}" \
        201
    
    # Extract session cookie from registration
    SESSION_COOKIE=$(echo "$response" | grep -i 'Set-Cookie' | awk '{print $2}' | cut -d';' -f1)
    
    # 2. Test duplicate registration
    test_endpoint "Duplicate registration" POST "/register" \
        "{\"email\":\"$TEST_USER_EMAIL\",\"password\":\"$TEST_PASSWORD\",\"first_name\":\"Test\",\"last_name\":\"User\"}" \
        400
    
    # 3. Test login
    test_endpoint "User login" POST "/login" \
        "{\"email\":\"$TEST_USER_EMAIL\",\"password\":\"$TEST_PASSWORD\"}" \
        200
    
    # Get new session cookie from login
    SESSION_COOKIE=$(echo "$response" | grep -i 'Set-Cookie' | awk '{print $2}' | cut -d';' -f1)
    AUTH_HEADER="Cookie: $SESSION_COOKIE"
    
    # 4. Test profile access
    test_endpoint "Profile access" GET "/profile" "" 200 "$AUTH_HEADER"
    
    # 5. Test logout
    test_endpoint "Logout" POST "/logout" "" 200 "$AUTH_HEADER"
    
    # 6. Test profile access after logout
    test_endpoint "Profile access after logout" GET "/profile" "" 401 "$AUTH_HEADER"
    
    # 7. Test admin login
    test_endpoint "Admin login" POST "/login" \
        "{\"email\":\"$ADMIN_EMAIL\",\"password\":\"$ADMIN_PASSWORD\"}" \
        200
    
    ADMIN_SESSION=$(echo "$response" | grep -i 'Set-Cookie' | awk '{print $2}' | cut -d';' -f1)
    ADMIN_AUTH_HEADER="Cookie: $ADMIN_SESSION"
    
    # 8. Verify admin flag
    admin_response=$(curl -s -X GET "$BASE_URL/profile" \
        -H "Content-Type: application/json" \
        -H "$ADMIN_AUTH_HEADER")
    
    if echo "$admin_response" | grep -q '"is_admin":true'; then
        print_success "Admin flag verified"
    else
        print_error "Admin flag not set"
    fi
    
    # 9. Test invalid login
    test_endpoint "Invalid login" POST "/login" \
        "{\"email\":\"nonexistent@example.com\",\"password\":\"wrong\"}" \
        401
    
    cleanup
    echo -e "\n${YELLOW}=== Testing Complete ===${NC}"
}

# Run tests
main
