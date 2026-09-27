# StudentHub Portal 🎓
**Subject:** Web Development Framework / Fundamentals (W.D.F)  
**Project:** Academic & Student Management Portal

---

## 📌 Project Overview & Scope

**StudentHub** is a centralized, accessible, and responsive student portal web application built using clean, simple **HTML5** and **CSS3**. It simplifies academic administration, provides real-time academic tracking (attendance, grades, enrolled subjects), facilitates campus event discovery, and enables direct communication between students, faculty, and administrators.

---

## 👥 User Roles & Responsibilities

| User Role | Description & Permissions |
| :--- | :--- |
| **Student** | View personal dashboard, enrolled courses, attendance records, exam results, register for campus events, update contact profile, submit inquiries. |
| **Faculty / Instructor** | Review course schedules, view class lists, post course updates, track student attendance. |
| **Administrator** | Approve new student registrations, publish campus announcements, monitor portal metrics. |
| **Visitor / Guest** | Browse public university information, academic departments, event catalog, and contact directory. |

---

## 🧩 Key Modules & Pages

1. **Home (`index.html`)**: Landing page featuring portal mission, key modules, latest notices, and quick action links.
2. **About Us (`about.html`)**: University vision, mission, core objectives, and academic departments table.
3. **Student Registration (`register.html`)**: Accessible registration form with validation, fieldsets, and academic details.
4. **User Login (`login.html`)**: Authentication portal with role selector (Student/Faculty/Admin) and security guidance.
5. **Student Dashboard (`dashboard.html`)**: Academic command center showing attendance, CGPA, enrolled courses, and daily timetable.
6. **Campus Events (`events.html`)**: Catalog of hackathons, workshops, guest lectures, and cultural fests with registration buttons.
7. **Student Profile (`profile.html`)**: Academic performance history table (SGPA/CGPA), verified skills, and editable bio.
8. **Contact Us (`contact.html`)**: Support helpdesk inquiry form, department directory table, and campus location details.
9. **Admin Panel (`admin.html`)**: Administrator control center for approving pending student accounts and posting notices.

---

## 🗺️ Sitemap & Navigation Flow


                           [ index.html (Home) ]
                                     |
    +---------------+----------------+----------------+----------------+
    |               |                |                |                |
[ about.html ] [ events.html ] [ contact.html ] [ login.html ] [ register.html ]
                                                      |                |
                                            (After Sign In)     (After Sign Up)
                                                      |                |
                                             [ dashboard.html ] <------+
                                                      |
                                       +--------------+--------------+
                                       |                             |
                               [ profile.html ]               [ admin.html ]
```

---

## 📐 Low-Fidelity Wireframes (Structural Layout)

### Standard Page Layout (Desktop)
```text
+-----------------------------------------------------------------------------+
| HEADER: [Logo] StudentHub          NAV: Home | About | Dashboard | Events...|
+-----------------------------------------------------------------------------+
| BREADCRUMB: Home / Current Page                                             |
+-----------------------------------------------------------------------------+
| PAGE BANNER: Page Title & Brief Subtitle                                    |
+------------------------------------------------------+----------------------+
| MAIN CONTENT AREA (70% Width)                        | SIDEBAR (30% Width)  |
|                                                      |                      |
| [Section / Article Cards]                            | [Notices / Schedule] |
| - Card 1: Title, details, action button              | - Item 1             |
| - Card 2: Title, details, action button              | - Item 2             |
|                                                      |                      |
| [Data Tables / Forms / Fieldsets]                    | [Quick Action Links] |
|                                                      |                      |
+------------------------------------------------------+----------------------+
| FOOTER: About StudentHub | Quick Navigation | Contact Info | Copyright      |
+-----------------------------------------------------------------------------+
```

---

## 📂 Project Directory Structure

```text
W.D.F/
├── README.md                # Complete project documentation & viva analysis
├── index.html               # Home landing page
├── about.html               # About university departments & portal
├── register.html            # Student registration form
├── login.html               # Multi-role authentication page
├── dashboard.html           # Academic student dashboard
├── events.html              # Campus events & workshop catalog
├── profile.html             # Student profile & SGPA/CGPA history
├── contact.html             # Helpdesk inquiry form & directory
├── admin.html               # Administrator moderation portal
├── data/
│   └── events.json          # Event records fetched by the events page
├── js/
│   ├── events.js            # Fetch, render, search, filter, sort, and paginate events
│   ├── regis.js             # Registration form validation
│   └── theme.js             # Light/dark theme toggle
└── css/
    └── style.css            # Shared responsive and accessible styling
