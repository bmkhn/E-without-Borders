# System Presentation Flow

> A simple walkthrough of the membership management system.
> We'll start with what everyone sees, then step into the admin side step by step.

**TL;DR — The Presentation in One Minute:**

> **Public website** → A professional homepage with a guided How to Join section and public member profiles
> **Admin login** → Each person has their own secure account
> **Dashboard** → See numbers at a glance — regions, clubs, members, active vs. inactive
> **Three roles** → National Admin (sees all), Regional Admin (sees their region), Club Admin (sees their club)
> **Set up your org** → Create regions, add clubs under them, define positions
> **Manage members** → Add, search, edit, import from Excel, or move to trash
> **Payments & status** → Record payments; members auto-marked Active or Inactive
> **Year rollover** → Runs automatically every January — unpaid members become Inactive
> **Activity log** → Every change is recorded with who did it and when

---

## 1. The Public Website — What Everyone Sees

**Start here:** The homepage (`/`).

### Show This

- **The main page** — Logo, tagline, the overall look and feel
- **The "How to Join" section** — Scroll down and a small button appears at the bottom-right:
  - Click **"Let's Start →"** — it guides you through each step one by one
  - Dots fill up as you go through the steps
  - At the last step, it says **"Back to Top"** to take you up again
- **Call to Action** — A button or link encouraging people to get involved

### Why This Matters

> *"This is the first thing people see when they visit. It looks professional, it's easy to understand, and it walks them through how to join without needing to ask anyone."*

### Extra: A Member's Public Profile

Pick any member and open their profile page. It shows:

- Their name, photo, and whether they're an **Active** or **Inactive** member
- What position they hold (e.g. Secretary, Treasurer)
- Their club and region
- Which years they've paid their membership (shown as green badges)
- Any certificates or awards they've earned (downloadable)

If a member hasn't paid for the current year, their profile politely says their membership needs renewal — but still shows their certificates.

---

## 2. Logging Into the Admin System

> *"Now let's go behind the scenes."*

### Show This

1. Go to the **login page**
2. Type in an email and password — click **Login**

### Points to Mention

- Each admin has their own login — no sharing passwords
- There's a password confirmation step before doing something important (like deleting something)
- The system keeps you logged in safely during your session

> *"Every person who manages the system has their own secure login. No shared passwords, no confusion about who did what."*

---

## 3. The Dashboard — Your Control Panel at a Glance

**Go to:** Dashboard

### What You See Depends on Who You Are

| If You Are... | You See... |
|--------------|-----------|
| **National Admin** | Everything across the whole country — total members, regions, clubs |
| **Regional Admin** | Only the region they're responsible for |
| **Club Admin** | Only their own club |

### What We'll Show (as National Admin)

- **Big number cards** — How many regions, clubs, positions, and total members
- **Club Status** — A list grouped by region showing each club's active vs. inactive members with simple colored bars (green = active, red = inactive)
  - Click any club to jump straight to its members
- **Positions** — Tiles showing how many people hold each role (e.g. 3 Presidents, 15 Secretaries)

> *"Everything you see here is clickable. Click a number, and it takes you straight to that list with the filter already applied. No searching needed."*

---

## 4. Who Can Do What — The Role System

> *"Let's talk about who gets to do what in the system."*

### The Three Management Roles

| Role | What They Can Do |
|------|-----------------|
| **National Admin** | Sees everything nationwide. Can manage regions, clubs, positions, and all members. Can also create other admin accounts. |
| **Regional Admin** | Sees only their own region. Manages clubs and members within that region. |
| **Club Admin** | Sees only their own club. Manages the members in that club. |

### How It Works in Practice

- A **Regional Admin** logs in and only sees their region's clubs and members — they can't see or change anything outside their area
- A **Club Admin** logs in and only sees their club — they can't see other clubs or regions
- A **National Admin** sees everything

> *"Everyone only sees what they're supposed to see. A club president won't accidentally mess with another club's data. It's automatic."*

---

## 5. Setting Up Regions

**Go to:** Regions

### Show This

