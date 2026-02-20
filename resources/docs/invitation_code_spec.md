# Invitation Code System - Implementation Specification for Claude Code

## Overview

Implement an optional invitation code system for user registration in a Laravel application. When enabled, users must provide a valid invitation code to complete registration.

---

## 1. Database Schema

### Create Migration: `create_invitation_codes_table`

**Table Name:** `invitation_codes`

**Columns:**

- `id` - Primary key (bigint, auto-increment)
- `code` - String, unique, not nullable (stores the invitation code)
- `active` - Boolean, default true (whether code can be used)
- `used_at` - Timestamp, nullable (when code was used)
- `used_by` - Foreign key to users table, nullable (which user used the code)
- `description` - Text, nullable (optional note about code purpose)
- `timestamps` - created_at, updated_at

**Indexes:**

- Unique index on `code`
- Composite index on `(code, active)` for performance on lookup queries
- Foreign key constraint: `used_by` references `users(id)`

**Migration Example Structure:**

```php
Schema::create('invitation_codes', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->boolean('active')->default(true);
    $table->timestamp('used_at')->nullable();
    $table->foreignId('used_by')->nullable()->constrained('users');
    $table->text('description')->nullable();
    $table->timestamps();

    $table->index(['code', 'active']);
});
```

---

## 2. Model

### Create Model: `InvitationCode`

**Fillable Fields:**

- `code`
- `active`
- `used_at`
- `used_by`
- `description`

**Casts:**

- `active` → boolean
- `used_at` → datetime
- `used_by` → integer

**Relationships:**

- None explicitly needed, but can add `belongsTo(User::class, 'used_by')` if desired

---

## 3. Configuration

### Add to Application Config

**File:** `config/[app-name].php` or similar config file

**Key:** `require_invitation`

**Environment Variable:** `[APP_PREFIX]_REQUIRE_INVITATION`

**Default:** `false`

**Example:**

```php
return [
    'require_invitation' => env('APP_PREFIX_REQUIRE_INVITATION', false),
];
```

---

## 4. Controllers

### 4.1 Locate Existing Registration Controller

**Find the controller handling user registration** - typically something like:

- `RegistrationController`
- `RegisterController`
- `Auth\RegisterController`

### 4.2 Create New Controller: InvitedUserRegistrationController

**Extends:** The existing registration controller

**Purpose:** Add invitation code validation to registration flow

**Key Method:** `store(Request $request)`

**Logic Flow:**

```
1. Check if invitation requirement is enabled via config
2. If NOT enabled → call parent::store() and return
3. If enabled → call storeWithInvitation()
```

**Method:** `storeWithInvitation(Request $request)`

**Implementation Requirements:**

1. **Wrap in Database Transaction**
    - Use `DB::transaction()`
    - Ensures atomicity of code check and user creation

2. **Validate Invitation Code**
    - Required field
    - String type
    - Length: 11 characters (including dashes)

3. **Normalize Code**
    - Convert to uppercase: `strtoupper($code)`

4. **Query with Row Lock**

    ```php
    InvitationCode::where('code', $normalizedCode)
        ->where('active', true)
        ->lockForUpdate()
        ->first()
    ```

5. **Validation Checks (in order):**
    - If code not found → Validation error: "Invitation code not found."
    - If code already used (used_at is not null) → Validation error: "Invitation code already used."

6. **Create User**
    - Call `parent::store($request)` to create user

7. **Mark Code as Used**

    ```php
    $invitation->used_at = now();
    $invitation->used_by = Auth::id();
    $invitation->save();
    ```

8. **Return Response**
    - Return the response from parent::store()

**Error Handling:**

- Use `ValidationException::withMessages()` for validation errors
- All validation errors should be on the `invitation_code` field

---

## 5. Routes

### Update Authentication Routes

**Find registration routes** - typically in:

- `routes/web.php`
- `routes/auth.php`

**Change registration controller reference:**

- FROM: `[OriginalRegistrationController]::class`
- TO: `[InvitedUserRegistrationController]::class`

**Routes to Update:**

```php
Route::get('register', [InvitedUserRegistrationController::class, 'create']);
Route::post('register', [InvitedUserRegistrationController::class, 'store']);
```

---

## 6. Registration Form View

### Locate Registration View

**Typical locations:**

- `resources/views/auth/register.blade.php`
- `resources/views/register.blade.php`

### Add Invitation Code Input Field

**Placement:** Before or after name/email fields (preferably first)

**Conditional Rendering:**

```blade
@if(config('[app-name].require_invitation', false))
    {{-- Invitation Code Input --}}
@endif
```

**Input Field Requirements:**

