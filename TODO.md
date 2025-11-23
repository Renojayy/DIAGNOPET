Vet-Register.php Update Tasks:

- [ ] Remove verification_status radio buttons from the form and PHP POST processing.
- [ ] Add expiration_date input field (type=date) to the form.
- [ ] Add input fields for uploading an image and a file attachment, allowing users to upload files.
- [ ] Set form enctype to "multipart/form-data".
- [ ] Update PHP POST handler to process expiration_date and uploaded files:
  - Validate uploaded files (file type, size).
  - Move uploaded files to the "uploads" folder.
  - Save file paths or names if needed (currently just storing files).
- [ ] Provide proper error handling for upload failures.

Once complete, test registration form for new fields and file upload functionality.
