const formatPrice = (value) => new Intl.NumberFormat('ru-RU').format(Math.max(0, Math.round(value || 0)));

export default function calculatorComponent({ services = [], selectedService = '' } = {}) {
    return {
        services,
        serviceId: selectedService || '',
        area: 50,
        options: [],
        result: null,
        loading: false,
        error: null,
        requestTimer: null,

        init() {
            this.$watch('serviceId', () => this.scheduleCalculation());
            this.$watch('area', () => this.scheduleCalculation());
            this.$watch('options', () => this.scheduleCalculation());

            this.scheduleCalculation();
        },

        scheduleCalculation() {
            clearTimeout(this.requestTimer);
            this.requestTimer = window.setTimeout(() => {
                this.calculate();
            }, 180);
        },

        async calculate() {
            if (!this.selectedServiceId) {
                this.result = null;
                this.error = null;
                return;
            }

            this.loading = true;
            this.error = null;

            try {
                const response = await fetch('/calculator/calculate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({
                        service_id: this.selectedServiceId,
                        area: this.area,
                        options: this.options,
                    }),
                });

                if (!response.ok) {
                    throw new Error('Ошибка расчета');
                }

                this.result = await response.json();
            } catch (error) {
                this.error = error.message;
            } finally {
                this.loading = false;
            }
        },

        get currentService() {
            return this.services.find((service) => service.slug === this.serviceId) ?? null;
        },

        get selectedServiceId() {
            return this.currentService?.id ?? '';
        },

        get selectedServiceName() {
            return this.currentService?.name ?? 'Не выбрана';
        },

        get selectedDuration() {
            return this.result?.service?.duration ?? this.currentService?.duration ?? 'Будет зависеть от выбранной услуги';
        },

        get areaLabel() {
            return `${this.area} м²`;
        },

        get totalPrice() {
            return this.result?.total_price ?? 0;
        },

        get roundedTotalPrice() {
            return Math.round(this.totalPrice);
        },

        get formattedTotalPrice() {
            return `${formatPrice(this.totalPrice)} BYN`;
        },
    };
}