- **Type:** text
- **Name:** `invitation_code`
- **ID:** `invitation_code`
- **Label:** "Invitation Code" or localized equivalent
- **Required:** yes (when visible)
- **Maxlength:** 11
- **Pattern:** `[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}` (HTML5 validation)
- **Placeholder:** "ABC-DEF-GHJ" or "XXX-XXX-XXX"
- **Style:** `text-transform: uppercase;` (CSS for auto-uppercase)
- **Value:** `{{ old('invitation_code') }}`
- **Error Display:** Show validation errors for `invitation_code` field
- **Help Text:** "Enter the invitation code you received (format: XXX-XXX-XXX)"

**Styling Notes:**

- Match existing form input styling
- Add error state styling when `@error('invitation_code')` exists
- Ensure responsive design

---

## 7. CLI Management Command

### Create Artisan Command: `invitation:manage`

**Command Signature:**

```
invitation:manage
    {action : The action to perform (create, list, deactivate)}
    {--code= : The invitation code (for deactivate action)}
    {--description= : Optional description for the invitation code}
    {--count=1 : Number of codes to generate (for create action)}
```

**Description:** "Create, list and deactivate invitation codes"

### 7.1 Action: `create`

**Purpose:** Generate new invitation codes

**Options:**

- `--count` (default: 1, min: 1, max: 100)
- `--description` (optional)

**Process:**

1. Validate count is between 1-100
2. Loop for count times:
    - Generate unique code using `generateCode()` method
    - Create invitation code record
    - Store generated code in array
3. Display success message with all generated codes

**Output Format:**

```
Generating X invitation code(s)...

✓ Successfully created invitation code(s):
  • ABC-DEF-GHJ
  • KLM-NPR-STU
```

### 7.2 Action: `list`

**Purpose:** Display all invitation codes

**Process:**

1. Fetch all invitation codes ordered by created_at DESC
2. Display in table format

**Table Columns:**

- ID
- Code
- Active (✓ or ✗)
- Used At (format: Y-m-d H:i or '-')
- Used By (user ID or '-')
- Description (truncate to 30 chars or '-')
- Created (format: Y-m-d H:i)

**Summary Statistics:**

- Total count
- Active & Unused count
- Used count

### 7.3 Action: `deactivate`

**Purpose:** Deactivate a specific invitation code

**Required Option:** `--code`

**Process:**

1. Validate --code option is provided
2. Find invitation code by code value
3. Check if exists, if not → error message
4. Check if already inactive → warning message, exit success
5. If already used → show warning message (but proceed)
6. Set active = false and save
7. Display success message

**Output Examples:**

```
Error: Invitation code 'XXX-XXX-XXX' not found.
Warning: Invitation code 'XXX-XXX-XXX' is already deactivated.
Warning: This code has already been used.
✓ Successfully deactivated invitation code: XXX-XXX-XXX
```

### 7.4 Code Generation Logic

**Method:** `generateCode()`

**Format:** `XXX-XXX-XXX` (3 segments of 3 characters, separated by dashes)

**Character Set:** `ABCDEFGHJKLMNPQRSTUVWXYZ23456789`

- **Excludes:** 0 (zero), O (letter O), I (letter I), 1 (one)
- **Reason:** Prevents confusion when reading/typing codes

**Process:**

1. Generate 3 segments using `generateSegment()`
2. Join with dashes: `segment1-segment2-segment3`
3. Check uniqueness in database
4. If exists, regenerate (loop until unique)
5. Return code

**Method:** `generateSegment()`

**Process:**

1. Create empty string
2. Loop 3 times
3. Pick random character from character set
4. Append to string
5. Return 3-character segment

---

## 8. Tests

### Test File Location

Create test in appropriate location:

- `tests/Feature/Auth/InvitedUserRegistrationTest.php`
- `tests/Feature/InvitationCodeTest.php`

### Test Setup

**Before Each Test:**

```php
beforeEach(function () {
    config(['[app-name].require_invitation' => true]);
});
```

### Required Test Cases

#### Test 1: Registration screen shows invitation code input when enabled

- **Arrange:** Config set to require invitation
- **Act:** GET /register
- **Assert:**
    - Status 200
    - Page contains "Invitation Code" text
    - Page contains invitation_code input field

#### Test 2: Successful registration with valid invitation code

- **Arrange:** Create active invitation code in database
- **Act:** POST /register with valid code and user data
- **Assert:**
    - User is authenticated
    - Redirected to dashboard/home
    - Invitation code marked as used (used_at filled)
    - Invitation code used_by set to new user's ID

#### Test 3: Registration fails without invitation code

- **Arrange:** Config requires invitation
- **Act:** POST /register without invitation_code field
- **Assert:**
    - Session has errors for 'invitation_code'
    - User is guest (not authenticated)
    - No user created in database

#### Test 4: Registration fails with invalid invitation code

