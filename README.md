clonar repositorio
- frontend: copiar node_modules
- backend: copiar vendor, .env
- raiz de repo: copiar .env
-  copiar backup db : docker cp backupdb/produccion_06112024.sql srv-mysql:/var/tmp/scripts/produccion_06112024.dump
- docker-compose build
- docker-compose up -d

