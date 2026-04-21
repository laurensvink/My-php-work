# ProjectWeek2 - UI Elements Analysis for Medieval-Themed Replacement

## Overview
The ProjectWeek2 application is a medieval-themed event management system for KVW Gorinchem 2026. This analysis identifies all UI text elements that currently exist and would need translation/replacement for a consistent medieval theme.

---

## 1. admin.php - Royal Palace Dashboard
**Purpose:** Admin control panel for logged-in leaders

### Page Title/Heading
- **Title (HTML):** "Koninklijk Paleis - KVW Gorinchem"
- **H1 Heading:** "Welkom in het Koninklijke Paleis, [username]!"

### Navigation Links
```
- "Kronieken Beheren" (Manage Chronicles)
- "Agenda Beheren" (Manage Agenda)
- "Kunstwerken Uploaden" (Upload Artworks)
- "Kunstcabinet Bekijken" (View Art Cabinet)
- "Uit het Paleis" (Exit the Palace)
```

### Static Text
- "Gebruik de links van het koninkrijk om content te beheren." (Use the kingdom's links to manage content)

---

## 2. login.php - Access to the Fortress
**Purpose:** Authentication page for leaders

### Page Title/Heading
- **Title (HTML):** "Toegang tot de Burcht - KVW Gorinchem"
- **H1 Heading:** "Toegang tot de Burcht" (Access to the Fortress)

### Form Labels
- "Je naam:" (Your name)
- "Het geheim:" (The secret)

### Buttons
- "Toegang Verlenen" (Grant Access)

### Error Messages
- "Incorrect password." (NOT YET MEDIEVAL-THEMED)
- "User not found." (NOT YET MEDIEVAL-THEMED)

### Status Messages
- Displayed in a `<p>` tag below the form

---

## 3. news.php - Royal Chronicles
**Purpose:** News/announcements management and display

### Page Title/Heading
- **Title (HTML):** "Koninklijke Kronieken - KVW Gorinchem"
- **H1 Heading:** "Nieuws & Updates" (News & Updates)

### Navigation Links
```
- "Home" 
- "Dagprogramma" (Daily Program)
- "Foto's & Video's" (Photos & Videos)
- "Nieuws" (News)
- "Beheer" (Management) - if logged in
- "Uitloggen" (Logout) - if logged in
- "Begeleiders Login" (Leaders Login) - if not logged in
```

### Admin Section (Logged In Users Only)
**Section Heading:** "Nieuw Decreet Uitroepen" (Proclaim a New Decree)
- **Form placeholders:**
  - "Titel van het decreet" (Title of the decree)
  - "De koninklijke mededeling" (The royal announcement)
- **Submit button:** "Afkondigen" (Proclaim)

**Edit Section (if editing):**
- **Section Heading:** "Nieuwsbericht Bewerken" (Edit News Message)
- **Submit button:** "Aanpassen" (Adjust)

### Messages
- Success: "Nieuws toegevoegd!" (News added!)
- Success: "Nieuws bijgewerkt!" (News updated!)
- Success: "Nieuws verwijderd!" (News deleted!)
- Error: "Controleer invoer (titel max 100, bericht max 128 tekens)" (Check input...)

### Content Display Section
- **Section Heading:** "Alle Koninklijke Kronieken" (All Royal Chronicles)
- **Empty state:** "Er zijn nog geen nieuwsberichten." (No news messages yet)
- **Action buttons:**
  - "Bewerken" (Edit) - link
  - "Verwijderen" (Delete) - button with confirmation
- **Confirmation dialog:** "Weet je zeker dat je dit bericht wilt verwijderen?" (Are you sure you want to delete this message?)

---

## 4. photos.php - Royal Art Cabinet
**Purpose:** Photo gallery for displaying uploaded artwork

### Page Title/Heading
- **Title (HTML):** "Koninklijke Kunstcabinet - KVW Gorinchem"
- **H1 Heading:** "Koninklijke Kunstcabinet" (Royal Art Cabinet)
- **H2 Heading:** "Foto's & Video's" (Photos & Videos)

### Navigation Links
```
- "Home"
- "Dagprogramma" (Daily Program)
- "Foto's & Video's" (Photos & Videos)
- "Nieuws" (News)
- "Beheer" (Management) - if logged in
- "Uitloggen" (Logout) - if logged in
- "Begeleiders Login" (Leaders Login) - if not logged in
```

### Conditional Elements (Logged In)
- **Button:** "Upload foto" (Upload photo) - link to upload.php
- **Delete button:** "Verwijderen" (Delete)
- **Delete confirmation:** "Weet je zeker dat je deze foto wilt verwijderen?" (Are you sure you want to delete this photo?)

### Status Messages
- **Success/Error message:** Displayed if `$_SESSION["message"]` is set

### Empty State
- "Nog geen foto's geüpload." (No photos uploaded yet)

### Modal Elements
- **Close button:** "&times;" symbol in modal
- Photo titles displayed beneath each image

---

## 5. dagprogramma.php - Royal Agenda Management
**Purpose:** Manage activities for the 5-day event

### Page Title/Heading
- **Title (HTML):** "Koninklijke Agenda - KVW Gorinchem"
- **H1 Heading:** "Koninklijke Agenda Beheren" (Manage Royal Agenda)

### Navigation Links
```
- "Home"
- "Dagprogramma" (Daily Program)
- "Foto's" (Photos)
- "Nieuws" (News)
- "Beheer" (Management)
- "Uitloggen" (Logout)
```

### Activity Form Section
**Section Heading:** "Activiteit toevoegen" (Add Activity)

**Form Labels:**
- "Dag (1-5):" (Day 1-5)
- "Dagdeel:" (Time of day)
- "Leeftijdsgroep:" (Age group)
- "Naam (max 30 tekens):" (Name - max 30 chars)
- "Beschrijving (max 200 tekens):" (Description - max 200 chars)

**Select Options for "Dagdeel" (Time Slots):**
- "Ochtend" (Morning)
- "Middag" (Afternoon)
- "Avond" (Evening)

**Select Options for "Leeftijd" (Age Groups):**
- "10-12"
- "13-15"
- "16-18"

**Submit Button:** "Toevoegen" (Add)

### Activities Display Section
**Section Heading:** "Alle activiteiten" (All Activities)

**Day Headers:** "Dag 1", "Dag 2", "Dag 3", "Dag 4", "Dag 5" (Day 1-5)

**Time Slot Headers:**
- "Ochtend" (Morning)
- "Middag" (Afternoon)
- "Avond" (Evening)

### Messages
- Success: "Activiteit toegevoegd!" (Activity added!)
- Success: "Activiteit verwijderd!" (Activity deleted!)
- Error: "Vul alle verplichte velden in (naam max 30, beschrijving max 200)." (Fill all required fields...)
- Empty: "Geen activiteiten." (No activities)
- **Delete button:** "Verwijderen" (Delete)
- **Confirmation dialog:** "Weet je zeker dat je deze activiteit wilt verwijderen?" (Are you sure you want to delete this activity?)

---

## 6. upload.php - Artwork Upload
**Purpose:** Upload artwork to the gallery

### Page Title/Heading
- **Title (HTML):** "Kunstwerk Uploaden - KVW Gorinchem"
- **H2 Heading:** "Kunstwerk Uploaden" (Upload Artwork)

### Navigation Links
```
- "Home"
- "Kunstcabinet Bekijken" (View Art Cabinet)
- "Paleis" (Palace)
- "Uitloggen" (Logout)
```

### Upload Form
**Form Labels:**
- "Titel van het kunstwerk:" (Title of the artwork)
- "Selecteer afbeelding:" (Select image)

**Preview Section Label:**
- "Voorbeeld:" (Preview)

**Submit Button:** "Kunstwerk Inleveren" (Submit Artwork)

### Navigation Button
- "Terug naar Kunstcabinet" (Back to Art Cabinet)

### Error/Success Messages
- "Upload successful!" (NOT YET MEDIEVAL-THEMED)
- "Database error."
- "Upload failed."
- "Only JPG, JPEG, PNG & WEBP allowed." (NOT YET MEDIEVAL-THEMED)

### File Input
- Accept: image files only

---

## 7. index.php - Home Page (Public)
**Purpose:** Welcome/home page visible to all

### Page Title/Heading
- **Title (HTML):** "KVW Gorinchem 2026 - Middeleeuwen"
- **H1 Heading:** "Welkom bij KVW Gorinchem 2026!" (Welcome to KVW Gorinchem 2026!)
- **H2 Heading:** "Thema: Middeleeuwen" (Theme: Middle Ages)

### Navigation Links
```
- "Home"
- "Dagprogramma" (Daily Program)
- "Foto's & Video's" (Photos & Videos)
- "Nieuws" (News)
- "Begeleiders Login" (Leaders Login)
```

### Content Sections
**Section Heading:** "Welkom in het Koninkrijk!" (Welcome to the Kingdom!)

**Introductory Text:**
- "Van 20 t/m 24 juli gaan we terug naar de Middeleeuwen!" (From July 20-24 we go back to the Middle Ages!)
- "Beleef avonturen met ridders, kastelen en spannende spellen." (Experience adventures with knights, castles, and exciting games.)
- "*Bereidt je goed voor op dit legendaire avontuur, edele ridder!*" (*Prepare yourself well for this legendary adventure, noble knight!*)

**Royal Decree Section Heading:** "Koninklijk Decreet" (Royal Decree)
- Dynamic content from latest news item
- Empty state: "Geen nieuws beschikbaar." (No news available)

### Footer
- "&copy; 2026 KVW Gorinchem"

---

## 8. program.php - Daily Program (Public)
**Purpose:** Display the daily schedule/activities

### Page Title/Heading
- **Title (HTML):** "Koninklijk Dagprogramma - KVW Gorinchem"
- **H1 Heading:** "Het Koninklijke Dagprogramma" (The Royal Daily Program)
- **H2 Heading:** "Thema: Middeleeuwen" (Theme: Middle Ages)

### Navigation Links
```
- "Home"
- "Dagprogramma" (Daily Program)
- "Foto's & Video's" (Photos & Videos)
- "Nieuws" (News)
- "Beheer" (Management) - if logged in
- "Uitloggen" (Logout) - if logged in
- "Begeleiders Login" (Leaders Login) - if not logged in
```

### Admin Link
- "Activiteiten beheren" (Manage Activities) - if logged in
- CSS class: "upload-btn"

### Status Messages
- Message display from `$message` variable
- Success: "Activiteit verwijderd!" (Activity deleted!) - shown when admin deletes

---

## Summary of Text Categories

### Navigation Text (Multi-page)
- Navigation items appear across most pages
- Some items are context-specific (Beheer vs Login)

### Form Elements Needing Review
1. **Labels** - Generally already medieval-themed (Burcht, Paleis, Decreet, etc.)
2. **Placeholders** - Mix of medieval and neutral terms
3. **Buttons** - Mix of medieval (Afkondigen, Toegang Verlenen) and neutral (Toevoegen, Verwijderen)

### English Terms Not Yet Medieval-Themed (in upload.php & login.php)
- "Incorrect password."
- "User not found."
- "Upload successful!"
- "Database error."
- "Upload failed."
- "Only JPG, JPEG, PNG & WEBP allowed."

### Already Medieval-Themed Elements ✓
- "Koninklijk" (Royal)
- "Paleis" (Palace)
- "Burcht" (Fortress)
- "Kronieken" (Chronicles)
- "Kunstcabinet" (Art Cabinet)
- "Kunstwerken" (Artworks)
- "Decreet" (Decree)
- "Afkondigen" (Proclaim)
- "Koninkrijk" (Kingdom)

### Neutral/Generic Terms (Could be Enhanced)
- "Nieuws" (News) → could be "Koninklijke Boodschappen" (Royal Messages)
- "Foto's & Video's" → could be "Beelden & Voorstellingen"
- "Beheer" (Management) → already acceptable in context
- "Uit het Paleis" (Exit the Palace) → already themed
- "Terug naar..." (Back to) → already themed

---

## Files Analyzed
✓ admin.php
✓ login.php
✓ news.php
✓ photos.php
✓ dagprogramma.php
✓ upload.php
✓ index.php
✓ program.php

**Total UI Text Elements Identified:** 100+
**Already Medieval-Themed:** ~80%
**Requiring Updates:** English error messages, some placeholders