- **List of regions** — shows each region and how many clubs are in it
- **Create a New Region** — type a name, fill in the Regional Admin's details (name, email, password), and click save
  - The system automatically creates the **Regional Admin login** — they can log in right away
- **Edit** — change the name or update the Regional Admin's password
- **Delete** — type "DELETE" to confirm, and the region plus its admin account is removed

### Key Takeaway

> *"When you create a region, the system also creates the person who will manage it. They get their own login immediately — no extra steps needed."*

---

## 6. Setting Up Clubs

**Go to:** Clubs

### Show This

- **List of clubs** — grouped by region, shows member count
- **Create a New Club** — pick which region it belongs to, type the name, fill in the Club Admin's details
  - The Club Admin login is created automatically
- **Edit** — change name, region, or Club Admin password
- **Delete** — confirmation required; removes the club and its admin account

### Key Takeaway

> *"Same as regions — create a club, and the club's admin is ready to log in. No waiting, no separate setup."*

---

## 7. Setting Up Positions

**Go to:** Positions

### Show This

- **List of positions** — e.g. President, Vice President, Secretary, Treasurer, etc.
- **Create / Edit / Delete** — simple. A position is just a label that tells you what role a member holds.

> *"Positions are simple labels — President, Secretary, Auditor, and so on. They show up on member profiles and in the dashboard counts."*

---

## 8. Managing Members — The Heart of the System

**Go to:** Members

### Show This

#### Finding Members

- **Search box** — type any name, even partial, and it finds matches
- **Filters** — narrow down by region, club, active/inactive status, or position
- **Shows count** — "Showing 15 of 200 members"
- **Export to Excel** — downloads a spreadsheet of whatever you're looking at

#### Adding a New Member

Open the create form:

- Fill in: first name, middle initial, last name, suffix (if any)
- Pick a **club** and **position** from dropdown menus
- Optional: upload a **profile picture** — the system automatically shrinks it to a good size
- Optional: add **certificates** right away
- **Duplicate Check** — if someone with the same name or phone number already exists in that club, the system warns you

> *"Before saving, the system checks for possible duplicates — so you don't accidentally add the same person twice."*

#### Importing from a Spreadsheet

Show the import feature:

- Upload a CSV file (spreadsheet) with columns like: First Name, Last Name, Club, Position, Years Paid
- The system reads it, matches clubs by name, and creates all the members
- If it finds exact duplicates, it skips them automatically
- A Club Admin's import will be rejected if it tries to add members to a different club

> *"Got an Excel list of members? Upload it here. The system handles the rest — no manual data entry."*

#### Editing a Member

Open an edit form:

- Change any details
- **Replace photo** — upload a new one (old one is deleted)
- **Remove photo** — take it off entirely
- **Certificates** — add new ones, update files, or remove them
- **Delete Member** — moves them to the "Trash" (soft delete), keeping everything in case you need to restore them

> *"Everything is tracked. If someone changes a member's club or position, we know who did it and when."*

#### The Trash (Recycle Bin)

- **Restore** — brings the member back exactly as they were, including certificates and payment history
- **Delete Forever** — removes everything permanently: member record, certificates, uploaded files, payment records

| Action | What Happens to Their Files |
|--------|---------------------------|
| Delete (soft) | Everything is kept, just hidden |
| Restore | Everything comes back |
| Delete Forever | Files and records are gone for good |

---

## 9. Payments & Membership Status

**Go to:** Payments

### Show This

- **Payment list** — shows who paid, which year, and when
- **Filters** — by year, club, or member name
- **Record a Payment** — pick a member, pick the year, pick the date
  - If they already paid for that year, the system says so
  - If the payment was previously deleted, it gets restored instead of creating a duplicate
- **Auto Status Update** — paying for the current year automatically marks the member as **Active**

### The Year-End Process

> *"Every January 1st, the system automatically checks who hasn't paid for the new year and marks them as Inactive. No one has to remember to do this."*

- If a member is **Active**, their public profile shows them as a full member
- If they're **Inactive**, their profile politely says their membership needs renewal

