FROM bitnami/kafka:latest

# Копируем скрипт настройки Kafka
COPY kafka-setup.sh /kafka-setup.sh

# Делаем скрипт исполняемым
RUN chmod +x /kafka-setup.sh

# Запускаем скрипт при старте контейнера
CMD ["/kafka-setup.sh"]