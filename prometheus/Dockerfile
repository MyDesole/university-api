# Используем официальный образ Prometheus
FROM prom/prometheus:latest

# Копируем конфигурацию Prometheus
COPY prometheus.yml /etc/prometheus/prometheus.yml

# Открываем порт 9090
EXPOSE 9090