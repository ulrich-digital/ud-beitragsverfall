# Beitragsverfall Block (WordPress Plugin)

**Erweiterung für den WordPress-Editor zur Steuerung von Beitragsgültigkeit.**  
Mit diesem Plugin lässt sich im Block-Editor einstellen, ab wann ein Beitrag als „verfallen“ gilt – etwa für zeitlich begrenzte Informationen, Kampagnen oder Hinweise.

## Was macht das Plugin?

Das Plugin fügt dem Editor ein Eingabefeld für ein Ablaufdatum hinzu. Nach diesem Zeitpunkt wird der Beitrag **automatisch auf "Entwurf" gesetzt**.  
Das ist ideal für Inhalte mit Ablaufdatum – zum Beispiel Aktionen, Veranstaltungen oder temporäre Hinweise.

Die Funktion steht für **normale Beiträge (Posts)** ebenso wie für **Custom Post Types (CPTs)** zur Verfügung.

## Funktionen im Überblick

- Steuerung von Gültigkeit / Verfall direkt im Editor
- Auswahl eines konkreten Datums (optional mit Uhrzeit)
- Automatisches Zurücksetzen des Beitrags auf „Entwurf“ nach Ablauf
- Funktioniert mit Beiträgen und Custom Post Types
- Keine externe Konfiguration notwendig

## Vorschau im Editor

![Beitragsverfall Editor](./assets/beitragsverfall_editor.png)

*Abbildung: Eingabefeld im Editor zur Festlegung eines Ablaufdatums für den Beitrag.*

## Installation

1. Plugin in `wp-content/plugins/` ablegen
2. Aktivieren
3. Im Editor unter Beitrag → „Verfall“ ein Ablaufdatum festlegen

<br><br><br><br><br>
# Kurze Entwickleranleitung

Kurzanleitung zur lokalen Weiterentwicklung des Plugins.

## 1. Projekt einrichten

Lade das Plugin herunter:
Auf „Code → Download ZIP“ klicken und das Plugin entpacken.

## 2. WordPress (Docker) starten

Nutze wp-env für eine lokale WordPress-Umgebung.
Öffne ein Terminal (macOS, Linux) oder PowerShell / Git Bash (Windows):
```bash
npx @wordpress/env start
```
Das Plugin liegt dabei in wp-content/plugins/.

## 3. Abhängigkeiten installieren und entwickeln
```bash
cd /pfad/zu/deinem/plugin  #navigiere zu deinem Plugin.
npm install                #lädt benötigte Node-Modules
npm start                  # startet den Watch-Modus für /src
```
Änderungen in src/ werden automatisch nach build/ geschrieben.

## 4. Build für Live-Einsatz
```bash
npm run build
```
Erzeugt einen optimierten, produktionsfertigen Build im Ordner build/.

## 5. Welche Dateien werden benötigt?

Für den produktiven Einsatz im WordPress-Plugin-Verzeichnis werden nur die folgenden Bestandteile benötigt:

- `build/` (vom Build-Prozess generiert)
- `block.json`
- PHP-Dateien (z. B. `plugin.php`, `render.php`, etc.)
- CSS-Dateien (z. B. `style.css`, `editor.css`)
- Optional: `assets/` (z. B. für Bilder oder Icons)

Nicht erforderlich (und typischerweise ausgeschlossen):

- `node_modules/`
- `src/`
- `.git/`
- `.gitignore`
- `package.json`, `package-lock.json`
- `.editorconfig`, `.eslintrc.js` usw.

Diese Dateien sind nur für die Entwicklung relevant und sollten nicht ins produktive WordPress-Setup kopiert werden.
