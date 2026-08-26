<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Attendance;
use App\Models\Feedback;
use App\Models\Certificate;
use App\Models\MediaGallery;
use App\Models\Bookmark;
use App\Models\SavedMedia;
use App\Models\Notification;
use App\Models\Announcement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();

        // --- 1. Admin User ---
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@college.edu',
            'username' => 'admin',
            'role' => 'admin',
            'contact_number' => '+91 98765 43210',
            'password' => bcrypt('password'),
        ]);

        // --- 2. Organizer Users (3) ---
        $organizerNames = ['Rahul Sharma', 'Priya Patel', 'Amit Singh'];
        $organizers = [];
        foreach ($organizerNames as $i => $name) {
            $org = User::create([
                'name' => $name,
                'email' => 'organizer' . ($i+1) . '@college.edu',
                'username' => 'org' . ($i+1),
                'role' => 'organizer',
                'contact_number' => '+91 98765 ' . str_pad(10000 + $i, 4, '0'),
                'password' => bcrypt('password'),
            ]);
            $organizers[] = $org;
        }

        // --- 3. Participant Users (15+) ---
        $participantEmails = [];
        for ($i = 0; $i < 20; $i++) {
$user = User::create([
                'name' => $faker->name,
                'email' => $userEmail = 'participant' . ($i+1) . '@college.edu',
                'username' => 'part' . ($i+1),
                'role' => 'participant',
                'contact_number' => '+91 ' . rand(6000000000, 9999999999),
                'department' => $faker->randomElement(['Computer Science', 'Electronics', 'Mechanical', 'Civil', 'IT', 'Physics', 'Chemistry']),
                'enrolment_no' => $faker->regexify('USN-202[0-9]-'.rand(10, 99).'-'.rand(100, 999)),
                'password' => bcrypt('password'),
            ]);
            $participantEmails[] = $userEmail;
        }

        // --- 4. Events (10+) ---
        $categories = ['Technical', 'Cultural', 'Sports', 'Workshops', 'Annual Day', 'Competitions'];
        $organizersRef = array_merge([$admin], $organizers);
        $organizerIndex = 0;

        $eventsData = [
            [
                'title' => 'Python Workshop',
                'category' => 'Technical',
                'venue' => 'A-Block Seminar Hall',
                'event_date' => '2025-03-15',
                'start_time' => '10:00',
                'end_time' => '16:00',
                'total_seats' => 50,
                'status' => 'approved',
            ],
            [
                'title' => 'Cultural Fest',
                'category' => 'Cultural',
                'venue' => 'Main Ground',
                'event_date' => '2025-04-10',
                'start_time' => '18:00',
                'end_time' => '22:00',
                'total_seats' => 200,
                'status' => 'approved',
            ],
            [
                'title' => 'Sports Day',
                'category' => 'Sports',
                'venue' => 'College Ground',
                'event_date' => '2025-01-20',
                'start_time' => '09:00',
                'end_time' => '17:00',
                'total_seats' => 100,
                'status' => 'completed',
            ],
            [
                'title' => 'Hackathon',
                'category' => 'Technical',
                'venue' => 'Computer Center',
                'event_date' => '2025-05-05',
                'start_time' => '09:00',
                'end_time' => '18:00',
                'total_seats' => 30,
                'status' => 'pending',
            ],
            [
                'title' => 'Tech Expo',
                'category' => 'Technical',
                'venue' => 'Exhibition Hall',
                'event_date' => '2025-02-14',
                'start_time' => '11:00',
                'end_time' => '18:00',
                'total_seats' => 80,
                'status' => 'approved',
            ],
            [
                'title' => 'Art Exhibition',
                'category' => 'Cultural',
                'venue' => 'Art Gallery',
                'event_date' => '2025-03-25',
                'start_time' => '10:00',
                'end_time' => '18:00',
                'total_seats' => 60,
                'status' => 'approved',
            ],
            [
                'title' => 'Basketball Tournament',
                'category' => 'Sports',
                'venue' => 'Sports Complex',
                'event_date' => '2025-04-25',
                'start_time' => '08:00',
                'end_time' => '20:00',
                'total_seats' => 40,
                'status' => 'rejected',
            ],
            [
                'title' => 'Workshop on AI',
                'category' => 'Workshops',
                'venue' => 'Robotics Lab',
                'event_date' => '2025-06-10',
                'start_time' => '14:00',
                'end_time' => '17:00',
                'total_seats' => 25,
                'status' => 'approved',
            ],
            [
                'title' => 'Music Night',
                'category' => 'Cultural',
                'venue' => 'Auditorium',
                'event_date' => '2025-07-05',
                'start_time' => '20:00',
                'end_time' => '23:00',
                'total_seats' => 150,
                'status' => 'approved',
            ],
            [
                'title' => 'Start-up Pitch',
                'category' => 'Workshops',
                'venue' => 'Incubation Center',
                'event_date' => '2025-08-15',
                'start_time' => '10:00',
                'end_time' => '16:00',
                'total_seats' => 40,
                'status' => 'pending',
            ],
        ];

        $events = [];
        foreach ($eventsData as $idx => $data) {
            $eventOrganizer = $organizersRef[array_rand($organizersRef)];
            // Re-assign to spread evenly
            $eventOrganizer = $organizersRef[$organizerIndex % count($organizersRef)];
            $organizerIndex++;

            $event = Event::create([
                'organizer_id' => $eventOrganizer->id,
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . ($idx + 1),
                'description' => "The {$data['title']} is a college event organized as part of the event management system.",
                'category' => $data['category'],
                'venue' => $data['venue'],
                'event_date' => $data['event_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'total_seats' => $data['total_seats'],
                'seats_booked' => 0,
                'waitlist_enabled' => in_array($data['status'], ['approved', 'pending']),
                'certificate_fee' => $data['status'] === 'completed' ? 500.00 : null,
                'status' => $data['status'],
                'cancellation_reason' => $data['status'] === 'rejected' ? 'Event cancelled due to low registration' : null,
                'registration_cutoff' => $data['status'] !== 'rejected' && $data['status'] !== 'completed'
                    ? now()->addDays(7)->toDateTimeString()
                    : null,
            ]);
            $events[] = $event;
        }

        // --- 5. Registrations ---
        foreach ($events as $event) {
            // Register some participants (some confirmed, some waitlisted)
            $numRegistrations = rand(8, 15);
            $registeredUsers = [];

            for ($i = 0; $i < $numRegistrations; $i++) {
                $user = User::inRandomOrder()->first();
                // Skip if already registered
                if (in_array($user->id, array_column($registeredUsers, 'id'))) {
                    $i--;
                    continue;
                }

                $reg = Registration::create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'status' => rand(0, 2) === 0 ? 'confirmed' : (rand(0, 1) === 0 ? 'waitlisted' : 'cancelled'),
                    'qr_token' => Str::random(40),
                    'registered_at' => now(),
                ]);
                $registeredUsers[] = ['id' => $user->id, 'status' => $reg->status];

                // If confirmed, create attendance and feedback later
                if ($reg->status === 'confirmed') {
                    Attendance::create([
                        'registration_id' => $reg->id,
                        'attended' => rand(0, 1),
                        'marked_by' => $organizers[array_rand($organizers)]->id,
                    ]);
                }
            }
        }

        // --- 6. Feedback (only for attended events) ---
        foreach ($events as $event) {
            $confirmedRegistrations = Registration::where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->get();

            foreach ($confirmedRegistrations as $reg) {
                if (rand(0, 1) && $reg->attendance->first()?->attended ?? false) {
                    Feedback::create([
                        'event_id' => $event->id,
                        'user_id' => $reg->user_id,
                        'rating' => rand(1, 5),
                        'venue_rating' => rand(1, 5),
                        'coordination_rating' => rand(1, 5),
                        'technical_rating' => rand(1, 5),
                        'hospitality_rating' => rand(1, 5),
                        'comments' => $faker->sentence(),
                        'submitted_at' => now(),
                    ]);
                }
            }
        }

        // --- 7. Certificates ---
        foreach ($events as $event) {
            $attendedRegistrations = Registration::where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->whereHas('attendance', function ($q) {
                    $q->where('attended', true);
                })
                ->get();

            foreach ($attendedRegistrations as $reg) {
                $feePaid = $event->certificate_fee !== null && rand(0, 1) ? true : false;
                Certificate::create([
                    'event_id' => $event->id,
                    'user_id' => $reg->user_id,
                    'fee_paid' => $feePaid,
                    'certificate_path' => 'certificates/' . $event->slug . '/' . $reg->user_id . '.pdf',
                    'issued_at' => $feePaid ? now()->subDays(rand(1, 30))->toDateTimeString() : null,
                ]);
            }
        }

        // --- 8. Media Gallery ---
        $mediaTypes = ['image', 'video'];
        foreach ($events as $event) {
            $numMedia = rand(3, 8);
            for ($i = 0; $i < $numMedia; $i++) {
                MediaGallery::create([
                    'event_id' => $event->id,
                    'uploaded_by' => $organizers[array_rand($organizers)]->id,
                    'file_type' => $mediaTypes[array_rand($mediaTypes)],
                    'file_path' => 'media/' . $event->slug . '/' . Str::random(10) . '.' . ($mediaTypes[array_rand($mediaTypes)] === 'image' ? 'jpg' : 'mp4'),
                    'caption' => $faker->sentence(),
                    'is_approved' => true,
                ]);
            }
        }

        // --- 9. Bookmarks ---
        foreach ($participantEmails as $i => $email) {
            $user = User::where('email', $email)->firstOrFail();
            $event = $events[array_rand($events)];
            // Avoid duplicate bookmarks
            $existing = Bookmark::where('user_id', $user->id)->where('event_id', $event->id)->first();
            if (!$existing) {
                Bookmark::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                ]);
            }
        }

        // --- 10. Saved Media ---
        foreach ($participantEmails as $email) {
            $user = User::where('email', $email)->firstOrFail();
            $galleries = MediaGallery::inRandomOrder()->take(rand(2, 5))->get();
            foreach ($galleries as $media) {
                $existing = SavedMedia::where('user_id', $user->id)->where('media_id', $media->id)->first();
                if (!$existing) {
                    SavedMedia::create([
                        'user_id' => $user->id,
                        'media_id' => $media->id,
                    ]);
                }
            }
        }

        // --- 11. Notifications ---
        // System notifications for various events
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'system',
            'icon' => 'alert-circle',
            'title' => 'Welcome to EventSphere',
            'body' => 'The college event management system is now active.',
            'is_read' => true,
        ]);

        Notification::create([
            'user_id' => $organizers[0]->id,
            'type' => 'event',
            'icon' => 'star',
            'title' => 'New event published',
            'body' => 'Your event ' . $events[0]->title . ' has been approved and is now live.',
            'href' => '/events/' . $events[0]->slug,
            'is_read' => false,
        ]);

        // --- 12. Announcements ---
        $adminAnnouncement = Announcement::create([
            'sent_by' => $admin->id,
            'title' => 'Welcome to EventSphere 2025',
            'body' => 'The college event management system is now live. All students and staff are invited to register and participate in upcoming events.',
            'target_role' => null,
        ]);

        // Fan out announcement to notifications
        foreach (User::all() as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'system',
                'icon' => 'megaphone',
                'title' => 'Announcement: ' . $adminAnnouncement->title,
                'body' => $adminAnnouncement->body,
                'is_read' => false,
            ]);
        }

        // --- 13. Write seed credentials to file ---
        $credentials = [];
        // Admin
        $credentials[] = ['Role' => 'Admin', 'Email' => 'admin@college.edu', 'Password' => 'password'];
        // Organizers
        foreach ($organizers as $org) {
            $credentials[] = ['Role' => 'Organizer', 'Email' => $org->email, 'Password' => 'password'];
        }
        // Participants
        foreach ($participantEmails as $email) {
            $user = User::where('email', $email)->first();
            $credentials[] = ['Role' => 'Participant', 'Email' => $email, 'Password' => 'password'];
        }

        $fp = fopen(storage_path('seed-credentials.txt'), 'w');
        foreach ($credentials as $c) {
            fputcsv($fp, $c);
        }
        fclose($fp);

        echo "Database seeded successfully with " . count($participantEmails) . " participants, " . count($events) . " events, and supporting data.\n";
    }
}