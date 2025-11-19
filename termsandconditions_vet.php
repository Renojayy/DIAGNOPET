<?php
/*
  diagnopet_terms.php
  PHP file that displays the Terms and Conditions for Diagnopet and allows downloading as a plain-text file.
  Usage:
    - Open in a browser to view the Terms: http://your-server/diagnopet_terms.php
    - Download a .txt copy: http://your-server/diagnopet_terms.php?download=1
*/

$lastUpdated = isset($argv) ? (isset($argv[1]) ? $argv[1] : date('F j, Y')) : date('F j, Y');

$terms = <<<'TEXT'
Terms and Conditions
Diagnopet: Diagnostic Tool Support System for Veterinarians with AI Assistant Chatbot

Last Updated: %LAST_UPDATED%

Welcome to Diagnopet, a diagnostic support system designed to assist veterinarians through automated assessment tools and an AI-powered chatbot. By accessing or using our platform, you agree to the following Terms and Conditions. Please read them carefully.

1. Acceptance of Terms
By creating an account, accessing, or using Diagnopet, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions, as well as our Privacy Policy.

2. Purpose of the System
Diagnopet is intended solely as a decision-support tool for licensed veterinarians. It provides suggestions, assessments, and information based on user-provided data but does not replace professional veterinary judgment.

3. User Responsibilities
Users agree to:
- Provide accurate and complete information when using the diagnostic tools.
- Use the platform ethically and in compliance with local laws and veterinary standards.
- Keep login credentials secure and confidential.
- Not use the system for harmful, fraudulent, or malicious purposes.

4. AI Chatbot Limitations
The AI Assistant:
- Provides recommendations based on algorithms and available data.
- Cannot perform actual diagnosis, prescribe medications, or conduct physical examinations.
- May generate incorrect or incomplete suggestions; veterinarians must verify the information.

Diagnopet is not liable for decisions made solely based on chatbot output.

5. Data Privacy and Security
Diagnopet collects user and patient-related information for analysis and system improvement.
We commit to:
- Protecting data using secure and encrypted storage.
- Not selling or sharing personal data with unauthorized third parties.
- Allowing users to request data deletion or modification.

Users are responsible for ensuring that the data they upload complies with privacy laws (e.g., client or pet owner consent).

6. Intellectual Property Rights
All content, algorithms, designs, trademarks, and system features are the property of Diagnopet developers.
Users may not:
- Copy, distribute, or reverse-engineer any part of the system.
- Use content for commercial purposes without written permission.

7. Restrictions and Prohibited Use
Users are prohibited from:
- Uploading harmful code, malware, or unauthorized scripts.
- Attempting to bypass security features.
- Misrepresenting diagnoses to clients using the platform.

Violation may result in account suspension or legal action.

8. Accuracy and No Warranty
Diagnopet is provided "as is" and "as available." We do not guarantee:
- 100% accuracy of diagnostic suggestions,
- Uninterrupted system availability,
- Error-free AI responses.

Veterinary professionals must use their own judgment when interpreting outputs.

9. System Updates and Modifications
We may update, enhance, or modify system features at any time without prior notice. By continuing to use Diagnopet after changes, you agree to the updated Terms.

10. Limitation of Liability
Diagnopet and its developers are not liable for:
- Misdiagnosis or improper treatment decisions,
- Losses caused by reliance on system outputs,
- Technical issues, downtimes, or data loss.

Users assume full responsibility for how they use the information generated.

11. Account Termination
We may suspend or terminate accounts if users:
- Violate these Terms,
- Misuse the system,
- Engage in unethical or harmful behavior.

Users may voluntarily delete their accounts at any time.

12. Governing Law
These Terms are governed by the laws of the Republic of the Philippines, unless otherwise specified.

13. Contact Information
For concerns, feedback, or support, you may contact the Diagnopet administrators at:
diagnopet.support@gmail.com

TEXT;

$terms = str_replace('%LAST_UPDATED%', $lastUpdated, $terms);

// If user requested a download (example: ?download=1), send as a plain text file.
if (php_sapi_name() !== 'cli' && isset($_GET['download'])) {
    header('Content-Type: text/plain; charset=utf-8');
    header('Content-Disposition: attachment; filename="diagnopet_terms.txt"');
    echo $terms;
    exit;
}

// HTML output
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Diagnopet — Terms and Conditions</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; line-height: 1.6; margin: 32px; color: #111; }
    .container { max-width: 900px; margin: 0 auto; }
    h1 { font-size: 24px; }
    pre { white-space: pre-wrap; word-wrap: break-word; background: #f8f8f8; padding: 18px; border-radius: 8px; }
    .actions { margin: 12px 0; }
    .btn { display: inline-block; padding: 8px 12px; border-radius: 6px; text-decoration: none; border: 1px solid #ccc; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Diagnopet — Terms and Conditions</h1>
    <p><strong>Last Updated:</strong> <?php echo htmlspecialchars($lastUpdated); ?></p>
    <div class="actions">
      <a class="btn" href="?download=1">Download as .txt</a>
    </div>
    <pre><?php echo htmlspecialchars($terms); ?></pre>
    <p>If you'd like this exported as a Word (.docx) or PDF file, tell me and I will provide a ready-to-download file version.</p>
  </div>
</body>
</html>
