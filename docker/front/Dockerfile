FROM alpine:3.9

ARG UID=${UID}
ARG GUID=${GUID}

RUN apk update && apk add nodejs npm yarn net-tools vim git zip shadow

RUN if [ -z `getent group www-data` ]; then addgroup -g ${GUID} www-data; fi
RUN if [ -z `getent passwd www-data` ]; then useradd -m -u ${UID} -g ${GUID} www-data; fi

WORKDIR /srv

USER www-data

CMD tail -f /dev/null

