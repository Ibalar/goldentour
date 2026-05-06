import './bootstrap';
import Alpine from 'alpinejs';
import calculatorComponent from './components/calculator';

const maskPhone = (value) => {
    const digits = value.replace(/\D/g, '');
    let normalized;

    if (digits.startsWith('375')) {
        normalized = digits.slice(0, 12);
    } else if (digits.startsWith('80')) {
        normalized = `375${digits.slice(2)}`.slice(0, 12);
    } else if (digits.startsWith('0')) {
        normalized = `375${digits.slice(1)}`.slice(0, 12);
    } else if (digits.length <= 9) {
        normalized = `375${digits}`.slice(0, 12);
    } else {
        normalized = `375${digits.slice(-9)}`.slice(0, 12);
    }

    const parts = normalized.slice(3);

    let result = '+375';
    if (parts.length > 0) result += ` (${parts.slice(0, 2)}`;
    if (parts.length >= 2) result += ')';
    if (parts.length > 2) result += ` ${parts.slice(2, 5)}`;
    if (parts.length > 5) result += `-${parts.slice(5, 7)}`;
    if (parts.length > 7) result += `-${parts.slice(7, 9)}`;
    return result;
};

const setupPhoneMasks = () => {
    document.querySelectorAll('[data-phone-mask]').forEach((input) => {
        input.addEventListener('input', () => {
            input.value = maskPhone(input.value);
        });
    });
};

const setupLeadForms = () => {
    document.querySelectorAll('[data-lead-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('[data-submit-button]');
            const defaultState = form.querySelector('[data-submit-default]');
            const loadingState = form.querySelector('[data-submit-loading]');

            if (button) {
                button.setAttribute('disabled', 'disabled');
                button.classList.add('opacity-70');
            }

            defaultState?.classList.add('hidden');
            loadingState?.classList.remove('hidden');
        });
    });
};

window.Alpine = Alpine;
Alpine.data('calculatorComponent', calculatorComponent);

document.addEventListener('DOMContentLoaded', () => {
    setupPhoneMasks();
    setupLeadForms();
});

Alpine.start();
