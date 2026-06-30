cd "C:\wamp64\bin\mysql\mysql9.1.0\bin"

mysqldump -h localhost -u root caisse > "C:\wamp64\www\caisse\backup\caisse_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%.sql" 