- **Arrange:** Config requires invitation
- **Act:** POST /register with non-existent code
- **Assert:**
    - Session error: "Invitation code not found."
    - User is guest
    - No user created

#### Test 5: Registration fails with inactive invitation code

- **Arrange:** Create invitation code with active = false
- **Act:** POST /register with inactive code
- **Assert:**
    - Session error: "Invitation code not found."
    - User is guest

#### Test 6: Registration fails with already used invitation code

- **Arrange:**
    - Create existing user
    - Create invitation code with used_at = now(), used_by = existing user
- **Act:** POST /register with used code
- **Assert:**
    - Session error: "Invitation code already used."
    - New user not created

#### Test 7: Race condition protection

- **Purpose:** Verify lockForUpdate prevents concurrent usage
- **Arrange:** Create active invitation code
- **Act:**
    - First registration with code → should succeed
    - Second registration with same code → should fail
- **Assert:**
    - First user created and authenticated
    - Second request fails with "already used" error
    - Only one user created

#### Test 8: Case sensitivity verification

- **Arrange:** Create code "ABC-DEF-GHJ"
- **Act:** POST with lowercase "abc-def-ghj"
- **Assert:** Should fail (unless case-insensitive logic implemented)

#### Test 9: Registration works without invitation when disabled

- **Arrange:** Config set to NOT require invitation
- **Act:** POST /register without invitation_code
- **Assert:** User created successfully

---

## 9. Implementation Checklist

### Phase 1: Database & Model

- [ ] Create migration for invitation_codes table
- [ ] Run migration
- [ ] Create InvitationCode model with fillable and casts

### Phase 2: Configuration

- [ ] Add config key for require_invitation
- [ ] Add environment variable to .env and .env.example
- [ ] Test config reading

### Phase 3: Backend Logic

- [ ] Identify existing registration controller
- [ ] Create InvitedUserRegistrationController extending existing
- [ ] Implement store() method with config check
- [ ] Implement storeWithInvitation() with:
    - Transaction wrapping
    - Validation
    - Code normalization
    - Row locking query
    - Validation checks
    - User creation
    - Code marking as used
- [ ] Update routes to use new controller

### Phase 4: Frontend

- [ ] Locate registration view
- [ ] Add conditional invitation code input field
- [ ] Match existing form styling
- [ ] Add client-side validation (pattern, maxlength)
- [ ] Test responsive design
- [ ] Test error display

### Phase 5: CLI Command

- [ ] Create invitation:manage command
- [ ] Implement create action with code generation
- [ ] Implement list action with table display
- [ ] Implement deactivate action
- [ ] Test all command actions
- [ ] Verify generated code uniqueness

### Phase 6: Testing

- [ ] Create test file
- [ ] Implement all 9 required test cases
- [ ] Run tests and verify all pass
- [ ] Test edge cases (empty strings, SQL injection attempts, etc.)

### Phase 7: Documentation & Deployment

- [ ] Update README with feature documentation
- [ ] Create user guide for managing codes
- [ ] Add to deployment checklist
- [ ] Test in staging environment
- [ ] Deploy to production

---

## 10. Adaptation Notes for Different Projects

### Project Structure Variations

**If using different auth scaffolding:**

- **Breeze:** Controllers in `App\Http\Controllers\Auth\`
- **Jetstream:** May use Fortify actions instead of controllers
- **Custom:** Locate wherever registration is handled

**If using Fortify/Jetstream:**

- Create custom action class instead of controller
- Register in service provider
- Implement `Fortify::createUsersUsing()`

**If views use different template engine:**

- Adapt Blade syntax to project's view system
- Maintain same conditional logic

**If using different CSS framework:**

- Adapt form styling to match (Bootstrap, Material, etc.)
- Keep same form structure and validation

### Naming Conventions

**Adapt these to match project:**

- Config file name (`shopnet.php` → `[your-app].php`)
- Config prefix (`SHOPNET_` → `[YOUR_APP_]`)
- Route names (`dashboard` → your project's home route)
- User model location (`App\Models\User` → actual location)

### Database Considerations

**If using different database:**

- PostgreSQL: Same structure works
- MySQL: Ensure proper character set for code column
- SQLite: Testing only, works fine

**If users table has different name:**

- Update foreign key: `constrained('users')` → `constrained('[your_table]')`

---

## 11. Security Considerations

### Implemented Security Measures

1. **Race Condition Protection**
    - `lockForUpdate()` prevents concurrent code usage
    - Transaction ensures atomicity

2. **Input Validation**
    - Server-side validation of code format
    - Length restrictions
    - Type checking

3. **Active Status Check**
    - Prevents use of deactivated codes
    - Allows administrator control

4. **One-Time Use Enforcement**
    - Checks `used_at` field
    - Prevents code reuse

5. **Audit Trail**
    - Tracks `used_at` timestamp
    - Tracks `used_by` user ID
    - Maintains code history

### Additional Security Recommendations

1. **Rate Limiting**

    ```php
    Route::post('register', [...])
        ->middleware('throttle:5,1');
    ```

2. **Logging**
    - Log code generation
    - Log code usage attempts
    - Log failed validations

3. **Code Expiration** (optional enhancement)
    - Add `expires_at` column
    - Check expiration in validation

---

## 12. Testing Guide

### Manual Testing Steps

1. **Enable Feature**

    ```bash
    # In .env
    APP_PREFIX_REQUIRE_INVITATION=true
    ```

2. **Generate Codes**

    ```bash
    php artisan invitation:manage create --count=5 --description="Test batch"
    ```

3. **List Codes**

    ```bash
    php artisan invitation:manage list
    ```

4. **Test Registration**
    - Visit /register
    - Verify invitation code field appears
    - Try registering without code → should fail
    - Copy a generated code
    - Register with valid code → should succeed
    - Try same code again → should fail

5. **Deactivate Code**

    ```bash
    php artisan invitation:manage deactivate --code=XXX-XXX-XXX
    ```

6. **Disable Feature**

    ```bash
    # In .env
    APP_PREFIX_REQUIRE_INVITATION=false
    ```

    - Registration should work without code

### Automated Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/Auth/InvitedUserRegistrationTest.php

# Run with coverage
php artisan test --coverage
```