### How Membership Works

> *"Membership status is simple: pay for the year, you're active. Don't pay, you're inactive. No complicated rules, no manual toggling. The system handles it."*

---

## 10. Managing Admin Accounts

**Go to:** Admins

> *"Only the National Admin can get here."*

### Show This

- **List of all admins** — shows their name, email, role badge, and assigned club/region
- **Filter by role** — see just Club Admins, just Regional Admins, etc.
- **Search** — by name or email
- **Live Email Check** — as you type an email, it tells you if it's already taken (green check = available, red X = taken)

### Creating an Admin

Show the form:

| Picking This Role... | You Just Need... |
|---------------------|-----------------|
| **National Admin** | Name, email, password |
| **Regional Admin** | Name, email, password, plus pick their **Region** |
| **Club Admin** | Name, email, password, plus pick their **Club** |

### Deleting an Admin

Requires you to:
1. Check the "I understand" box
2. Type the word "DELETE"
3. The delete button only works when both are done

You also **cannot delete your own account** — the system prevents it.

---

## 11. The Activity Log — Who Did What and When

**Go to:** Audit Logs

### Show This

- **Filterable log** — search for any action, filter by type
- **Examples of what's tracked**:
  - When a member is created, edited, deleted, or restored
  - When an admin account is created or deleted
  - When a payment is recorded
  - When members are imported or exported
- For edits, it shows **what changed** — old value vs. new value

> *"Every action is recorded: who did it, what they changed, and when. If someone asks 'who moved this member to a different club?' — you have the answer."*

---

## 12. Things That Run Automatically

### What Happens Behind the Scenes

| Task | What It Does | When |
|------|-------------|------|
| **Year Rollover** | Marks unpaid members as Inactive | January 1st (automatic) |
| **Log Cleanup** | Deletes activity logs older than 1 year | Daily (automatic) |
| **Initial Setup** | Creates the very first admin account | Once (by you, the developer) |

> *"Once the system is set up, it takes care of itself. No one needs to remember to run year-end reports or clean up old data."*

---

## Suggested Presentation Flow (30–40 minutes)

| Part | What You Cover | Time |
|------|---------------|------|
| **1. The Public Website** | Homepage, how-to-join guide, member profiles | 5 min |
| **2. Logging In** | Login screen, password safety | 3 min |
| **3. The Dashboard** | Numbers at a glance, clickable shortcuts | 5 min |
| **4. Roles** | Who does what (National / Regional / Club Admin) | 3 min |
| **5. Setting Up** | Regions → Clubs → Positions | 5 min |
| **6. Members** | Finding, adding, importing, editing, trash | 8 min |
| **7. Payments** | Recording payments, auto status updates, year-end | 4 min |
| **8. Admins** | Creating admin accounts, restrictions | 3 min |
| **9. Activity Log** | Seeing who did what | 2 min |
| **10. Q&A** | Open floor | 5–10 min |

---

## A Note on Roles

> *"Should I show the other roles during the presentation?"*

**Suggestion:** Present everything from the **National Admin** view — it shows the full system. Then explain:

> *"What you just saw is the full national view. If a Regional Admin logs in, they only see their own region's clubs and members. A Club Admin only sees their own club. The screens look the same — just less data. Your people will only ever see what they need to see."*

This way:
- You show **everything the system can do** in one go
- No switching between accounts
- Scoping is explained as a **built-in safety feature**, not a limitation
- If someone asks about a specific role, you can answer confidently

---

## Quick Reference: Main Pages

| Page | What It Does |
|------|-------------|
| `/` | Public homepage with How to Join guide |
| `/member-profile/{slug}` | A member's public profile |
| Dashboard | Overview of numbers and status |
| Regions | Add/change/remove regions |
| Clubs | Add/change/remove clubs |
| Positions | Add/change/remove positions |
| Members | Find, add, edit, import members |
| Trash (Members) | Restore or permanently delete |
| Payments | Record and track membership payments |
| Admins | Create and manage admin accounts |
| Activity Log | See a record of every change |
| Login | Admin sign-in page |
