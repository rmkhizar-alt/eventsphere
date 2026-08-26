@component('mail::message')
# Welcome to EventSphere, {{ $user->name }}!

Your account has been successfully created on EventSphere — the college event management system.

**Account Details:**
- Name: {{ $user->name }}
- Email: {{ $user->email }}
- Username: {{ $user->username }}
- Role: Participant

**What's Next?**
- Browse upcoming events and festivals
- Register for events with just a few clicks
- Track your registrations and attendance
- Download certificates after event participation
- Receive notifications about event updates

**Need Help?**
- Contact us at the college administration office
- Check the FAQ section for common questions

Thank you for joining EventSphere!

---
The EventSphere Team