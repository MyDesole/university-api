# Используем официальный образ Logstash
FROM docker.elastic.co/logstash/logstash:8.6.2

# Копируем конфигурацию Logstash
COPY logstash.conf /usr/share/logstash/pipeline/logstash.conf

# Открываем порт 5044
EXPOSE 5044