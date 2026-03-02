FROM nginx:alpine

# Rimuoviamo la configurazione di default di Nginx
RUN rm /etc/nginx/conf.d/default.conf

# Copiamo la nostra configurazione personalizzata nel container
COPY default.conf /etc/nginx/conf.d/