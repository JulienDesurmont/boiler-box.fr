#!/bin/bash

version='boilerbox_4.1.0'

rm /srv/www/htdocs/$version/web/logs/connexions.log
rm /srv/www/htdocs/$version/web/tmp/*

rm /srv/www/htdocs/$version/web/uploads/bonsAttachement/*
rm /srv/www/htdocs/$version/web/uploads/bonsAttachement/enquetes/*
rm /srv/www/htdocs/$version/web/uploads/bonsAttachement/fichiersDesBons/*

rm /srv/www/htdocs/$version/web/uploads/files/*

rm /srv/www/htdocs/$version/web/uploads/problemes/*

rm -rf /srv/www/htdocs/$version/app/cache/*

rm /srv/www/htdocs/$version/app/logs/*

chmod 444 /srv/www/htdocs/$version/utilitaires/nettoyageDev.sh


echo "Ne pas oublier de modifier les paramètre Twig en ajoutant [BoilerBox] en production !!!"
