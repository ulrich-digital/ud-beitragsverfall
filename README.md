# UD Plugin: Beitragsverfall 

Plugin zur zeitgesteuerten Veröffentlichung und automatischen Deaktivierung von Beiträgen, Seiten und Custom Post Types.
Ermöglicht die Festlegung eines Ablaufdatums, ab dem ein Beitrag automatisch als *abgelaufen* gilt und im Frontend nicht mehr sichtbar ist. 



## Funktionen

- Fügt eine Verfallsdatum-Metabox zu Beiträgen, Seiten und CPTs hinzu
- Automatisches Setzen des Status *Abgelaufen (expired)* bei Erreichen des Datums
- Beiträge mit diesem Status sind standardmässig im Frontend ausgeblendet
- Auswahl, für welche Beitragstypen die Ablaufprüfung aktiv ist
- Option für Testmodus (Cron alle 5 Minuten statt stündlich)
- Übersicht und Steuerung der Funktion unter `Einstellungen → Beitragsverfall`
- Automatische Prüfung über einen geplanten WordPress-Cronjob

## Screenshots

![Beitragsverfall Editor](./assets/beitragsverfall_editor.png)
*Eingabefeld im Editor zur Festlegung eines Ablaufdatums für den Beitrag.*

![Beitragsverfall Editor](./assets/settings.png)
*In der Einstellungsseite können Beitragstypen aktiviert oder deaktiviert werden. Zusätzlich lässt sich ein Testmodus einschalten, um die Ablaufprüfung häufiger (alle 5 Minuten) auszuführen.*


## Autor

[ulrich.digital gmbh](https://ulrich.digital)


## Lizenz

GPL v2 or later
[https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)



<!--
Interne Verwendung:
Eingesetzt in den Projekten
- illgau.ch
- schule.illgau.ch
- bbzg.ch
-->
