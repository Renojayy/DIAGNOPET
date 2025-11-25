# TODO for Restrict Vet Settings Update to Password Only

- [x] Remove handling of profile update POST request in vet_settings.php to disallow profile field edits.
- [x] Disable or remove profile update UI elements/links in vet_settings.php (commented out Profile Settings link).
- [x] Keep password change functionality intact and verified.
- [x] Keep account deletion handling intact.
- [ ] Test vet_settings.php to ensure only password change and account deletion are possible, no profile fields editable.
- [ ] Inform user of completion and readiness for testing.
