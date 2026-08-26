/*
 * EventSphere — Shared Notifications Store
 * ------------------------------------------------------------------
 * This is a frontend-only data/state layer (no backend in this build).
 * It backs two things described in the SRS (section 1.6, User Dashboard
 * > Notifications): the navbar bell dropdown on every page, and the
 * full Notifications page (notifications.html).
 *
 * Read/unread state is kept in localStorage under STORAGE_KEY so both
 * surfaces always agree on what's been seen, even across page loads.
 * When the backend is wired up, swap NOTIFICATIONS for an API call and
 * keep the same isRead/markRead/markAllRead/unreadCount interface.
 */
(function (global) {
  'use strict';

  const STORAGE_KEY = 'eventSphereNotifRead';

  const NOTIFICATIONS = [
    {
      id: 'n1', type: 'event', icon: 'calendar-plus',
      title: 'New event published',
      body: 'TechWiz 6 finals registration is now open for all departments.',
      time: '2h ago', href: 'events.html'
    },
    {
      id: 'n2', type: 'system', icon: 'user-plus',
      title: 'Account created',
      body: 'Your EventSphere participant account is ready to use.',
      time: '5h ago', href: 'dashboard-student.html'
    },
    {
      id: 'n3', type: 'review', icon: 'star',
      title: 'New review posted',
      body: 'A 4.5★ rating was left for CodeForge 2026.',
      time: '1d ago', href: '#reviews'
    },
    {
      id: 'n4', type: 'event', icon: 'clock',
      title: 'Registration deadline nearing',
      body: 'Rhythm Nation passes close in 48 hours — grab your seat.',
      time: '1d ago', href: 'events.html'
    },
    {
      id: 'n5', type: 'event', icon: 'map-pin',
      title: 'Venue updated',
      body: 'Apex Arena finals moved to the Indoor Courts wing.',
      time: '2d ago', href: 'events.html'
    },
    {
      id: 'n6', type: 'system', icon: 'megaphone',
      title: 'Platform announcement',
      body: 'Founders Day volunteer sign-ups are now live.',
      time: '3d ago', href: '#contact'
    },
    {
      id: 'n7', type: 'review', icon: 'message-circle',
      title: 'Feedback response',
      body: 'Organizers replied to your CodeForge 2026 feedback.',
      time: '4d ago', href: '#reviews'
    },
    {
      id: 'n8', type: 'system', icon: 'shield-check',
      title: 'Certificate ready',
      body: 'Your e-certificate for InnoQuiz is ready to download.',
      time: '6d ago', href: 'dashboard-student.html'
    },
  ];

  const getReadIds = () => {
    try { return new Set(JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]')); }
    catch (_) { return new Set(); }
  };
  const saveReadIds = (idSet) => {
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify([...idSet])); }
    catch (_) { /* localStorage unavailable — state just won't persist */ }
  };

  const isRead = (id) => getReadIds().has(id);

  const markRead = (id) => {
    const ids = getReadIds();
    ids.add(id);
    saveReadIds(ids);
  };

  const markUnread = (id) => {
    const ids = getReadIds();
    ids.delete(id);
    saveReadIds(ids);
  };

  const markAllRead = () => {
    saveReadIds(new Set(NOTIFICATIONS.map((n) => n.id)));
  };

  const unreadCount = () => NOTIFICATIONS.filter((n) => !isRead(n.id)).length;

  global.EventSphereNotifications = {
    NOTIFICATIONS,
    STORAGE_KEY,
    isRead,
    markRead,
    markUnread,
    markAllRead,
    unreadCount,
  };
})(window);