---

## 13. Common Issues & Solutions

### Issue: Codes not appearing in form

- **Check:** Config value is true
- **Check:** Cache cleared: `php artisan config:clear`
- **Check:** View cache cleared: `php artisan view:clear`

### Issue: "Invitation code not found" for valid code

- **Check:** Code is uppercase in database
- **Check:** Code normalization in controller
- **Check:** Active status is true
- **Check:** Database connection working

### Issue: Multiple users can use same code

- **Check:** Transaction is wrapping the logic
- **Check:** `lockForUpdate()` is being called
- **Check:** Database supports row locking (InnoDB for MySQL)

### Issue: Command generates duplicate codes

- **Check:** Uniqueness check in `generateCode()`
- **Check:** Database unique constraint on code column
- **Check:** No race condition in code generation

---

## 14. Success Criteria

Implementation is complete when:

- ✅ Migration creates table with all columns and indexes
- ✅ Model created with proper fillable and casts
- ✅ Config added and environment variable works
- ✅ Registration requires code when enabled
- ✅ Registration works without code when disabled
- ✅ Form shows/hides input based on config
- ✅ Valid codes allow registration
- ✅ Invalid codes prevent registration with clear errors
- ✅ Used codes cannot be reused
- ✅ CLI command can create codes
- ✅ CLI command can list codes
- ✅ CLI command can deactivate codes
- ✅ All 9 test cases pass
- ✅ No race conditions (tested with concurrent requests)
- ✅ Code is properly documented

---

## 15. File Locations Summary

**Files to Create:**

- `database/migrations/YYYY_MM_DD_HHMMSS_create_invitation_codes_table.php`
- `app/Models/InvitationCode.php`
- `app/Http/Controllers/Auth/InvitedUserRegistrationController.php`
- `app/Console/Commands/ManageInvitationCode.php`
- `tests/Feature/Auth/InvitedUserRegistrationTest.php`

**Files to Modify:**

- `config/[app-name].php` (add require_invitation key)
- `routes/auth.php` or `routes/web.php` (update registration routes)
- `resources/views/auth/register.blade.php` (add invitation code input)
- `.env.example` (add APP_PREFIX_REQUIRE_INVITATION)

**Files to Reference:**

- Existing registration controller (identify and extend)
- User model (reference for foreign key)
- Auth routes file (update controller references)

---

## 16. Claude Code Agent Instructions

When implementing this feature:

1. **Read the existing codebase first**
    - Identify registration controller location and structure
    - Find registration view location
    - Determine auth routing structure
    - Check config file organization

2. **Adapt naming to project conventions**
    - Match existing controller naming patterns
    - Use project's config file structure
    - Follow view directory structure
    - Match existing variable naming

3. **Maintain consistency**
    - Match code style (PSR-12, project standards)
    - Use same database query patterns as existing code
    - Follow existing validation patterns
    - Match error handling approach

4. **Test incrementally**
    - Test after each phase completion
    - Verify existing functionality still works
    - Run existing test suite before and after
    - Test in development environment first

5. **Document changes**
    - Update README or equivalent
    - Add inline comments where logic is complex
    - Document any deviations from spec (with reasons)
    - Note any project-specific adaptations made

---

## End of Specification

This document provides complete implementation details for the invitation code system. All technical decisions, validation rules, and error messages are specified. Adapt naming and structure to match target project while maintaining core functionality and security measures.
