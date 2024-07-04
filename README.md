# wizAi-api

## Production
### Versioning

Die Versionierung dieser API folgt dem Prinzip [Semantic Versioning](https://semver.org/).
Eine kleinere Version der API wird alle sechs Monate veröffentlicht. Die Hauptversion wird alle zwei Jahre veröffentlicht. Die Versionierung ist in zwei Branches enthalten:
- Stable: (derzeit Version 3.2): regelmäßige Fehlerbehebung.
- Long-Term Support: (Version 2.4): Unterstützung für Bugs und Sicherheit. In der Produktion empfohlen

Alle früheren Versionen (1.2, 1.3, 1.4) werden nicht mehr gepflegt.
### Performance und Cache

Aus Leistungsgründen wurde ein Varnish-Reverse-Proxy dem Webserver vorgeschaltet und speichert alle von der API zurückgegebenen Antworten. Das bedeutet, dass nach der ersten Anfrage alle nachfolgenden Anfragen nicht auf den Webserver treffen, sondern sofort aus dem Cache bedient werden.

Legen Sie die Cache-Strategie in headers cache-control des Headers fest.
```
Cache-Control: max-age=60, public, s-maxage=120
```

Um die Anzahl der Anfragen an die API zu begrenzen, sollten die HTTP-Header wie folgt konfiguriert werden

```
X-RateLimit-Limit 
X-RateLimit-Remaining
```

Bei Überschreitung dieser Werte wird eine Antwort 429 Too Many Requests von der API zurückgegeben

## Daten aus Gorest aktualisieren 

Um die Daten aus der Gorest.co.in-API auf dem neuesten Stand zu halten, startet ein automatischer Task (Cronjob) einen wöchentlichen php-Befehl. 