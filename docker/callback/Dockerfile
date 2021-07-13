FROM alpine

RUN apk add wget

COPY script.sh /opt

RUN chmod +x /opt/script.sh

ENTRYPOINT ["/opt/script.sh"]
