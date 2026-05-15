II. Konfiguracja CMS

5. Witryna oparta na CMS uwzględniająca wbudowane szablony widoku (po instalacji i dodaniu treści)
6. Dostosowanie nowego szablonu HTML (szablon strony + szablon menu)
7. Aktualności i galeria

Wordpress uruchomiony na vm w GCP.
http://34.73.39.225/

VM SPOT, po zmianie adresu wymagane uruchomienie:
```
docker-compose exec db mariadb -u ... -p... wordpress -e "
UPDATE wp_options SET option_value='http://34.73.39.225' WHERE option_name='siteurl';
UPDATE wp_options SET option_value='http://34.73.39.225' WHERE option_name='home';
"
```

Wykorzystany docker compose
```yaml
services:
  db:
    image: mariadb:11
    restart: always
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: ...
      MYSQL_PASSWORD: ...
      MYSQL_ROOT_PASSWORD: ...
    volumes:
      - db_data:/var/lib/mysql

  wordpress:
    image: wordpress:latest
    restart: always
    depends_on:
      - db
    ports:
      - "80:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: ...
      WORDPRESS_DB_PASSWORD: ...
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - wp_data:/var/www/html
      - ./pss-motyw:/var/www/html/wp-content/themes/pss-motyw

volumes:
  db_data:
  wp_data:
```
