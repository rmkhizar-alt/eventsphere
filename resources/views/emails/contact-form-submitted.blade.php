@component('mail::message')
# New Contact Form Submission from EventSphere

**Sender Details:**
- Name: {{ $data['name'] }}
- Email: {{ $data['email'] }}
- Subject: {{ $data['subject'] ?? 'No subject provided' }}

**Message:**
{{ $data['message'] }}

---
This message was submitted via the EventSphere website contact form.
Please reply to the sender's email address.

---
The EventSphere Team