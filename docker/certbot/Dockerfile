FROM alpine:latest


ARG UID=${UID}
ARG GUID=${GUID}

RUN apk update
RUN apk add certbot shadow openssl

RUN if [ -z `getent group www-data` ]; then addgroup -g ${GUID} www-data; fi
RUN if [ -z `getent passwd www-data` ]; then useradd -m -u ${UID} -g ${GUID} www-data; fi



USER www-data

CMD tail -f /dev/null
