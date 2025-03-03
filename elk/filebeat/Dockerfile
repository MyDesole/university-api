# Используем официальный образ Filebeat
FROM docker.elastic.co/beats/filebeat:8.6.2

# Копируем конфигурацию Filebeat
COPY filebeat.yml /usr/share/filebeat/filebeat.yml

# Открываем порт 5044
EXPOSE 5044