FROM nginx:1.25.3-alpine as local
RUN apk --no-cache add curl ca-certificates
WORKDIR /php-app
ENV PORT 80
COPY docker/nginx/local.conf /etc/nginx/conf.d/default.conf
