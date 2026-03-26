Questo progetto è relativo al sito dell'Area Urbana Funzionale Sicilia Occidentale, brevemente e meno formalmente detta FUA Trapani.



Il tema è basato su https://github.com/italia/design-comuni-wordpress-theme

il tema base lo abbiamo clonato su https://github.com/comuneditrapani/design-fua-wordpress-theme

# Guida rapida: Migrare WordPress con Duplicator

descrizione di come migrare il sito WordPress da un ambiente locale/staging a un altro host (produzione o nuovo staging) usando il plugin **Duplicator**.

---

## Prerequisiti

Assicurati di avere:
- Accesso **FTP/SFTP/SSH** al server di destinazione  
- Accesso al server di destinazione per creare:
  - un nuovo **database MySQL**  
  - un **utente con privilegi** su quel database  
- PHP e MySQL compatibili tra il server sorgente e quello di destinazione.

---

## Passo 1: Preparare il sito di origine

1. Apri il sito WordPress (locale o staging).  
2. Installa e attiva il plugin **Duplicator**:
   - Da admin: `Plugin → Aggiungi nuovo → Cerca "Duplicator"` → Installa e attiva.  
3. Verifica che:
   - Tutte le estensioni e i temi siano aggiornati  
   - Non ci siano errori critici in `Registri degli errori` o `WP_DEBUG`.

---

## Passo 2: Creare il pacchetto con Duplicator

1. Vai in: **Duplicator → Backups → Add New**.  
2. Assegna un nome al pacchetto (es. `site-backup-yyyy-mm-dd`).  
3. Usa il preset **Full Site** (file + database).  
4. Clicca **Create Backup**.
   - Duplicator farà una scansione; se vedi avvisi minori, di solito puoi ignorarli.  
5. Al termine:
   - Scarica i due file:
     - `installer.php`  
     - `archive.zip`  
   - Tienili insieme in una cartella sicura.

---

## Passo 3: Preparare il server di destinazione

1. Dal pannello dell’hosting:
   - Crea un nuovo **database** (es. `wp_staging_db`).  
   - Crea un **utente MySQL** con password e assegnagli tutti i privilegi.  
   - Annota:
     - `DB_NAME`  
     - `DB_USER`  
     - `DB_PASSWORD`  
     - `DB_HOST` (di solito `localhost` o IP del server dove risiede il database).  
2. Crea (se non c’è ancora) una cartella vuota per il sito:
   - es. `/var/www/domain.com` o una sottodirectory dedicata.  

---

## Passo 4: Caricare il pacchetto su destinazione

1. Collegati al server tramite **FTP/SFTP/SSH/WINSCP**.  
2. Apri la cartella root del sito di destinazione.  
3. Carica **entrambi** i file:
   - `installer.php`  
   - `archive.zip`  
4. Assicurati che la cartella sia **vuota** prima del caricamento, a meno che tu stia sostituendo un sito esistente.

---

## Passo 5: Eseguire l’installer di Duplicator

1. Nel browser apri:
   - `https://tuodominio.com/installer.php`  
2. Apparirà l’installazione guidata di Duplicator.  
3. Inserisci:
   - Nome del database (`DB_NAME`)  
   - Utente (`DB_USER`)  
   - Password (`DB_PASSWORD`)  
   - Host (`DB_HOST`, in genere `localhost`).  
4. Lascia gli altri parametri ai valori predefiniti, a meno che tu non sappia cosa cambiare.  
5. Lancia la procedura:
   - Duplicator estrarrà i file e importerà il database.  
   - Aggiorna automaticamente gli URL (es. da `http://localhost` a `https://tuodominio.com`).  
6. Alla fine:
   - Spunta **Remove installer files** per sicurezza.  
   - Accedi a WordPress con le stesse credenziali del sito sorgente.

---

## Passo 6: Configurazione post‑migrazione

Sul sito di destinazione:

1. Vai in **Impostazioni → Generali** e verifica:
   - **Indirizzo WordPress (URL)**  
   - **Indirizzo sito (URL)**  
   siano `https://tuodominio.com` (o il dominio corretto).  
2. Vai in **Impostazioni → Permalink** e clicca **Salva** (rigenera `.htaccess`).  
3. Disattiva (temporaneamente):
   - plugin di cache  
   - plugin di minificazione/ottimizzazione  
   - CDN  
4. Controlla che:
   - Pagine, post e immagini caricano correttamente  
   - Non ci siano errori critici nella dashboard.

---

## Note di sicurezza

- Elimina sempre `installer.php` e `archive.zip` dopo il completamento.    
- Conserva il backup Duplicator in un luogo sicuro per eventuali rollback.