```

---

## 🏷️ HTML5 Semantic Elements & Accessibility Features

### 1. Semantic Elements Implemented
- `<header>`: Site branding and global navigation.
- `<nav>`: Primary navigation (`aria-label="Main Navigation"`) and breadcrumb hierarchy (`aria-label="Breadcrumb"`).
- `<main id="main-content">`: Main body landmark for assistive technologies.
- `<section>`: Thematic grouping of content with associated headings.
- `<article>`: Self-contained cards for events, dashboard modules, and notices.
- `<aside>`: Contextual sidebars for timetables, quick links, and rules.
- `<footer>`: University contact info, copyright, and secondary links.
- `<address>`: For structured campus address data.
- `<form>`, `<fieldset>`, `<legend>`, `<label>`: Accessible form controls with explicit `for` and `id` bindings.

### 2. Accessibility Best Practices (WCAG Level AA)
- **Skip to Main Content Link (`.skip-link`)**: Allows keyboard and screen-reader users to bypass top navigation.
- **Form Association**: Every form input is explicitly linked with a `<label for="...">`.
- **Keyboard Navigation**: Focus outlines are styled and visible across all interactive elements (`:focus-visible`).
- **Heading Hierarchy**: Strict, logical heading levels (`<h1>` &rarr; `<h2>` &rarr; `<h3>` &rarr; `<h4>`).
- **Contrast Ratios**: High-contrast, easily readable color combinations.

---

## 📝 Lab Practical Questions & Viva Preparation

### Practical 1: Analysis & Answers

#### Q1: What is a URL and what are its parts?
**Answer:**  
A **Uniform Resource Locator (URL)** is a specific address used to locate a resource on the internet.  
**Parts of a URL:** `https://www.studenthub.edu:443/dashboard.html?user=23IT001#courses`
1. **Scheme / Protocol (`https://`)**: Specifies how data is transferred (HTTP, HTTPS).
2. **Subdomain (`www.`)**: Specific subdivision of the main domain.
3. **Domain Name / Host (`studenthub.edu`)**: The human-readable name of the server.
4. **Port Number (`:443`)**: Network port (80 for HTTP, 443 for HTTPS).
5. **Path (`/dashboard.html`)**: Specific resource file or route on the server.
6. **Query Parameters (`?user=23IT001`)**: Extra parameters passed to the page.
7. **Fragment / Anchor (`#courses`)**: Internal bookmark for direct section jumping.

#### Q2: How is an HTML file processed in a web browser?
**Answer:**  
The browser rendering engine processes an HTML file via the Critical Rendering Path:
1. **HTML Parsing**: HTML bytes &rarr; characters &rarr; tokens &rarr; nodes &rarr; **DOM (Document Object Model)** tree.
2. **CSS Parsing**: CSS bytes &rarr; rules &rarr; **CSSOM (CSS Object Model)** tree.
3. **Render Tree**: Combining DOM + CSSOM to determine which visible elements exist.
4. **Layout (Reflow)**: Calculating geometric sizes and positions of all elements on screen.
5. **Painting**: Filling pixels with colors, text, borders, and shadows.

#### Q3: How will page navigation flow be managed among all HTML pages?
**Answer:**  
Navigation flow is managed using relative hyperlinks (`href="page.html"`), consistent top navigation bars, active page highlighting (`class="active"`), breadcrumbs (`<nav class="breadcrumb">`), and intuitive links between related workflows (e.g. Register &rarr; Login &rarr; Dashboard &rarr; Profile/Admin).

#### Q4: How will Git commits be maintained after each practical?
**Answer:**  
Using atomic Git commit commands per lab practical milestone:
```bash
git add .
git commit -m "feat(practical-1): project setup, sitemap, wireframes, and readme"
git commit -m "feat(practical-2): semantic HTML5 pages and simple CSS3 styling"
git push origin main
```

---

### Practical 2: Analysis & Answers

#### Q1: Are semantic tags such as `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, and `<footer>` used properly?
**Answer:**  
Yes. All pages use `<header>` for branding and menu, `<main id="main-content">` for the page content, `<section>` and `<article>` for structured cards, `<aside>` for supplementary widgets, and `<footer>` for universal copyright and footer links.

#### Q2: Are form labels, headings, image alt text, and navigation links accessible?
**Answer:**  
Yes. All form inputs have paired `<label for="...">` elements with `required` tags, headings follow a strict hierarchy (`<h1>` &rarr; `<h4>`), and breadcrumbs use `<li aria-current="page">` to announce current location.

#### Q3: Is the page structure consistent across all pages?
**Answer:**  
Yes. Every page follows the exact same clean layout architecture: Skip Link &rarr; Header & Navigation &rarr; Breadcrumbs &rarr; Page Banner &rarr; Main Grid with Sidebar &rarr; Footer.

---

### Practical 6: Rendering External JSON Data

The Events page fetches records from `data/events.json` with the Fetch API and renders them as event cards. Users can search titles, descriptions, venues, or categories; filter by category; sort by date or title; and paginate results. Loading, empty-result, and fetch-error states are announced through a live status region.

Serve the project through VS Code Live Server or another local HTTP server. Browsers commonly block `fetch()` when the page is opened directly using a `file://` URL.

## 🚀 How to Run the Project Locally

1. Open the project folder in **Visual Studio Code**.
2. Start **Live Server** (or another local HTTP server) from `index.html`; the Events page needs HTTP access to fetch its JSON data.
3. Navigate between pages using the top menu and footer links.
