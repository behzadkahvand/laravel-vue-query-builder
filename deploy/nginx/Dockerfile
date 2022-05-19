FROM m.docker-registry.ir/nginx:alpine

MAINTAINER Behzad Kahvand <behzad.kahvand@gmail.com>

COPY nginx.conf /etc/nginx/
COPY sites/default.conf /etc/nginx/sites-available/

ARG PHP_UPSTREAM_SERVICE=fpm
ARG PHP_UPSTREAM_PORT=9000

# Install necessary packages
RUN apk update \
    && apk upgrade \
    && apk add --no-cache openssl bash tzdata \
    && adduser -D -H -u 1000 -s /bin/bash www-data -G www-data

# Set timezon to Tehran
ENV TZ UTC

# Set upstream conf and remove the default conf
RUN echo "upstream php-upstream { server ${PHP_UPSTREAM_SERVICE}:${PHP_UPSTREAM_PORT}; }" >> /etc/nginx/conf.d/upstream.conf

RUN rm /etc/nginx/conf.d/default.conf

COPY startup.sh /opt/startup.sh
RUN chmod +x /opt/startup.sh
RUN sed -i 's/\r//g' /opt/startup.sh
CMD ["/bin/bash", "/opt/startup.sh"]

EXPOSE 80 443
