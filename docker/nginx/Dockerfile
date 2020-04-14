ARG NGINX_VERSION
FROM nginx:${NGINX_VERSION}

COPY nginx.conf /etc/nginx/
COPY symfony.conf /etc/nginx/sites-available/symfony.conf

RUN rm /etc/nginx/conf.d/default.conf
RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN usermod -u 1000 www-data

CMD ["nginx"]

EXPOSE 80 443
