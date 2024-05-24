Uno scriptarello per interrogare il database del sito e pescare i valori dalle tabelle [post_url], [meta_title] e [meta_description] di Yoast SEO.
La teoria è quella che fa una delle feature più importanti presenti solo su Yoast SEO PRO, ovvero:

- Interrogare il database;
- printare i risultati in un csv, splittarli in colonne;
- restituirli come file scaricabile (o printato a schermo, boh non conosco Yoast Pro)

Carica lo script nella root del sito, pusha [urldelsito]/esporta-yoast.php e, a seconda della complessità della tabella, verrà generato un file csv salvato in locale.
Dato che ho esperienza con vari CRM dove esportare un csv è un dramma, ho aggiunto anche il BOM e la codifica UTF-8, così evitiamo di non farci riconoscere i caratteri accentati.

Ho aggiunto un parametro admin per sicurezza, ma è estremamente basilare, ci lavorerò meglio sulla sicurezza nei ritagli di tempo,
dato che sto script è nato da una necessità e avevo tempo solo durante una pausa pranzo a lavoro e non è che mi sono messo a fare chissà quali sanificazioni...
