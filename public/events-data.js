/*
 * EventSphere — Shared Events Data Store
 * ------------------------------------------------------------------
 * Frontend-only data layer (no backend in this build). Backs the
 * Live Events directory (events.html) and the Event Details page
 * (events-open.html) so both pages always stay in sync.
 *
 * When the backend is wired up, swap EVENTS for an API call and keep
 * the same shape: id, title, desc, longDesc, category, org, orgContact,
 * date, time, venue, address, img, gallery[], totalSeats, seatsBooked,
 * highlights[], prerequisites[], tags[], agenda[{time,title,desc}].
 */
(function (global) {
  'use strict';

  const CATEGORY_STYLES = {
    'Technical':     { chip: 'bg-[#e4e2f6] text-[#433a86] border-[#433a86]/20', dot: '#433a86', icon: 'cpu' },
    'Cultural':      { chip: 'bg-[#f3e4d8] text-[#a6642f] border-[#a6642f]/20', dot: '#a6642f', icon: 'music' },
    'Sports':        { chip: 'bg-[#dbe9e4] text-[#2f6b54] border-[#2f6b54]/20', dot: '#2f6b54', icon: 'trophy' },
    'Workshops':     { chip: 'bg-[#e6e0f0] text-[#6a4c93] border-[#6a4c93]/20', dot: '#6a4c93', icon: 'wrench' },
    'Annual Day':    { chip: 'bg-[#f5e0e0] text-[#a6483f] border-[#a6483f]/20', dot: '#a6483f', icon: 'party-popper' },
    'Competitions':  { chip: 'bg-[#dde6f0] text-[#33628c] border-[#33628c]/20', dot: '#33628c', icon: 'medal' },
  };

  const EVENTS = [
    {
      id: 'techwiz-7',
      title: 'TechWiz 7: National Hackathon Finals',
      desc: 'A 24-hour build sprint where finalist teams ship a working product in front of industry judges.',
      longDesc: 'TechWiz 7 brings together the top 40 teams from the regional qualifiers for a 24-hour, in-person build sprint. Teams will have access to mentor office hours, cloud credits, and a live judging panel drawn from partner companies. Categories include FinTech, HealthTech, and Sustainability. Top three teams win cash prizes, internship interviews, and incubation support.',
      category: 'Technical',
      org: 'Dept. of Computer Science',
      orgContact: 'cs.techwiz@campus.edu',
      date: 'Sep 12, 2026',
      time: '9:00 AM – 9:00 PM',
      venue: 'Innovation Hub, Block C',
      address: 'Innovation Hub, Block C, Main Campus',
      img: 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?q=80&w=1200&auto=format&fit=crop',
      gallery: [
        'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=800&auto=format&fit=crop'
      ],
      totalSeats: 160,
      seatsBooked: 142,
      highlights: [
        'Mentorship from 10+ industry engineers throughout the night',
        'Free meals, snacks, and a dedicated quiet-rest zone',
        '₹1,50,000 in total prize money across three tracks'
      ],
      prerequisites: [
        'Must have cleared the online qualifier round',
        'Teams of 2–4, at least one member from this campus',
        'Bring a valid student ID for check-in'
      ],
      tags: ['Hackathon', '24-Hour', 'Prizes', 'Team Event'],
      agenda: [
        { time: '9:00 AM', title: 'Check-in & team briefing' },
        { time: '10:00 AM', title: 'Hacking begins', desc: 'Mentors available throughout the day' },
        { time: '8:00 PM', title: 'Submissions close' },
        { time: '9:00 PM', title: 'Judging & closing ceremony' }
      ]
    },
    {
      id: 'rhythm-nation',
      title: 'Rhythm Nation: Inter-College Music Fest',
      desc: 'An evening of live bands, solo performances, and a headline act from a touring indie artist.',
      longDesc: 'Rhythm Nation is the flagship music night of the cultural calendar, featuring student bands, solo acts, and a surprise headliner. Expect a full stage production with professional sound and lighting, food trucks along the lawn, and open seating on a first-come basis.',
      category: 'Cultural',
      org: 'Cultural Affairs Committee',
      orgContact: 'culturals@campus.edu',
      date: 'Sep 20, 2026',
      time: '6:00 PM – 11:00 PM',
      venue: 'Open Air Amphitheatre',
      address: 'Open Air Amphitheatre, North Lawn, Main Campus',
      img: 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?q=80&w=1200&auto=format&fit=crop',
      gallery: [
        'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=800&auto=format&fit=crop'
      ],
      totalSeats: 500,
      seatsBooked: 486,
      highlights: [
        'Live performances from 8 student bands and solo artists',
        'Surprise touring headline act',
        'Food trucks and merch stalls on the lawn'
      ],
      prerequisites: [
        'Open to all students with a valid campus ID',
        'Entry passes must be shown at the gate'
      ],
      tags: ['Music', 'Live Bands', 'Evening Event'],
      agenda: [
        { time: '6:00 PM', title: 'Gates open, food trucks live' },
        { time: '7:00 PM', title: 'Student band performances' },
        { time: '9:30 PM', title: 'Headline act' },
        { time: '11:00 PM', title: 'Wrap up' }
      ]
    },
    {
      id: 'apex-arena',
      title: 'Apex Arena: Inter-College Cricket Cup',
      desc: 'Finals day of the annual cricket cup, featuring the top four college teams in a knockout format.',
      longDesc: 'The Apex Arena Cricket Cup finals bring the four remaining teams together for a knockout day of T20 matches, ending in the championship final. Bleacher seating is open to all students, with a dedicated cheer zone for each participating college.',
      category: 'Sports',
      org: 'Sports Board',
      orgContact: 'sportsboard@campus.edu',
      date: 'Sep 27, 2026',
      time: '8:00 AM – 6:00 PM',
      venue: 'Indoor Courts Wing',
      address: 'Apex Arena, Indoor Courts Wing, Sports Complex',
      img: 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?q=80&w=1200&auto=format&fit=crop',
      gallery: [
        'https://images.unsplash.com/photo-1531415074968-036ba1b575da?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1531061366669-a1a8d2fda5c9?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?q=80&w=800&auto=format&fit=crop'
      ],
      totalSeats: 200,
      seatsBooked: 178,
      highlights: [
        'Semi-finals and final played back-to-back',
        'Live scoreboard and commentary',
        'Trophy presentation immediately after the final'
      ],
      prerequisites: [
        'Open seating, no registration required to spectate',
        'Reserved bleacher passes available for early bookings'
      ],
      tags: ['Cricket', 'Finals', 'Spectator Event'],
      agenda: [
        { time: '8:00 AM', title: 'Semi-final 1' },
        { time: '11:00 AM', title: 'Semi-final 2' },
        { time: '3:00 PM', title: 'Final' },
        { time: '5:30 PM', title: 'Trophy ceremony' }
      ]
    },
    {
      id: 'resume-clinic',
      title: 'Resume Clinic: Placement Week Special',
      desc: 'One-on-one resume reviews and mock interviews with recruiters ahead of placement season.',
      longDesc: 'The Placement Cell hosts a full-day resume clinic ahead of the recruitment drive, offering one-on-one feedback sessions, mock interviews, and a short workshop on ATS-friendly formatting. Slots are limited and allocated on a first-come basis.',
      category: 'Workshops',
      org: 'Placement Cell',
      orgContact: 'placements@campus.edu',
      date: 'Oct 3, 2026',
      time: '10:00 AM – 4:00 PM',
      venue: 'Seminar Hall B',
      address: 'Seminar Hall B, Academic Block 2',
      img: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1200&auto=format&fit=crop',
      gallery: [
        'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=800&auto=format&fit=crop'
      ],
      totalSeats: 100,
      seatsBooked: 95,
      highlights: [
        '15-minute one-on-one review slots with recruiters',
        'Mock interview practice with real-time feedback',
        'ATS formatting workshop included'
      ],
      prerequisites: [
        'Bring a printed and a digital copy of your current resume',
        'Final-year and pre-final-year students prioritized'
      ],
      tags: ['Career', 'Resume', 'Mock Interviews'],
      agenda: [
        { time: '10:00 AM', title: 'ATS formatting workshop' },
        { time: '11:00 AM', title: 'One-on-one review slots begin' },
        { time: '2:00 PM', title: 'Mock interviews' },
        { time: '4:00 PM', title: 'Wrap up & Q&A' }
      ]
    },
    {
      id: 'founders-day',
      title: "Founders' Day Celebration",
      desc: 'The campus-wide annual day celebrating the institution\'s founding, with performances and awards.',
      longDesc: "Founders' Day is the institution's flagship annual celebration, featuring a formal ceremony, cultural performances, alumni felicitations, and the annual excellence awards. The evening closes with a community dinner on the main lawn.",
      category: 'Annual Day',
      org: "Dean's Office",
      orgContact: 'deansoffice@campus.edu',
      date: 'Oct 10, 2026',
      time: '5:00 PM – 9:30 PM',
      venue: 'Main Auditorium',
      address: 'Main Auditorium, Central Block',
      img: 'https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1200&auto=format&fit=crop',
      gallery: [
        'https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1531058020387-3be344556be6?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=800&auto=format&fit=crop'
      ],
      totalSeats: 600,
      seatsBooked: 340,
      highlights: [
        'Alumni felicitation and annual excellence awards',
        'Cultural performances curated by student councils',
        'Community dinner on the main lawn'
      ],
      prerequisites: [
        'Open to all students, staff, and invited alumni',
        'Formal dress code encouraged'
      ],
      tags: ['Annual Day', 'Ceremony', 'Awards'],
      agenda: [
        { time: '5:00 PM', title: 'Formal ceremony & welcome address' },
        { time: '6:15 PM', title: 'Alumni felicitation & awards' },
        { time: '7:00 PM', title: 'Cultural performances' },
        { time: '8:30 PM', title: 'Community dinner' }
      ]
    },
    {
      id: 'innoquiz',
      title: 'InnoQuiz: Campus General Quiz Championship',
      desc: 'A fast-paced buzzer quiz open to teams of two, spanning science, pop culture, and current affairs.',
      longDesc: 'InnoQuiz is the campus\'s most competitive general quiz event, run in a buzzer-round knockout format. Teams of two battle through preliminary written rounds before the top eight advance to the on-stage buzzer finals in front of a live audience.',
      category: 'Competitions',
      org: 'Quiz & Literary Club',
      orgContact: 'quizclub@campus.edu',
      date: 'Oct 17, 2026',
      time: '2:00 PM – 6:00 PM',
      venue: 'Seminar Hall A',
      address: 'Seminar Hall A, Academic Block 1',
      img: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1200&auto=format&fit=crop',
      gallery: [
        'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=800&auto=format&fit=crop'
      ],
      totalSeats: 120,
      seatsBooked: 120,
      highlights: [
        'Written prelims followed by an on-stage buzzer final',
        'Categories: science, pop culture, current affairs, sports',
        'Cash prizes for the top three teams'
      ],
      prerequisites: [
        'Teams of exactly 2 students',
        'Prior quizzing experience not required'
      ],
      tags: ['Quiz', 'Buzzer Round', 'Team Event'],
      agenda: [
        { time: '2:00 PM', title: 'Written prelims' },
        { time: '3:30 PM', title: 'Top 8 announced' },
        { time: '4:00 PM', title: 'On-stage buzzer final' },
        { time: '5:45 PM', title: 'Prize distribution' }
      ]
    },
    {
      id: 'codeforge-2026',
      title: 'CodeForge 2026: Competitive Programming Meet',
      desc: 'A timed, individual competitive programming contest with problems ranging from easy to hard.',
      longDesc: 'CodeForge is a 3-hour individual competitive programming contest hosted on a live judge, with a problem set spanning beginner to advanced difficulty. Rankings are decided by problems solved and total time penalty, with results published live on the leaderboard.',
      category: 'Technical',
      org: 'Dept. of Computer Science',
      orgContact: 'cs.codeforge@campus.edu',
      date: 'Oct 24, 2026',
      time: '11:00 AM – 2:00 PM',
      venue: 'Computer Lab 3',
      address: 'Computer Lab 3, Engineering Block',
      img: 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?q=80&w=1200&auto=format&fit=crop',
      gallery: [
        'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1550439062-609e1531270e?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1516116216624-53e697fedbea?q=80&w=800&auto=format&fit=crop'
      ],
      totalSeats: 90,
      seatsBooked: 61,
      highlights: [
        'Live leaderboard with real-time rank updates',
        'Problems curated by senior competitive programmers',
        'Certificates for all participants, medals for the top 10'
      ],
      prerequisites: [
        'Bring your own laptop or use a lab machine',
        'Familiarity with at least one programming language'
      ],
      tags: ['Competitive Programming', 'Individual Event', 'Timed Contest'],
      agenda: [
        { time: '11:00 AM', title: 'Contest briefing & rules' },
        { time: '11:15 AM', title: 'Contest begins' },
        { time: '2:00 PM', title: 'Contest ends, leaderboard freeze' },
        { time: '2:30 PM', title: 'Results announced' }
      ]
    },
    {
      id: 'basanti-mela',
      title: 'Basanti Mela: Spring Cultural Carnival',
      desc: 'A day-long spring carnival with food stalls, folk performances, and campus-wide games.',
      longDesc: 'Basanti Mela turns the main lawn into a spring carnival for a full day, with student-run food stalls, folk dance performances, craft stands, and lawn games. It closes with a community bonfire and acoustic set as the sun goes down.',
      category: 'Cultural',
      org: 'Cultural Affairs Committee',
      orgContact: 'culturals@campus.edu',
      date: 'Nov 1, 2026',
      time: '11:00 AM – 8:00 PM',
      venue: 'Main Lawn',
      address: 'Main Lawn, Central Campus',
      img: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=1200&auto=format&fit=crop',
      gallery: [
        'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1541532713592-79a0317b6b77?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1467810563316-b5476525c0f9?q=80&w=800&auto=format&fit=crop'
      ],
      totalSeats: 800,
      seatsBooked: 512,
      highlights: [
        'Student-run food stalls and craft stands',
        'Folk dance performances throughout the day',
        'Evening bonfire and acoustic closing set'
      ],
      prerequisites: [
        'Free entry, open to all students and guests'
      ],
      tags: ['Carnival', 'Food Stalls', 'Folk Performances'],
      agenda: [
        { time: '11:00 AM', title: 'Stalls open' },
        { time: '2:00 PM', title: 'Folk dance performances' },
        { time: '6:00 PM', title: 'Games & prize giveaways' },
        { time: '7:00 PM', title: 'Bonfire & acoustic set' }
      ]
    }
  ];

  global.EVENTS = EVENTS;
  global.CATEGORY_STYLES = CATEGORY_STYLES;
})(window);
