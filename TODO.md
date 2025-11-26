# DIAGNOPET - Pet Owner Profile Task

## Task Overview
Create a new file `profilepets.php` that displays and allows editing of pet owner information from the `petowners` table. Link it from the Profile icon in the sidebar of `petowner_dashboard.php`.

## Completed Tasks
- [x] Analyze the petowner_dashboard.php file to understand the sidebar structure
- [x] Search for the petowners table structure to identify fields (Name, Email, ContactNo, Address, Password)
- [x] Create profilepets.php with form to display and edit user information including password change
- [x] Update petowner_dashboard.php to link Profile button to profilepets.php
- [x] Update TODO.md with task completion status

## Pending Tasks
- [ ] Test the profile page functionality
- [ ] Verify database updates work correctly
- [ ] Ensure session updates properly when name is changed

## Notes
- The profile page includes fields: Name, Email, ContactNo, Address, and optional password change
- Password is hashed using password_hash() for security
- Form validation includes email format check and password confirmation
- Success/error messages are displayed after form submission
