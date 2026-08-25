# Maurice Appliances — Forms & Email Setup Checklist (`form_todo.md`)

This checklist contains all necessary steps and configuration values to connect the Maurice Appliances website forms to your Hostinger business email accounts.

---

## 1. Credentials & Configuration Inputs

Fill in these details in your [`.env`](file:///.env) file or [`api/config.php`](file:///api/config.php):

| Field | Configuration Key | Recommended Value | Your Value |
| :--- | :--- | :--- | :--- |
| **SMTP Server Host** | `SMTP_HOST` | `smtp.hostinger.com` | `smtp.hostinger.com` |
| **SMTP Server Port** | `SMTP_PORT` | `465` (SSL) or `587` (TLS) | `465` |
| **SMTP Security** | `SMTP_SECURITY` | `ssl` | `ssl` |
| **SMTP Username** | `SMTP_USERNAME` | `customer.care@mauriceappliances.in` | `customer.care@mauriceappliances.in` |
| **SMTP Password** | `SMTP_PASSWORD` | `[YOUR_HOSTINGER_MAILBOX_PASSWORD]` | `_________________________` |
| **Sender Email (From)**| `FROM_EMAIL` | `customer.care@mauriceappliances.in` | `customer.care@mauriceappliances.in` |
| **Sender Name** | `FROM_NAME` | `Maurice Appliances Website` | `Maurice Appliances Website` |
| **Destination Inbox (To)**| `TO_EMAIL` | `customer.care@mauriceappliances.in` | `customer.care@mauriceappliances.in` |

> [!IMPORTANT]
> **Security Notice**: Never commit your real mailbox password to GitHub or public repositories. Put the password in `.env` or edit `api/config.php` directly on your Hostinger server.

---

## 2. Server Deployment & PHPMailer Installation

The backend uses **PHPMailer** to communicate with Hostinger's SMTP server securely.

### Option A: Install via Composer (Recommended)
If SSH or Composer is enabled on your Hostinger plan:
```bash
# In the website root folder on Hostinger
composer install
```
*(This will read `composer.json` and generate the `vendor/` folder with PHPMailer).*

### Option B: Upload `vendor/` Folder
If you do not have SSH on your hosting plan:
1. Run `composer install` or `composer require phpmailer/phpmailer` on your local computer.
2. Upload the generated `vendor/` folder into your website root directory on Hostinger via File Manager or FTP.

---

## 3. Direct Backend Verification

Before testing frontend forms, verify the PHP endpoint directly in your browser:

1. Open in browser: `https://mauriceappliances.in/api/submit-form.php`
2. **Expected Response (JSON)**:
   ```json
   {
     "success": false,
     "message": "Method not allowed. Please submit the form via POST."
   }
   ```
   *(If you see this response, PHP is executing correctly and ready to receive submissions).*

---

## 4. End-to-End Form Verification Checklist

Test each form on the website to confirm emails arrive in `customer.care@mauriceappliances.in`:

- [ ] **Contact Form** (`/contact/contact.html` or `/contact.html`)
  - Form Type: `contact_message`
  - Expected Subject: `New Contact Form Submission`
  - Verifies: Name, Phone, Email, Subject, Message, Reply-To header.

- [ ] **Dealership Application Form** (`/dealers/become-dealer.html`)
  - Form Type: `dealer_application`
  - Expected Subject: `New Dealer Application`
  - Verifies: Business Name, Contact Person, GST/PAN, Experience, State, District, Investment Capacity.

- [ ] **Job & Career Application** (`/company/careers.html`)
  - Form Type: `job_application`
  - Expected Subject: `New Job Application`
  - Verifies: Applicant Name, Phone, Position Applied, Experience, Portfolio/CV Link.

- [ ] **Warranty Registration** (`/support/warranty.html`)
  - Form Type: `warranty_registration`
  - Expected Subject: `New Warranty Registration`
  - Verifies: Customer Name, Mobile, Product Model, Serial Number, Purchase Date, Dealer Name, Invoice Number.

- [ ] **Customer Service / Repair Request** (`/support/service.html`)
  - Form Type: `service_ticket`
  - Expected Subject: `New Service Request`
  - Verifies: Customer Name, Contact Number, Appliance Category, Model, Issue Description, Pincode, Address.

- [ ] **Express Dealer Callback** (`/index.html` — B2B Quick Section)
  - Form Type: `express_dealer_callback`
  - Expected Subject: `New Express Dealer Callback`
  - Verifies: Name, City, Phone Number.

- [ ] **Product Inquiry Modal** (Triggered via "Inquire" button on product detail pages or navbar)
  - Form Type: `product_inquiry`
  - Expected Subject: `New Product Inquiry`
  - Verifies: Selected SKU Model, Customer Name, Phone, Email, City, Inquiry Type, Requirement Notes.

- [ ] **Newsletter Subscription** (`/index.html` — Footer)
  - Form Type: `newsletter_subscription`
  - Expected Subject: `New Newsletter Subscription`
  - Verifies: Subscriber Email Address.

---

## 5. Troubleshooting & FAQ

| Issue / Symptom | Probable Cause | Resolution |
| :--- | :--- | :--- |
| **"Unable to submit your request right now" (500 Error)** | Incorrect mailbox password or SMTP authentication failure. | Double-check the password in `.env` / `api/config.php`. Verify you can log in at `https://mail.hostinger.com`. |
| **"PHPMailer vendor library not installed"** | The `vendor/autoload.php` file is missing. | Run `composer install` or upload the `vendor/` folder to Hostinger. |
| **Emails going to Spam / Junk** | Hostinger SPF/DKIM records need verification. | Check Hostinger DNS zone settings to confirm `v=spf1 include:_spf.mail.hostinger.com ~all` and DKIM are enabled. |
| **Reply button in email replies to customer.care instead of the customer** | Customer did not enter an email or entered an invalid email. | The endpoint automatically sets `Reply-To` when a valid email address is provided in the form submission. |
