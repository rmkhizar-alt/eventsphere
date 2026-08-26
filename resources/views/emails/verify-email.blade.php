@component('mail::message')
# Verify Your EventSphere Account

A verification code has been sent to your email address **{{ $user->email }}**.

## Your OTP Code
### {{ $otp }}

This code will expire in **15 minutes** for security purposes.

## How to Verify
1. Enter the OTP code in the verification form on the EventSphere website
2. Once verified, your account will be activated
3. You can then log in and start exploring events

---
If you didn't request this verification, please ignore this email. This OTP is valid for only 15 minutes.

---
The EventSphere Team